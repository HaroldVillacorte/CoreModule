$(document).ready(function() {

  // Show button on Edit and Add pages.
  $('#ajax-back-button').show();

  function updatePage(html) {
    $('#ajax-content').html(html);
  }
  function showBusy() {
    $('#loading-img').show();
  }
  $('.pagination > li a').live('click', function(eve) {
    eve.preventDefault();
    var link = $(this).attr('href');
    $.ajax({
      url: link,
      type: 'GET',
      dataType: 'html',
      beforeSend: function() {
        showBusy();
      },
      success: function(html) {
        updatePage(html);
      }
    });
  });
});


