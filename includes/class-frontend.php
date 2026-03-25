<?php
/**
 * Frontend functionality for Pincode Finder Lite
 *
 * Handles shortcode rendering and AJAX lookups.
 *
 * @package PincodeFinderLite
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class PFL_Frontend
 */
class PFL_Frontend {

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
        add_shortcode('pincode_checker', [$this, 'shortcode']);
        add_action('wp_ajax_pfl_lookup', [$this, 'ajax_lookup']);
        add_action('wp_ajax_nopriv_pfl_lookup', [$this, 'ajax_lookup']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_style('network-search', PFL_PLUGIN_URL . 'assets/css/network-search.css', [], PFL_VERSION);
        wp_enqueue_script('network-search', PFL_PLUGIN_URL . 'assets/js/network-search.js', ['jquery'], PFL_VERSION, true);
        wp_localize_script('network-search', 'pfl_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pfl_lookup_nonce'),
            'strings' => [
                'server_error' => __('Server error:', 'network-search'),
                'something_wrong' => __('Something went wrong.', 'network-search'),
                'not_in_delivery_zone' => __('Not in Delivery Zone', 'network-search'),
                'for' => __('for', 'network-search'),
                'no' => __('No', 'network-search'),
                'delivery_available' => __('Delivery Available', 'network-search'),
                'yes' => __('Yes', 'network-search'),
                'result' => __('result', 'network-search'),
                'results' => __('results', 'network-search'),
                'pincode' => __('Pincode', 'network-search'),
                'city' => __('City', 'network-search'),
                'state' => __('State', 'network-search'),
                'zone' => __('Zone', 'network-search'),
                'vendor' => __('Vendor', 'network-search'),
                'ofd_tat' => __('OFD TAT', 'network-search'),
                'location' => __('Location', 'network-search'),
                'error' => __('Error:', 'network-search'),
                'request_failed' => __('request failed', 'network-search'),
            ]
        ]);
    }

    /**
     * Render shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public function shortcode($atts) {
        $uid = 'pfl_' . substr(md5(uniqid('', true)), 0, 8);

        ob_start();
        ?>
        <div id="<?php echo esc_attr($uid); ?>" class="pfl-wrap">
            <form class="pfl-form" autocomplete="off">
                <label for="pfl_q_<?php echo esc_attr($uid); ?>"><strong><?php esc_html_e('Enter Pincode or Area', 'network-search'); ?></strong></label>
                <input id="pfl_q_<?php echo esc_attr($uid); ?>" name="query" type="text" inputmode="search" placeholder="<?php esc_attr_e('e.g., 380001 or Andheri', 'network-search'); ?>" required>
                <button type="submit"><?php esc_html_e('Check', 'network-search'); ?></button>
                <span class="pfl-spin" style="display:none;"><?php esc_html_e('Checking…', 'network-search'); ?></span>
            </form>
            <div class="pfl-out" aria-live="polite"></div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Handle AJAX lookup
     */
    public function ajax_lookup() {
        check_ajax_referer('pfl_lookup_nonce', 'nonce');

        global $wpdb;

        $q = isset($_POST['query']) ? trim(wp_unslash($_POST['query'])) : '';
        if ($q === '') {
            wp_send_json_success(['items' => []]);
        }

        if (ctype_digit($q)) {
            $like = $q . '%';
            $sql = $wpdb->prepare(
                "SELECT pincode, city, state, zone, vendor, ofd_tat, location
                 FROM {$this->table}
                 WHERE pincode = %s OR pincode LIKE %s
                 ORDER BY pincode ASC, city ASC, location ASC
                 LIMIT 800",
                $q, $like
            );
        } else {
            $like = '%' . $wpdb->esc_like($q) . '%';
            $sql = $wpdb->prepare(
                "SELECT pincode, city, state, zone, vendor, ofd_tat, location
                 FROM {$this->table}
                 WHERE city LIKE %s OR location LIKE %s
                 ORDER BY pincode ASC, city ASC, location ASC
                 LIMIT 800",
                $like, $like
            );
        }

        $rows = $wpdb->get_results($sql, ARRAY_A);
        if (!is_array($rows)) {
            $rows = [];
        }

        wp_send_json_success(['items' => $rows]);
    }
}