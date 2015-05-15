<?php
/**
 * @file save_item.php
 *
 * Save form elements into JSON doc
 */

include('includes/header.php');
require_once('error.php');
// TODO better error handling
if(!$_POST) {
  echo 'Oops no form data submitted';
}

// Get file from session.
session_start();

if (!can_read_file()) { return; }

if (!isset($_SESSION['file'])) { return; }

$filename = $_SESSION['file'];
$file = 'files/' . $filename;

$content = file_get_contents($file);

if (!file_has_content($content)) { return; }

$content = json_decode($content);

// Make sure we have valid json content
if (!is_valid_json()) { return; };

// Process $_POST data to output for correct JSON format
$postjson = array();
foreach($_POST as $key => $value) {
  // if this is not the original key data - add to postjson.    
  if ($key !== 'orig_key') {
    // strip formfield nums for clean json
    $key_arr = explode('_', $key);      
    $key = $key_arr[0];
  
    // nest fieldsets into arrays/ objects
    if (is_object($value) || is_array($value)) {
      $fieldset_values = array();
      foreach ($value as $val) {
        foreach ($val as  $k => $v) {
          $val[$k] = _clean_value($v);
        }
        $postjson[$key][] = $val;
      }
    } else {
      // clean and trim 
      $postjson[$key] = _clean_value($value);
    }
  }
}

// save the values into file.
$content_arr = array();
foreach ($content as $type => $gubbins) {
  $i = 0;
  $content_arr = $content->$type; 
  foreach ($gubbins as $item) {
    $title_key = key($item);
    if ($item->$title_key === $_POST['orig_key']) {
       $content_arr[$i] = $postjson;
    }
    $i++;
  }
  $content->$type = $content_arr;
}

// convert num strings to int using JSON encode option
$json_data = utf8_encode(json_encode($content, JSON_NUMERIC_CHECK));
file_put_contents($file, $json_data);

$msg = 'Item has been saved.';
_error_html($msg, BASE, 'chose another item', $_SERVER['HTTP_REFERER']);

// TODO possible not straight to index... 
//  Edit it again


function _clean_value($value) {
  // remove leading and trailing spaces
  $value = trim($value);
  // set empty string to null
  if ($value === '') {
    $value = null;
  }
  return $value;
}

