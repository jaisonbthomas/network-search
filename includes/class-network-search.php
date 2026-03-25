<?php
/**
 * Main Pincode Finder Lite Class
 *
 * Initializes the plugin and coordinates between admin, import, and frontend components.
 *
 * @package PincodeFinderLite
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Pincode_Finder_Lite
 */
class Pincode_Finder_Lite {

    /**
     * Singleton instance
     *
     * @var Pincode_Finder_Lite
     */
    private static $instance = null;

    /**
     * Database table name
     *
     * @var string
     */
    private $table;

    /**
     * Admin handler instance
     *
     * @var PFL_Admin
     */
    public $admin;

    /**
     * Import handler instance
     *
     * @var PFL_Import
     */
    public $import;

    /**
     * Frontend handler instance
     *
     * @var PFL_Frontend
     */
    public $frontend;

    /**
     * Get singleton instance
     *
     * @return Pincode_Finder_Lite
     */
    public static function instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        global $wpdb;
        $this->table = $wpdb->prefix . 'network_search';

        $this->init_hooks();
        $this->load_dependencies();
        $this->init_components();
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        register_activation_hook(PFL_PLUGIN_BASENAME, [$this, 'activate']);

        add_filter('upload_mimes', [$this, 'allow_csv']);

        // Self-heal schema on admin & first frontend hit
        add_action('admin_init', [$this, 'ensure_schema']);
        add_action('init', [$this, 'ensure_schema_once']);
    }

    /**
     * Load required files
     */
    private function load_dependencies() {
        require_once PFL_PLUGIN_DIR . 'includes/class-admin.php';
        require_once PFL_PLUGIN_DIR . 'includes/class-import.php';
        require_once PFL_PLUGIN_DIR . 'includes/class-frontend.php';
    }

    /**
     * Initialize component classes
     */
    private function init_components() {
        $this->admin = new PFL_Admin($this->table);
        $this->import = new PFL_Import($this->table);
        $this->frontend = new PFL_Frontend($this->table);
    }

    /**
     * Plugin activation hook
     */
    public function activate() {
        $this->create_table();
    }

    /**
     * Create database table
     */
    private function create_table() {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $charset = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE {$this->table} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            pincode VARCHAR(20) NOT NULL,
            city VARCHAR(191) NOT NULL DEFAULT '',
            state VARCHAR(191) NOT NULL DEFAULT '',
            zone VARCHAR(191) NOT NULL DEFAULT '',
            vendor VARCHAR(191) NOT NULL DEFAULT '',
            ofd_tat VARCHAR(191) NOT NULL DEFAULT '',
            location VARCHAR(191) NOT NULL DEFAULT '',
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY pin_idx (pincode),
            KEY city_idx (city),
            KEY loc_idx (location),
            KEY zone_idx (zone)
        ) $charset;";
        dbDelta($sql);
    }

    /**
     * Ensure database schema is up to date
     */
    public function ensure_schema() {
        $this->create_table();
        global $wpdb;
        // Best-effort indexes (ignore if exist)
        @$wpdb->query("CREATE INDEX pin_idx ON {$this->table} (pincode)");
        @$wpdb->query("CREATE INDEX city_idx ON {$this->table} (city)");
        @$wpdb->query("CREATE INDEX loc_idx ON {$this->table} (location)");
        @$wpdb->query("CREATE INDEX zone_idx ON {$this->table} (zone)");
    }

    /**
     * Ensure schema only once per request
     */
    public function ensure_schema_once() {
        static $done = false;
        if ($done) {
            return;
        }
        $done = true;
        $this->ensure_schema();
    }

    /**
     * Allow CSV file uploads
     *
     * @param array $mimes Allowed mime types
     * @return array
     */
    public function allow_csv($mimes) {
        $mimes['csv'] = 'text/csv';
        $mimes['txt'] = 'text/plain';
        $mimes['application/csv'] = 'application/csv';
        $mimes['text/comma-separated-values'] = 'text/comma-separated-values';
        return $mimes;
    }
}