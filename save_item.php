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
  //remove leading and trailing spaces      
  $_POST[$key] = trim($value);
  //set empty string to null 
  if (trim($value) === '') {
    $_POST[$key] = null;
    echo $value . 'null';
  }

  //TODO save the values into form.
  //TODO success message.
  // TODO possible not straight to index... 
  // your question has been saved as PREVIEW
  //  Edit it again
  //  chose another question
  //  chose another file     
}
  
  var_dump($_POST);
  // convert num strings to int using JSON encode option 
  $contents = utf8_encode(json_encode($_POST, JSON_NUMERIC_CHECK));
  file_put_contents( 'files/new-question.json', $contents);
  //header('Location: /');
//}
