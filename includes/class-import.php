<?php
/**
 * Import functionality for Pincode Finder Lite
 *
 * Handles CSV import and processing.
 *
 * @package PincodeFinderLite
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class PFL_Import
 */
class PFL_Import {

    /**
     * Database table name
     *
     * @var string
     */
    private $table;

    /**
     * Constructor
     *
     * @param string $table Database table name
     */
    public function __construct($table) {
        $this->table = $table;
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('admin_post_pfl_import', [$this, 'handle_import']);
    }

    /**
     * Handle CSV import request
     */
    public function handle_import() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Unauthorized', 'network-search'));
        }

        check_admin_referer('pfl_import_nonce', 'pfl_import_nonce');

        if (empty($_FILES['pfl_csv']['tmp_name'])) {
            wp_die(__('No file uploaded.', 'network-search'));
        }

        $overrides = [
            'test_form' => false,
            'mimes' => [
                'csv' => 'text/csv',
                'txt' => 'text/plain',
                'application/csv' => 'application/csv',
                'text/comma-separated-values' => 'text/comma-separated-values'
            ]
        ];

        $uploaded = wp_handle_upload($_FILES['pfl_csv'], $overrides);
        if (isset($uploaded['error'])) {
            wp_die(__('Upload error: ', 'network-search') . esc_html($uploaded['error']));
        }

        $mode = (isset($_POST['pfl_mode']) && $_POST['pfl_mode'] === 'append') ? 'append' : 'replace';
        $this->import_csv($uploaded['file'], $mode);

        wp_safe_redirect(add_query_arg(['page' => 'network-search'], admin_url('admin.php')));
        exit;
    }

    /**
     * Import CSV data into database
     *
     * @param string $path File path
     * @param string $mode Import mode ('replace' or 'append')
     */
    private function import_csv($path, $mode = 'replace') {
        global $wpdb;

        if (!file_exists($path)) {
            return;
        }

        if ($mode === 'replace') {
            $wpdb->query("TRUNCATE TABLE {$this->table}");
        }

        $fh = fopen($path, 'r');
        if (!$fh) {
            return;
        }

        // Handle BOM
        $bom = fread($fh, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            fseek($fh, 0);
        }

        $header = fgetcsv($fh);
        if (!$header) {
            fclose($fh);
            return;
        }

        // Map headers (case/space/dot/paren-insensitive)
        $map = [
            'pincode' => null,
            'city' => null,
            'state' => null,
            'zone' => null,
            'vendor' => null,
            'ofd_tat' => null,
            'location' => null
        ];

        foreach ($header as $i => $h) {
            $k = strtolower(trim(preg_replace('/[\s\.\(\)_-]+/', '', $h)));
            if (in_array($k, ['pincode', 'pin', 'pin_code', 'postal', 'postalcode', 'zipcode'])) {
                $map['pincode'] = $i;
            }
            if (in_array($k, ['city', 'district', 'town'])) {
                $map['city'] = $i;
            }
            if (in_array($k, ['state', 'stateut', 'province'])) {
                $map['state'] = $i;
            }
            if (in_array($k, ['zone', 'deliveryzone', 'servicezone', 'region'])) {
                $map['zone'] = $i;
            }
            if (in_array($k, ['vendor', 'partner', 'carrier', 'courier'])) {
                $map['vendor'] = $i;
            }
            if (in_array($k, ['ofdtat', 'tat', 'ofd', 'ofdtatoutfordeliveryturnaroundtime', 'turnaround', 'turnaroundtime'])) {
                $map['ofd_tat'] = $i;
            }
            if (in_array($k, ['location', 'area', 'locality', 'place', 'neighborhood', 'neighbourhood'])) {
                $map['location'] = $i;
            }
        }

        if ($map['pincode'] === null) {
            fclose($fh);
            wp_die(__('CSV must include a Pincode column.', 'network-search'));
        }

        $insert_tpl = "INSERT INTO {$this->table} (pincode, city, state, zone, vendor, ofd_tat, location) VALUES %s";
        $batch = [];
        $batch_size = 500;

        while (($row = fgetcsv($fh)) !== false) {
            $pin = ($map['pincode'] !== null && isset($row[$map['pincode']])) ? preg_replace('/\D+/', '', $row[$map['pincode']]) : '';
            if ($pin === '') {
                continue;
            }
            $city = ($map['city'] !== null && isset($row[$map['city']])) ? trim($row[$map['city']]) : '';
            $state = ($map['state'] !== null && isset($row[$map['state']])) ? trim($row[$map['state']]) : '';
            $zone = ($map['zone'] !== null && isset($row[$map['zone']])) ? trim($row[$map['zone']]) : '';
            $vend = ($map['vendor'] !== null && isset($row[$map['vendor']])) ? trim($row[$map['vendor']]) : '';
            $tat = ($map['ofd_tat'] !== null && isset($row[$map['ofd_tat']])) ? trim($row[$map['ofd_tat']]) : '';
            $loc = ($map['location'] !== null && isset($row[$map['location']])) ? trim($row[$map['location']]) : '';

            $batch[] = $wpdb->prepare('(%s,%s,%s,%s,%s,%s,%s)', $pin, $city, $state, $zone, $vend, $tat, $loc);

            if (count($batch) >= $batch_size) {
                $wpdb->query(sprintf($insert_tpl, implode(',', $batch)));
                $batch = [];
            }
        }

        if (!empty($batch)) {
            $wpdb->query(sprintf($insert_tpl, implode(',', $batch)));
        }

        fclose($fh);
    }
}