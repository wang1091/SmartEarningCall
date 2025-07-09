# Business Contact Manager MVP

A minimum viable product WordPress plugin for managing business contacts with a clean, professional interface.

## Features

- **Contact Management**: Add, edit, and delete business contacts
- **Database Integration**: Custom database table for storing contact information
- **Admin Interface**: Clean WordPress admin interface with AJAX functionality
- **Search Functionality**: Real-time search through contacts
- **Frontend Display**: Shortcode support for displaying contacts on the frontend
- **Responsive Design**: Works on desktop and mobile devices
- **Sample Data**: Includes sample contacts for demonstration

## Installation

1. Upload the `business-contact-manager` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to 'Contacts' in the WordPress admin menu

## Usage

### Admin Interface

1. Go to **Contacts** in your WordPress admin menu
2. Use the form to add new contacts with:
   - Name (required)
   - Email (required)
   - Phone
   - Company
   - Position
   - Notes

3. Edit contacts by clicking the "Edit" button in the contact list
4. Delete contacts by clicking the "Delete" button (with confirmation)
5. Search through contacts using the search box above the contact list

### Frontend Display

Use the `[business_contacts]` shortcode to display contacts on any page or post.

#### Shortcode Parameters

- `limit`: Number of contacts to display (default: 10)
- `company`: Filter by specific company name

#### Examples

```
[business_contacts]
[business_contacts limit="5"]
[business_contacts company="Tech Solutions Inc"]
[business_contacts limit="3" company="Marketing Pro"]
```

## Database Structure

The plugin creates a custom table `wp_business_contacts` with the following fields:

- `id`: Auto-incrementing primary key
- `name`: Contact name (required)
- `email`: Contact email (required)
- `phone`: Contact phone number
- `company`: Company name
- `position`: Job position/title
- `notes`: Additional notes
- `created_at`: Timestamp of creation
- `updated_at`: Timestamp of last update

## File Structure

```
business-contact-manager/
├── business-contact-manager.php (Main plugin file)
├── admin.js (JavaScript for admin functionality)
├── admin.css (Styling for admin interface)
└── README.md (This file)
```

## Technical Details

- **WordPress Version**: 5.0+
- **PHP Version**: 7.4+
- **Dependencies**: jQuery (included with WordPress)
- **Security**: CSRF protection with nonces, data sanitization
- **AJAX**: Modern AJAX implementation for seamless user experience

## Customization

### Styling

Modify `admin.css` to customize the appearance of the admin interface and frontend shortcode display.

### Functionality

The main plugin class `BusinessContactManager` can be extended to add additional features:

- Custom fields
- Import/export functionality
- Email integration
- Advanced search filters
- Contact categories

### Hooks Available

The plugin provides standard WordPress hooks for extension:

- `register_activation_hook`: Database table creation
- `admin_menu`: Admin menu integration
- `wp_ajax_*`: AJAX endpoint handling

## Sample Data

The plugin includes two sample contacts for demonstration:

1. **John Smith** - CEO at Tech Solutions Inc
2. **Sarah Johnson** - Marketing Director at Marketing Pro

Sample data is only added during plugin activation and won't overwrite existing data.

## Security Features

- CSRF protection using WordPress nonces
- Data sanitization on input
- Capability checks for admin functions
- SQL injection prevention with prepared statements
- XSS prevention with proper output escaping

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Internet Explorer 11+

## Troubleshooting

### Plugin not appearing in admin menu
- Check if the plugin is activated
- Verify you have administrator privileges

### AJAX not working
- Ensure jQuery is loaded
- Check browser console for JavaScript errors
- Verify WordPress AJAX endpoints are accessible

### Shortcode not displaying
- Check if you're using the correct shortcode syntax
- Verify there are contacts in the database
- Check if the theme supports shortcodes in the content area

## Development

This is an MVP (Minimum Viable Product) designed to demonstrate core WordPress plugin development concepts:

- Custom database tables
- Admin interface creation
- AJAX implementation
- Shortcode development
- WordPress security best practices

## License

This plugin is provided as-is for educational and demonstration purposes.

## Changelog

### Version 1.0.0
- Initial release
- Basic contact management functionality
- Admin interface with AJAX
- Frontend shortcode display
- Sample data integration