<?php
/**
 * Plugin Name: Business Contact Manager MVP
 * Description: A minimum viable product for managing business contacts in WordPress
 * Version: 1.0.0
 * Author: MVP Developer
 * Text Domain: business-contact-manager
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('BCM_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('BCM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('BCM_VERSION', '1.0.0');

class BusinessContactManager {
    
    private $table_name;
    
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'business_contacts';
        
        // Hooks
        register_activation_hook(__FILE__, array($this, 'create_tables'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_bcm_save_contact', array($this, 'save_contact'));
        add_action('wp_ajax_bcm_delete_contact', array($this, 'delete_contact'));
        add_shortcode('business_contacts', array($this, 'display_contacts_shortcode'));
    }
    
    /**
     * Create database table on plugin activation
     */
    public function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE {$this->table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            email varchar(100) NOT NULL,
            phone varchar(20),
            company varchar(100),
            position varchar(100),
            notes text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) {$charset_collate};";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        // Add sample data
        $this->add_sample_data();
    }
    
    /**
     * Add sample data for demonstration
     */
    private function add_sample_data() {
        global $wpdb;
        
        $sample_contacts = array(
            array(
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'phone' => '+1-555-0123',
                'company' => 'Tech Solutions Inc',
                'position' => 'CEO',
                'notes' => 'Initial contact from tech conference'
            ),
            array(
                'name' => 'Sarah Johnson',
                'email' => 'sarah.j@marketing.com',
                'phone' => '+1-555-0456',
                'company' => 'Marketing Pro',
                'position' => 'Marketing Director',
                'notes' => 'Interested in collaboration'
            )
        );
        
        foreach ($sample_contacts as $contact) {
            $wpdb->insert($this->table_name, $contact);
        }
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            'Business Contacts',
            'Contacts',
            'manage_options',
            'business-contacts',
            array($this, 'admin_page'),
            'dashicons-groups',
            30
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'toplevel_page_business-contacts') {
            return;
        }
        
        wp_enqueue_script('jquery');
        wp_enqueue_script('bcm-admin', BCM_PLUGIN_URL . 'admin.js', array('jquery'), BCM_VERSION, true);
        wp_localize_script('bcm-admin', 'bcm_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('bcm_nonce')
        ));
        
        wp_enqueue_style('bcm-admin', BCM_PLUGIN_URL . 'admin.css', array(), BCM_VERSION);
    }
    
    /**
     * Admin page content
     */
    public function admin_page() {
        global $wpdb;
        
        // Get all contacts
        $contacts = $wpdb->get_results("SELECT * FROM {$this->table_name} ORDER BY created_at DESC");
        
        ?>
        <div class="wrap">
            <h1>Business Contact Manager</h1>
            
            <div class="bcm-container">
                <!-- Add New Contact Form -->
                <div class="bcm-form-section">
                    <h2>Add New Contact</h2>
                    <form id="bcm-contact-form">
                        <table class="form-table">
                            <tr>
                                <th><label for="name">Name *</label></th>
                                <td><input type="text" id="name" name="name" required class="regular-text"></td>
                            </tr>
                            <tr>
                                <th><label for="email">Email *</label></th>
                                <td><input type="email" id="email" name="email" required class="regular-text"></td>
                            </tr>
                            <tr>
                                <th><label for="phone">Phone</label></th>
                                <td><input type="text" id="phone" name="phone" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th><label for="company">Company</label></th>
                                <td><input type="text" id="company" name="company" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th><label for="position">Position</label></th>
                                <td><input type="text" id="position" name="position" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th><label for="notes">Notes</label></th>
                                <td><textarea id="notes" name="notes" rows="4" class="large-text"></textarea></td>
                            </tr>
                        </table>
                        <p class="submit">
                            <input type="submit" class="button button-primary" value="Add Contact">
                            <input type="hidden" id="contact_id" name="contact_id" value="">
                        </p>
                    </form>
                </div>
                
                <!-- Contacts List -->
                <div class="bcm-list-section">
                    <h2>Contact List</h2>
                    <div id="bcm-message"></div>
                    
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Company</th>
                                <th>Position</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="contacts-table-body">
                            <?php foreach ($contacts as $contact): ?>
                            <tr data-id="<?php echo $contact->id; ?>">
                                <td><?php echo esc_html($contact->name); ?></td>
                                <td><?php echo esc_html($contact->email); ?></td>
                                <td><?php echo esc_html($contact->phone); ?></td>
                                <td><?php echo esc_html($contact->company); ?></td>
                                <td><?php echo esc_html($contact->position); ?></td>
                                <td><?php echo date('M j, Y', strtotime($contact->created_at)); ?></td>
                                <td>
                                    <button class="button edit-contact" data-id="<?php echo $contact->id; ?>">Edit</button>
                                    <button class="button delete-contact" data-id="<?php echo $contact->id; ?>">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Save contact via AJAX
     */
    public function save_contact() {
        check_ajax_referer('bcm_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        global $wpdb;
        
        $contact_data = array(
            'name' => sanitize_text_field($_POST['name']),
            'email' => sanitize_email($_POST['email']),
            'phone' => sanitize_text_field($_POST['phone']),
            'company' => sanitize_text_field($_POST['company']),
            'position' => sanitize_text_field($_POST['position']),
            'notes' => sanitize_textarea_field($_POST['notes'])
        );
        
        $contact_id = intval($_POST['contact_id']);
        
        if ($contact_id > 0) {
            // Update existing contact
            $result = $wpdb->update($this->table_name, $contact_data, array('id' => $contact_id));
            $message = 'Contact updated successfully!';
        } else {
            // Insert new contact
            $result = $wpdb->insert($this->table_name, $contact_data);
            $message = 'Contact added successfully!';
        }
        
        if ($result !== false) {
            wp_send_json_success(array('message' => $message));
        } else {
            wp_send_json_error(array('message' => 'Error saving contact.'));
        }
    }
    
    /**
     * Delete contact via AJAX
     */
    public function delete_contact() {
        check_ajax_referer('bcm_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        global $wpdb;
        
        $contact_id = intval($_POST['contact_id']);
        $result = $wpdb->delete($this->table_name, array('id' => $contact_id));
        
        if ($result !== false) {
            wp_send_json_success(array('message' => 'Contact deleted successfully!'));
        } else {
            wp_send_json_error(array('message' => 'Error deleting contact.'));
        }
    }
    
    /**
     * Display contacts shortcode for frontend
     */
    public function display_contacts_shortcode($atts) {
        global $wpdb;
        
        $atts = shortcode_atts(array(
            'limit' => 10,
            'company' => ''
        ), $atts);
        
        $sql = "SELECT * FROM {$this->table_name}";
        $where_conditions = array();
        
        if (!empty($atts['company'])) {
            $where_conditions[] = $wpdb->prepare("company = %s", $atts['company']);
        }
        
        if (!empty($where_conditions)) {
            $sql .= " WHERE " . implode(' AND ', $where_conditions);
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT " . intval($atts['limit']);
        
        $contacts = $wpdb->get_results($sql);
        
        if (empty($contacts)) {
            return '<p>No contacts found.</p>';
        }
        
        $output = '<div class="business-contacts-list">';
        $output .= '<h3>Business Contacts</h3>';
        
        foreach ($contacts as $contact) {
            $output .= '<div class="contact-card">';
            $output .= '<h4>' . esc_html($contact->name) . '</h4>';
            if ($contact->company) {
                $output .= '<p><strong>Company:</strong> ' . esc_html($contact->company);
                if ($contact->position) {
                    $output .= ' - ' . esc_html($contact->position);
                }
                $output .= '</p>';
            }
            $output .= '<p><strong>Email:</strong> <a href="mailto:' . esc_attr($contact->email) . '">' . esc_html($contact->email) . '</a></p>';
            if ($contact->phone) {
                $output .= '<p><strong>Phone:</strong> ' . esc_html($contact->phone) . '</p>';
            }
            $output .= '</div>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
}

// Initialize the plugin
new BusinessContactManager();