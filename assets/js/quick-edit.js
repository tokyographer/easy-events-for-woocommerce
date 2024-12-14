(function ($) {
    $(document).ready(function () {
        // Listen for Quick Edit button click
        $(document).on('click', '.editinline', function () {
            // Extract Post ID from the row's ID attribute
            var postId = $(this).closest('tr').attr('id')?.replace('post-', '');
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
            populateField(
                rowElement,
                '.column-event_start_date',
                'input[name="_event_start_date"]',
                'Event Start Date'
            );

            // Populate Event End Date
            populateField(
                rowElement,
                '.column-event_end_date',
                'input[name="_event_end_date"]',
                'Event End Date'
            );

            // Populate Event Location
            populateDropdown(
                rowElement,
                '.taxonomy-event_location a',
                'select[name="_event_location"]',
                'Event Location'
            );
        });

        /**
         * Populate a text field in the Quick Edit form.
         * @param {Object} rowElement - The jQuery object representing the table row.
         * @param {string} columnSelector - The selector for the column containing the value.
         * @param {string} inputSelector - The selector for the input field in the Quick Edit form.
         * @param {string} fieldName - The name of the field (for debugging).
         */
        function populateField(rowElement, columnSelector, inputSelector, fieldName) {
            var columnElement = rowElement.find(columnSelector);
            if (columnElement.length === 0) {
                console.warn(`${fieldName} column not found for Post ID`);
                return;
            }

            var fieldValue = columnElement.text().trim();
            if (!fieldValue) {
                console.warn(`${fieldName} value not found for Post ID`);
            } else {
                console.log(`${fieldName}:`, fieldValue);
                var inputElement = $(inputSelector);
                if (inputElement.length === 0) {
                    console.error(`${fieldName} input field not found in Quick Edit form`);
                } else {
                    inputElement.val(fieldValue);
                }
            }
        }

        /**
         * Populate a dropdown in the Quick Edit form.
         * @param {Object} rowElement - The jQuery object representing the table row.
         * @param {string} columnSelector - The selector for the column containing the value.
         * @param {string} dropdownSelector - The selector for the dropdown in the Quick Edit form.
         * @param {string} fieldName - The name of the field (for debugging).
         */
        function populateDropdown(rowElement, columnSelector, dropdownSelector, fieldName) {
            var columnElement = rowElement.find(columnSelector);
            if (columnElement.length === 0) {
                console.warn(`${fieldName} column not found for Post ID`);
                return;
            }

            var fieldValue = columnElement.text().trim();
            if (!fieldValue) {
                console.warn(`${fieldName} value not found for Post ID`);
            } else {
                console.log(`${fieldName}:`, fieldValue);
                var dropdown = $(dropdownSelector);
                if (dropdown.length === 0) {
                    console.error(`${fieldName} dropdown not found in Quick Edit form`);
                } else {
                    dropdown.find('option').each(function () {
                        $(this).prop('selected', $(this).text().trim() === fieldValue);
                    });
                }
            }
        }
    });
})(jQuery);