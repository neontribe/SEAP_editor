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
  // strip formfield nums for clean json
  $key = explode('_', $key)[0];
    
  // nest fieldsets into arrays/ objects
  if (is_object($value) || is_array($value)) {
    $fieldset_values = array();
    echo '<pre>'; print_r($value); echo '</pre>';
    die;
    foreach ($value as $val) {
      $postjson[$key][] = $val;
    }
    $test = array('questions' => array(array('label' => 'hello', 'value' => 'hidid'), array('label'=> 'hi', 'value' => 'hididi')));
    echo '<pre>';
    print_r($test);
    echo '</pre>';
    $json = json_encode($test);
    echo '<pre>JSON******'. $json .'</pre>';;
  } else {

    // clean and trim 
    $postjson[$key] = _clean_value($value);

    //TODO save the values into form.
    //TODO success message.
    // TODO possible not straight to index... 
    // your question has been saved as PREVIEW
    //  Edit it again
  }
}

function _clean_value($value) {
  // remove leading and trailing spaces
  $value = trim($value);
  // set empty string to null
  if ($value === '') {
    $value = null;
  }
  return $value;
}

var_dump($_POST);
print '<p>==================</p>';
var_dump($postjson);
  // convert num strings to int using JSON encode option 
  $contents = utf8_encode(json_encode($postjson, JSON_NUMERIC_CHECK));
  file_put_contents( 'files/new-question.json', $contents);
  //header('Location: /');
//}
