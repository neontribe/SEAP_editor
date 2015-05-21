<?php
/**
 * @file save_item.php
 *
 * Save form elements into JSON doc
 */

include('includes/header.php');
require_once('JEditError.class.php');

if(!$_POST) {
 $msg = 'Oops no form data submitted';
 JEditError::errorMsg($msg);
}

if (!isset($_SESSION['file'])) { return; }

$filename = $_SESSION['file'];
$filepath = FILES_DIR . $filename;

$content = JEditError::loadFileContent($filepath);
if(!$content) { die; }

// Possible actions
$action = '';

if ($_POST['action'] === 'save') { $action = 'save'; }
if ($_POST['action'] === 'delete') { $action = 'delete'; }

if (!$action) { return; }

// Process $_POST data to output for correct JSON format
$postjson = array();
foreach($_POST as $key => $value) {
  // if this is not the action button, hidden original key or type data - add to postjson.    
  if ($key !== 'action' && $key !== 'orig_key' && $key !== 'orig_type') {
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
          $count++;
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
      JEditError::errorMsg('Please enter a ' . $title_key . ' value.', null, '', $_SERVER['HTTP_REFERER']); die;
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
      if ($action === 'save') {
        $content_arr[$i] = $postjson;
      }
      if ($action === 'delete' ) {
        unset($content_arr[$i]);
        $content_arr = array_values($content_arr);
      }  
    }
    $i++;
  }
  // If this is a new item or a changed key...
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
file_put_contents($filepath, $json_data);

if($action === 'delete') {
  $msg = 'Item has been deleted'; 
  JEditError::errorMsg($msg, null, '', BASE . 'content.php', true);
}

if($action === 'save') {
  $msg = 'Item has been saved.';

  if (!empty($new_item[$new_item_type]) || !empty($new_key[$new_item_type])) {
    // On successful save set orig_key to new title
    $_POST['orig_key'] = $new_item_key;
    $url = BASE . 'content_edit.php?type=' . $new_item_type . '&key=' .$new_item_key;
    JEditError::errorMsg($msg, null, '', $url, true);
  } else {
    JEditError::errorMsg($msg, null, '', $_SERVER['HTTP_REFERER'], true);
  }
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

