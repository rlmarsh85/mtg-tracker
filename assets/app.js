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


jQuery(document).ready(function() {
  // Get the ul that holds the collection of tags
  var $playersCollectionHolder = $('ul.Players');
  
  // count the current form inputs we have (e.g. 2), use that as the new
  // index when inserting a new item (e.g. 2)
  $playersCollectionHolder.data('index', $playersCollectionHolder.find('input').length);

  $('body').on('click', '.add_item_link', function(e) {
      console.log("CLicking thing");
      var $collectionHolderClass = $(e.currentTarget).data('collectionHolderClass');
      // add a new tag form (see next code block)
      addFormToCollection($collectionHolderClass);
  })
});

function addFormToCollection($collectionHolderClass) {
  console.log("Calling add function");
  console.log($collectionHolderClass)
  // Get the ul that holds the collection of tags
  var $collectionHolder = $('.' + $collectionHolderClass);
  console.log($collectionHolder); 
  // Get the data-prototype explained earlier
  var prototype = $collectionHolder.data('prototype');
  console.log(prototype);
  // get the new index
  var index = $collectionHolder.data('index');
  console.log(index);
  var newForm = prototype;
  // You need this only if you didn't set 'label' => false in your tags field in TaskType
  // Replace '__name__label__' in the prototype's HTML to
  // instead be a number based on how many items we have
  // newForm = newForm.replace(/__name__label__/g, index);

  // Replace '__name__' in the prototype's HTML to
  // instead be a number based on how many items we have
  newForm = newForm.replace(/__name__/g, index);

  // increase the index with one for the next item
  $collectionHolder.data('index', index + 1); 

  // Display the form in the page in an li, before the "Add a tag" link li
  var $newFormLi = $('<li></li>').append(newForm);
  // Add the new form at the end of the list
  $collectionHolder.append($newFormLi)
}