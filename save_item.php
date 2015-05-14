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

$postjson = array();

foreach($_POST as $key => $value) {
  // nest fieldsets into arrays/ objects
  // strip formfield nums for clean json
  $key = explode('_', $key)[0];
     
  //remove leading and trailing spaces      
  $postjson[$key] = trim($value);
  //set empty string to null 
  if (trim($value) === '') {
    $postjson[$key] = null;
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
print '<p>==================</p>';
var_dump($postjson);
  // convert num strings to int using JSON encode option 
  $contents = utf8_encode(json_encode($postjson, JSON_NUMERIC_CHECK));
  file_put_contents( 'files/new-question.json', $contents);
  //header('Location: /');
//}
