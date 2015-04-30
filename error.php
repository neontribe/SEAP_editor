<?php

/**
 * Check if php allow_url_fopen on
 */
function can_read_file() {
  //Enable Error Reporting and Display:
  error_reporting(~0);
  ini_set('display_errors', 1);

  if (ini_get('allow_url_fopen') == 0) {
    echo '<p class="error">Error with php setup: fopen is not allowed on this host.</p>';
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
    echo '<p class="error">No file selected or file has no content</p>';
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
    echo '<p class="error">Error reading JSON: ' . ($json_errors[json_last_error()]) . '</p>';
    echo '<p class="error">Please check that your document is correctly formated JSON. This might help: http://jsonlint.com</p>';
    return false;
  }
  // No errors all good.
  return true;
}
