<?php
/**
 * Admin functionality for Pincode Finder Lite
 *
 * Handles the admin menu and page for CSV import.
 *
 * @package PincodeFinderLite
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class PFL_Admin
 */
class PFL_Admin {

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
        add_action('admin_menu', [$this, 'admin_menu']);
    }

    /**
     * Add admin menu
     */
    public function admin_menu() {
        add_menu_page(
            __('Network Search', 'network-search'),
            __('Network Search', 'network-search'),
            'manage_options',
            'network-search',
            [$this, 'admin_page'],
            'dashicons-search',
            56
        );
    }

    /**
     * Render admin page
     */
    public function admin_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        global $wpdb;
        $count = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$this->table}");
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Network Search — Import CSV', 'network-search'); ?></h1>
            <div style="background:#fff8e5;border-left:4px solid #ffb900;padding:12px;margin-bottom:20px;border-radius:4px;">
                <p><strong>📌 <?php esc_html_e('Getting Started with Sample Data', 'network-search'); ?></strong></p>
                <p><?php esc_html_e('A sample CSV file (sample-data.csv) is included for testing. Follow these steps:', 'network-search'); ?></p>
                <ol style="margin:8px 0 0 20px;font-size:14px;line-height:1.8;">
                    <li><?php esc_html_e('Select sample-data.csv file in the upload field below', 'network-search'); ?></li>
                    <li><?php esc_html_e('Click "Import CSV" to load test data', 'network-search'); ?></li>
                    <li><?php esc_html_e('Add [pincode_checker] shortcode to any page', 'network-search'); ?></li>
                    <li><?php esc_html_e('Test search with sample pincodes like 380001, 400001, etc.', 'network-search'); ?></li>
                    <li><?php esc_html_e('When ready, replace with your own CSV data', 'network-search'); ?></li>
                </ol>
            </div>
            <p><?php esc_html_e('Prepare a CSV with headers (any case/spacing is ok):', 'network-search'); ?> <code><?php esc_html_e('Pincode, City, State, Zone, Vendor, OFD TAT, Location', 'network-search'); ?></code> <?php esc_html_e('(no Sr column).', 'network-search'); ?></p>
            <p><strong><?php esc_html_e('Rows in database:', 'network-search'); ?></strong> <?php echo esc_html(number_format($count)); ?></p>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">
                <?php wp_nonce_field('pfl_import_nonce', 'pfl_import_nonce'); ?>
                <input type="hidden" name="action" value="pfl_import" />
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="pfl_csv"><?php esc_html_e('CSV file', 'network-search'); ?></label></th>
                        <td><input type="file" id="pfl_csv" name="pfl_csv" accept=".csv,text/csv" required /></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Import Mode', 'network-search'); ?></th>
                        <td>
                            <label><input type="radio" name="pfl_mode" value="replace" checked> <?php esc_html_e('Replace all data', 'network-search'); ?></label><br>
                            <label><input type="radio" name="pfl_mode" value="append"> <?php esc_html_e('Append', 'network-search'); ?></label>
                        </td>
                    </tr>
                </table>
                <?php submit_button(__('Import CSV', 'network-search')); ?>
            </form>
            <hr>
            <h2><?php esc_html_e('Use on a page', 'network-search'); ?></h2>
            <p><?php esc_html_e('Add this shortcode anywhere:', 'network-search'); ?> <code>[pincode_checker]</code></p>
        </div>
        <?php
    }
}