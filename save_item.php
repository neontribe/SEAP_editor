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

//foreach($_POST as $key => $value) {
  //TODO save the values into form.
  //TODO success message.
  // TODO possible not straight to index... 
  // your question has been saved as PREVIEW
  //  Edit it again
  //  chose another question
  //  chose another file     

  
  var_dump($_POST);
  // TODO reformat $_POST values to group into arrays byt fieldset
  $contents = json_encode($_POST);
  file_put_contents( 'files/new-question.json', $contents);
  //headder('Location: /');
//}
