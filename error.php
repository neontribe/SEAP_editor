<?php

/**
 * Check if php allow_url_fopen on
 */
function can_read_file() {
  //Enable Error Reporting and Display:
  error_reporting(~0);
  ini_set('display_errors', 1);

  if (ini_get('allow_url_fopen') == 0) {
    echo _error_html('Error with php setup: fopen is not allowed on this host', '/', 'Go Back');
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
    echo _error_html('No file selected or file has no content', '/', 'Go Back');
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
    echo _error_html($msg);
    $msg = 'Please check that your document is correctly formated JSON. This might help: http://jsonlint.com';
    echo _error_html($msg);
    echo _error_html('Try another file?', '/', 'Go Back');
    $_SESSION['file'] = '';
    return false;
  }
  // No errors all good.
  return true;
}

/**
 * Create formatted error message with link to index.
 */
function _error_html($msg, $link=null, $linktext='') {
  $html  = '<p class="error">' . $msg . '</p>';
  if ($link) {
    $html .= '<p class="error"><a href="' . $link . '">'. $linktext . '</a></p>';
  }
  return $html;
}
