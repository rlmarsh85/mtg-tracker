/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';


// start the Stimulus application
import './bootstrap';


$(document).on('change', '#game_NumberPlayers', function() {


  // ... retrieve the corresponding form.
  var $form = $(this).closest('form');
  // Simulate form data, but only include the selected sport value.

  var data = {}; 
  var $number_players = $('#game_NumberPlayers');
  data[$number_players.attr('name')] = $number_players.val();
  data['game__token'] = $('#game__token').val();
  // Submit data via AJAX to the form's action path.
  $.ajax({
    url : $form.attr('action'),
    type: $form.attr('method'),
    data : data,
    success: function(html) {
      $('#players_section').replaceWith(
        $(html).find('#players_section')
      );
    }
  });
});