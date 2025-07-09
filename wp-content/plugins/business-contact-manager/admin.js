jQuery(document).ready(function($) {
    
    // Handle form submission
    $('#bcm-contact-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = {
            action: 'bcm_save_contact',
            nonce: bcm_ajax.nonce,
            name: $('#name').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            company: $('#company').val(),
            position: $('#position').val(),
            notes: $('#notes').val(),
            contact_id: $('#contact_id').val()
        };
        
        // Show loading state
        var submitButton = $(this).find('input[type="submit"]');
        var originalValue = submitButton.val();
        submitButton.val('Saving...').prop('disabled', true);
        
        $.post(bcm_ajax.ajax_url, formData, function(response) {
            if (response.success) {
                showMessage(response.data.message, 'success');
                $('#bcm-contact-form')[0].reset();
                $('#contact_id').val('');
                submitButton.val('Add Contact');
                
                // Reload the page to refresh the contact list
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                showMessage(response.data.message, 'error');
            }
        })
        .fail(function() {
            showMessage('An error occurred while saving the contact.', 'error');
        })
        .always(function() {
            submitButton.val(originalValue).prop('disabled', false);
        });
    });
    
    // Handle edit contact
    $('.edit-contact').on('click', function() {
        var contactId = $(this).data('id');
        var row = $(this).closest('tr');
        
        // Populate form with contact data
        $('#contact_id').val(contactId);
        $('#name').val(row.find('td:eq(0)').text());
        $('#email').val(row.find('td:eq(1)').text());
        $('#phone').val(row.find('td:eq(2)').text());
        $('#company').val(row.find('td:eq(3)').text());
        $('#position').val(row.find('td:eq(4)').text());
        
        // Change submit button text
        $('#bcm-contact-form input[type="submit"]').val('Update Contact');
        
        // Scroll to form
        $('html, body').animate({
            scrollTop: $('#bcm-contact-form').offset().top - 100
        }, 500);
        
        // Focus on name field
        $('#name').focus();
    });
    
    // Handle delete contact
    $('.delete-contact').on('click', function() {
        if (!confirm('Are you sure you want to delete this contact?')) {
            return;
        }
        
        var contactId = $(this).data('id');
        var row = $(this).closest('tr');
        var deleteButton = $(this);
        
        var formData = {
            action: 'bcm_delete_contact',
            nonce: bcm_ajax.nonce,
            contact_id: contactId
        };
        
        // Show loading state
        deleteButton.text('Deleting...').prop('disabled', true);
        
        $.post(bcm_ajax.ajax_url, formData, function(response) {
            if (response.success) {
                showMessage(response.data.message, 'success');
                row.fadeOut(300, function() {
                    $(this).remove();
                });
            } else {
                showMessage(response.data.message, 'error');
                deleteButton.text('Delete').prop('disabled', false);
            }
        })
        .fail(function() {
            showMessage('An error occurred while deleting the contact.', 'error');
            deleteButton.text('Delete').prop('disabled', false);
        });
    });
    
    // Reset form when clicking Add Contact (when in edit mode)
    $('#bcm-contact-form input[type="submit"]').on('click', function() {
        if ($(this).val() === 'Update Contact' && $('#contact_id').val() === '') {
            $(this).val('Add Contact');
        }
    });
    
    // Cancel edit mode
    $(document).on('keyup', function(e) {
        if (e.keyCode === 27) { // ESC key
            cancelEdit();
        }
    });
    
    // Add cancel button functionality
    function cancelEdit() {
        $('#bcm-contact-form')[0].reset();
        $('#contact_id').val('');
        $('#bcm-contact-form input[type="submit"]').val('Add Contact');
    }
    
    // Show message function
    function showMessage(message, type) {
        var messageClass = type === 'success' ? 'notice-success' : 'notice-error';
        var messageHtml = '<div class="notice ' + messageClass + ' is-dismissible"><p>' + message + '</p></div>';
        
        $('#bcm-message').html(messageHtml);
        
        // Auto-hide after 3 seconds
        setTimeout(function() {
            $('#bcm-message .notice').fadeOut();
        }, 3000);
        
        // Scroll to message
        $('html, body').animate({
            scrollTop: $('#bcm-message').offset().top - 100
        }, 300);
    }
    
    // Search functionality (basic filter)
    var searchTimeout;
    $('<div class="bcm-search-wrapper"><input type="text" id="contact-search" placeholder="Search contacts..." class="regular-text"></div>')
        .insertBefore('#contacts-table-body').parent().find('#contact-search').on('keyup', function() {
        
        clearTimeout(searchTimeout);
        var searchTerm = $(this).val().toLowerCase();
        
        searchTimeout = setTimeout(function() {
            $('#contacts-table-body tr').each(function() {
                var rowText = $(this).text().toLowerCase();
                if (rowText.indexOf(searchTerm) === -1) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        }, 300);
    });
});