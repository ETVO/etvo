

const UTIL_API_URL = '/etvo-manage/admin/api/use_blocks_util.php';

$('.project').on('click', function () {
  var dir = $(this).data('project-dir');
  var year = $(this).data('project-year');

  $.ajax({
    url: UTIL_API_URL,
    data: {
      function: 'get_data_from_dir',
      args: JSON.stringify([
        dir
      ])
    },
    dataType: "json",
    success: function (info) {
      var images = (() => {
        let images = [];
        let imageKeys = Object.keys(info.images);
        imageKeys.forEach((key) => {
          var image = info.images[key];
          var src = image.src;
          var style = image.style ?? '';

          images.push(`<img src="${src}" style="${style}" />`);
        })

        return images;
      })();

      var description = treatText(info.description);

      var modal = $('#projectModal');

      $(modal).find('.title').html(info.title);
      $(modal).find('.link').attr('href', info.link);
      $(modal).find('.desc').html(description);
      $(modal).find('.tech').html(info.tech);
      $(modal).find('.year').html(year);
      $(modal).find('.images').html(images.join(' '));

      $(modal).fadeIn(200);
    }
  });

});

function treatText(text) {
  var paragraphs = text.split('\r\n\r\n');
  for (var i = 0; i < paragraphs.length; i++) {
    paragraphs[i] = '<p>' + paragraphs[i] + '</p>';
  }
  text = paragraphs.join('');
  
  text = text.replace(/\r\n/g, '<br>');

  var $html = $('<div>').html(text);
  $html.find('a').attr('target', '_blank');

  return $html.html();
}

// Function to close the modal
function closeModal() {
  $('#projectModal').scrollTop(0);
  $('#projectModal').fadeOut(200);
}

// Close modal when clicking the close button
$('#closeModal').on('click', closeModal);

// Close modal when clicking outside the modal
$('#projectModal').on('click', function (e) {
  if (e.target.id === 'projectModal') {
    closeModal();
  }
});

// Close modal when clicking on elements with class 'action' inside the modal
$('#projectModal .action').on('click', closeModal);

// Close modal when the ESC key is pressed
$(document).on('keydown', function (e) {
  if (e.keyCode === 27) {
    closeModal();
  }
});
