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


$(document).on('click', '#add_player_button', function() {


  // ... retrieve the corresponding form.
  var $form = $(this).closest('form');
  // Simulate form data, but only include the selected sport value.

  var data = {}; 
  var $number_players = $('#game_NumberPlayers');
  var $num_players_current = $number_players.val();
  $number_players.val(parseInt($number_players.val()) + 1);
  var $num_players_next = $number_players.val();

  data[$number_players.attr('name')] = $number_players.val();
  data['game__token'] = $('#game__token').val();
  // Submit data via AJAX to the form's action path.
  $.ajax({
    url : $form.attr('action'),
    type: $form.attr('method'),
    data : data,
    success: function(html) {

     $('#game_Player' + $num_players_current + 'Section')
      .after($(html).find('#game_Player' + $num_players_next + 'Section'))
      .after('<div>Player' + $num_players_next + ' Section</div>');

      if($num_players_next >= 6){
        $('#add_player_button').hide();
      }      

    }




  });
});