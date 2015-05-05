<?php
/**
 * @file save_item.php
 *
 * Save form elements into JSON doc
 */

require_once('error.php');
// TODO better error handling
if(!$_POST) {
  echo 'Oops no form data submitted';
}

foreach($_POST as $key => $value) {
  //TODO save the values into form.
  //TODO success message.
  // TODO possible not straight to index... 
  // your question has been saved as PREVIEW
  //  Edit it again
  //  chose another question
  //  chose another file     

  $contents = json_encode($_POST);
  file_put_contents( 'files/new-question.json', $contents);
  header('Location: /');
}
