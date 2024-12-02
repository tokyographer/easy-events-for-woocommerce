(function ($) {
    $(document).ready(function () {
        // Listen for Quick Edit button click
        $(document).on('click', '.editinline', function () {
            var postId = $(this).closest('tr').data('id');

            if (!postId) {
                console.error('Post ID not found for Quick Edit operation');
                return;
            }

            console.log('Quick Edit triggered for Post ID:', postId);

            // Populate Event Start Date
            var startDate = $('#post-' + postId).find('.column-event_start_date').text().trim();
            $('input[name="_event_start_date"]').val(startDate || '');
            console.log('Start Date:', startDate);

            // Populate Event End Date
            var endDate = $('#post-' + postId).find('.column-event_end_date').text().trim();
            $('input[name="_event_end_date"]').val(endDate || '');
            console.log('End Date:', endDate);

            // Populate Event Location
            var eventLocation = $('#post-' + postId).find('.column-event_location').text().trim();
            console.log('Event Location:', eventLocation);

            // Select the correct location in the dropdown
            $('select[name="_event_location"] option').each(function () {
                if ($(this).text().trim() === eventLocation) {
                    $(this).prop('selected', true);
                } else {
                    $(this).prop('selected', false);
                }
            });
        });
    });
})(jQuery);