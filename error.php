<?php
/**
 * Check if php allow_url_fopen on
 */
function can_read_file() {
  //Enable Error Reporting and Display:
  error_reporting(~0);
  ini_set('display_errors', 1);

  if (ini_get('allow_url_fopen') == 0) {
    _error_html('Error with php setup: fopen is not allowed on this host', BASE, 'Go Back');
    $_SESSION['file'] = '';
    return false;
  }
  // No errors all good.
  return true;
}

/**
 * Check if the file is writeable.
 */
function can_write_file($filename) {
  $filepath = 'files/' . $filename;      
  if (!file_exists($filepath)) {
    _error_html($filepath . ' not found.', BASE, 'Choose another file');
    $_SESSION['file'] = '';
    return false;
  }      
  if (!is_writeable($filepath)) {
    _error_html('File not writeable', BASE, 'Choose another file');
    $_SESSION['file'] = '';
    return false;
  }
  // No errors all good.
  return true;
}

/**
 *  Check if we have a file and it contains stuff.
 */
function file_has_content($content) {
  if ($content === '') {
    _error_html('No file selected or file has no content', BASE, 'Go Back');
    $_SESSION['file'] = '';
    return false;
  }
  return true;
}

/**
 *  Check for json errors.
 */
function is_valid_json() {
  if (json_last_error()) { 
    $json_errors = array(
      JSON_ERROR_NONE => 'No error has occurred',
      JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
      JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
      JSON_ERROR_SYNTAX => 'Syntax error', 
    );
    $msg = 'Error reading JSON: ' . ($json_errors[json_last_error()]);
    _error_html($msg);
    $msg = 'Please check that your document is correctly formated JSON. This might help: http://jsonlint.com';
    _error_html($msg);
    _error_html('Try another file?', BASE, 'Go Back');
    $_SESSION['file'] = '';
    return false;
  }
  // No errors all good.
  return true;
}

function is_valid_item($item, $type) { 
  if (!is_object($item)) {
    $msg = 'Sorry your selection was not found in the ' . $type . ' section of ' . $_SESSION['file'] . '. ';
    _error_html($msg, BASE, 'Please go back and choose another', BASE . 'content.php');
    return false;
  }     
  // There is an item... might need another check to see if valid JSON obj
  // ... but for now
  return true;
}

/**
 * Create formatted error message with link to index.
 */
function _error_html($msg, $link=null, $linktext='', $redirect=null, $success=false) {
  $class = 'error';      
  if ($success) { $class = 'success'; }      
  $html  = '<p class="' . $class . '">' . $msg . '</p>';
  if ($link) {
    $html .= '<p class="'. $class .'"><a href="' . $link . '" class="nav-link">'. $linktext . '</a></p>';
  }
  $_SESSION['messages'][] = $html;
  if (!$redirect) {
    header('Refresh:1');
  } else {
    header('Location: ' . $redirect);
  }
}
