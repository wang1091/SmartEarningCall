<?php
/**
 * WordPress MVP Setup Script
 * Run this script to quickly set up the WordPress installation for testing
 */

// Basic configuration
$db_name = 'wordpress_mvp';
$db_user = 'root';
$db_password = '';
$db_host = 'localhost';

echo "WordPress MVP Setup Script\n";
echo "==========================\n\n";

// Check if wp-config.php exists
if (file_exists('wp-config.php')) {
    echo "✓ wp-config.php found\n";
} else {
    echo "✗ wp-config.php not found\n";
    exit(1);
}

// Check plugin directory
if (is_dir('wp-content/plugins/business-contact-manager')) {
    echo "✓ Business Contact Manager plugin found\n";
} else {
    echo "✗ Business Contact Manager plugin not found\n";
    exit(1);
}

echo "\nSetup Instructions:\n";
echo "==================\n\n";

echo "1. Database Setup:\n";
echo "   - Create a MySQL database named '{$db_name}'\n";
echo "   - Update wp-config.php with your database credentials if needed\n\n";

echo "2. Web Server:\n";
echo "   - Point your web server to this directory\n";
echo "   - Or use PHP's built-in server: php -S localhost:8000\n\n";

echo "3. WordPress Installation:\n";
echo "   - Open your browser and navigate to your site\n";
echo "   - Complete the WordPress setup wizard\n";
echo "   - Create an admin user\n\n";

echo "4. Plugin Activation:\n";
echo "   - Go to WordPress Admin → Plugins\n";
echo "   - Activate 'Business Contact Manager MVP'\n";
echo "   - Navigate to 'Contacts' in the admin menu\n\n";

echo "5. Testing the MVP:\n";
echo "   - Add/edit/delete contacts in the admin interface\n";
echo "   - Test the shortcode [business_contacts] on a page/post\n";
echo "   - Verify the search functionality\n\n";

echo "Features of this MVP:\n";
echo "====================\n";
echo "• Contact Management (CRUD operations)\n";
echo "• Admin Interface with AJAX\n";
echo "• Frontend Display via Shortcode\n";
echo "• Search Functionality\n";
echo "• Responsive Design\n";
echo "• Sample Data Included\n";
echo "• Security Best Practices\n\n";

echo "Database Requirements:\n";
echo "=====================\n";
echo "• MySQL 5.6+ or MariaDB 10.1+\n";
echo "• PHP 7.4+\n";
echo "• WordPress 5.0+\n\n";

echo "For detailed documentation, see:\n";
echo "wp-content/plugins/business-contact-manager/README.md\n\n";

echo "Setup complete! Your WordPress MVP is ready to use.\n";
?>