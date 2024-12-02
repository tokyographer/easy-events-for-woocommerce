(function ($) {
    $(document).ready(function () {
        // Listen for Quick Edit button click
        $(document).on('click', '.editinline', function () {
            // Extract Post ID from the row's ID attribute
            var postId = $(this).closest('tr').attr('id').replace('post-', '');

            if (!postId) {
                console.error('Post ID not found for Quick Edit operation');
                return;
            }

            console.log('Quick Edit triggered for Post ID:', postId);

            // Locate the table row
            var rowElement = $('#post-' + postId);
            if (rowElement.length === 0) {
                console.error('Row not found for Post ID:', postId);
                return;
            }

            // Populate Event Start Date
            var startDateElement = rowElement.find('.column-event_start_date');
            if (startDateElement.length === 0) {
                console.warn('Start Date column not found for Post ID:', postId);
            } else {
                var startDate = startDateElement.text().trim();
                // $('input[name="_event_start_date"]').val(startDate || '');
                console.log('Start Date:', startDate || 'Not Found');
            }

            // Populate Event End Date
            var endDateElement = rowElement.find('.column-event_end_date');
            if (endDateElement.length === 0) {
                console.warn('End Date column not found for Post ID:', postId);
            } else {
                var endDate = endDateElement.text().trim();
              //  $('input[name="_event_end_date"]').val(endDate || '');
                console.log('End Date:', endDate || 'Not Found');
            }

            // Populate Event Location
            var locationElement = rowElement.find('.taxonomy-event_location a');
            if (locationElement.length === 0) {
                console.warn('Location column not found for Post ID:', postId);
            } else {
                var eventLocation = locationElement.text().trim();
                console.log('Event Location:', eventLocation || 'Not Found');

                // Update dropdown
                var locationDropdown = $('select[name="_event_location"]');
                if (locationDropdown.length === 0) {
                    console.error('Location dropdown not found in Quick Edit form');
                } else {
                    locationDropdown.find('option').each(function () {
                        if ($(this).text().trim() === eventLocation) {
                            $(this).prop('selected', true);
                        } else {
                            $(this).prop('selected', false);
                        }
                    });
                }
            }
        });
    });
})(jQuery);