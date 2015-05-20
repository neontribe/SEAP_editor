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
if (!can_read_file()) { return; }

if (!isset($_SESSION['file'])) { return; }

$filename = $_SESSION['file'];
$file = 'files/' . $filename;

if (!can_write_file($filename)) { return; }

$content = file_get_contents($file);

if (!file_has_content($content)) { return; }

$content = json_decode($content);

// Make sure we have valid json content
if (!is_valid_json()) { return; };

// Process $_POST data to output for correct JSON format
$postjson = array();
foreach($_POST as $key => $value) {
  // if this is not the hidden original key or type data - add to postjson.    
  if ($key !== 'orig_key' && $key !== 'orig_type') {
    // strip formfield nums for clean json
    $key_arr = explode('_', $key);      
    $key = $key_arr[0];
  
    // nest fieldsets into arrays/ objects
    if (is_object($value) || is_array($value)) {
      $count = 0;    
      foreach ($value as $val) {
        foreach ($val as  $k => $v) {
          $val[$k] = _clean_value($v);
        }
        //The set is not empty, or it is the only one save it.
        if (_has_values($val)) {
          $postjson[$key][] = $val;
          $count++;
        }
        if ($count < 1) {
          // make sure we keep at least one.
          $postjson[$key][] = $val;
        }
      } 
    } else {
      // clean and trim 
      $postjson[$key] = _clean_value($value);
    }
  }
}
// save the values into file.
$content_arr = array();
$new_item = array(); 
$new_key = array();

foreach ($content as $type => $gubbins) {
  $i = 0;
  $content_arr = $gubbins;
  if ($_POST['orig_key'] === NEW_ITEM && $_POST['orig_type'] === $type) {
    $new_item[$type] = true;
    $new_item_type = $type;
  }
  foreach ($gubbins as $item) {
    $title_key = key($item);
    // Posted item is this type, and has no title key value
    if (array_key_exists($title_key, $postjson) 
         && empty($postjson[$title_key])) {
      _error_html('Please enter a ' . $title_key . ' value.', null, '', $_SERVER['HTTP_REFERER']); die;
    }
    if ($item->$title_key === $_POST['orig_key']) {
      // if the key field has changed, set flag and type for redirect params later
      $new_item_type = $type;      
      if ($postjson[$title_key] !== $_POST['orig_key']) {
        $new_key[$type] = true;
      }
      else {
        $new_key[$type] = false;
      }
      $content_arr[$i] = $postjson;
    }
    $i++;
  }
  if (!empty($new_item[$type]) || !empty($new_key[$type])) {
    if (!empty($new_item[$type])) {      
      $content_arr[$i] = $postjson;
    }
    // Keep the title for redirect param after save
    $new_item_title_key = key($postjson);
    $new_item_key = $postjson[$new_item_title_key];
  }
  $content->$type = $content_arr;
}

// convert num strings to int using JSON encode option
$json_data = utf8_encode(json_encode($content, JSON_NUMERIC_CHECK));
file_put_contents($file, $json_data);

$msg = 'Item has been saved.';
if (!empty($new_item[$new_item_type]) || !empty($new_key[$new_item_type])) {
   // On successful save set orig_key to new title
   $_POST['orig_key'] = $new_item_key;
   $url = BASE . 'content_edit.php?type=' . $new_item_type . '&key=' .$new_item_key;
  _error_html($msg, null, '', $url, true);
} else {
  _error_html($msg, null, '', $_SERVER['HTTP_REFERER'], true);
}

/**
 *  trim and set empty string to null
 */
function _clean_value($value) {
  // remove leading and trailing spaces
  $value = trim($value);
  // set empty string to null
  if ($value === '') {
    $value = null;
  }
  return $value;
}

/**
 * Check for content in arrays/ objs
 */
function _has_values($val) {
  foreach ($val as $content) {
    if ($content) {      
      return true;
    }
  }
  return false;
}

