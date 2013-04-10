// This fille is required by default_model.php and Asset Loader module. Do not delete.

$(document).ready(function()
{

    // Function to read cookies.
    function readCookie(name) {
        var nameEQ = escape(name) + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return unescape(c.substring(nameEQ.length, c.length));
        }
        return null;
    }

    // Shot the weight draggers.
    $('.weight-dragger').show();
    $('.weight-dragger').button({ icons: { secondary: "ui-icon-arrowthick-2-n-s" } });

    // Set the table as sortable.
    $('#table1').sortable({
                    handle: '.weight-dragger'
                });

    // Get the csrf cookie.
    var csrfCookie = readCookie('csrf_cookie_name');

    // Set the message box.
    var messageBox = document.getElementById('messages');

    /**
     * Drop handler.
     */
    $('#table1').on('sortstop', function(event, ui)
    {


        var sortableSelects = $('#table1').sortable('toArray');

        // Loop through the sortable array.
        for (var i = 0; i < sortableSelects.length; i++)
        {
            // Get the table rows.
            var row = document.getElementById(sortableSelects[i]);

            // Set the weight variable.
            var weight = i + 1;

            // Get the link id from the Soratable array.
            var idArray = sortableSelects[i].split('-');
            var id = idArray[1]

            // The HTML to replace the weight dragger.
            var weightDragger = '<div id="weight-dragger-' + id  +'" class="weight-dragger">' + (i + 1) +'</div>';

            // The Td cell of the element that is being looped through.
            var rowTd = document.getElementById('td-' + id);

            // Run the weight dragger processing.
            if (!document.getElementById('weight-dragger-' + id))
            {
                // Just add the weight dragger.
                $(rowTd).prepend(weightDragger);
            }
            else
            {
                // Remove existing weight dragger then add.
                $('#weight-dragger-' + id).remove();
                $(rowTd).prepend(weightDragger);
            }

            // Add the styling back to the new weight dragger.
            $('.weight-dragger').button({ icons: { secondary: "ui-icon-arrowthick-2-n-s" } });

            // Get the post url from the hidden form field.
            var postUrl = document.getElementById('menu_link_edit_weight_url').getAttribute('value');

            // Set the global variable before passing to $.post() function.
            var result;

            // Run the $.post() function for each link.
            $.post(postUrl, {csrf_test_name: csrfCookie, submit: 'Submit', id: id, weight: weight}, function(result) {

                // The result actions.
                switch (result)
                {
                    // Put up the error box.
                    case 'invalid':
                        var messageInvalid = '<div class="alert-box secondary">Form did not validate.</div>';
                        $(messageBox).html(messageInvalid);
                        break;
                    // Successful update.
                    case 'true':
                        console.log(result);
                        break;
                    // failed to save to thte database.
                    case 'false':
                        var messageFalse = '<div class="alert-box alert">There was a problem saving the forms.</div>';
                        $(messageBox).html(messageFalse);
                        break;
                }

            });

            // Break the loops is not a 'true' result.
            if (result == 'invalid') break;
            if (result == 'false') break;
        }
    });

});