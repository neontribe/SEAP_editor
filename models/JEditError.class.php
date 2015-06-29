<?php

/**
 *  Error handling
 */

class JEditError {

  /**
   * Check we can access files and have loaded a file with valid content.
   *
   * @param string $filepath
   * Path to the file we're trying to access
   *
   * @return Object $content or null
   * Returns json decoded in to php object or null if not valid
   */
  public static function loadFileContent($filepath) {
    if (!self::_canReadFiles()) { return null; }
    if (!self::_canWriteFile($filepath)) { return null; }
    $content = file_get_contents($filepath);
    if (!self::_fileHasContent($content)) { return null; }
    $content = json_decode($content);
    if(!self::_isValidJson()){ return null; }
  
    return $content;
  }

 
  /**
   * Create formatted error message with link to index.
   *
   * @param string $msg 
   *    The message to print.
   * @param string $link 
   *    The url for a link in the message.
   * @param string $linktext
   *    The text for the link in the message.
   * @param string $redirect
   *    A url to redirect to automatically.
   * @param boolean $success
   *    Is this really an error or is it a success message?
   */
  public static function errorMsg($msg, $link=null, $linktext='', $redirect=null, $success=false) {
    $class = 'error';      
    if ($success) { $class = 'success'; }      
    $html  = '<p class="' . $class . '">' . $msg . '</p>';
    if ($link) {
      $html .= '<p class="'. $class .'"><a href="' . $link . '" class="nav-link">'. $linktext . '</a></p>';
    }
    $_SESSION['messages'][] = $html;
    if (!$redirect) {
      header('Refresh:3');
      die;
    } else {
      header('Location: ' . $redirect);
      die;
    }
    return;
  }
       
  /**
   * Check if php allow_url_fopen on
   */
  private static function _canReadFiles() {
    //Enable Error Reporting and Display:
    error_reporting(~0);
    ini_set('display_errors', 1);

    if (ini_get('allow_url_fopen') == 0) {
      JEditError::errorMsg('Error with php setup: fopen is not allowed on this host', BASE, 'Go Back');
      $_SESSION['file'] = '';
      return false;
    }
    // No errors all good.
    return true;
  }

  /**
   * Check if the file is writeable.
   */
  private static function _canWriteFile($filepath) {
    if (!file_exists($filepath)) {
      JEditError::errorMsg($filepath . ' not found.', BASE, 'Choose another file');
      $_SESSION['file'] = '';
      return false;
    }      
    if (!is_writeable($filepath)) {
      JEditError::errorMsg('File not writeable', BASE, 'Choose another file');
      $_SESSION['file'] = '';
      return false;
    }
    // No errors all good.
    return true;
  }

  /**
   *  Check if we have a file and it contains stuff.
   */
  private static function _fileHasContent($content) {
    if ($content === '') {
      JEditError::errorMsg('No file selected or file has no content', BASE, 'Go Back');
      $_SESSION['file'] = '';
      return false;
    }
    return true;
  }

  /**
   *  Check for json errors.
   */
  private static function _isValidJson() {
    if (json_last_error()) { 
      $json_errors = array(
        JSON_ERROR_NONE => 'No error has occurred',
        JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
        JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
        JSON_ERROR_SYNTAX => 'Syntax error', 
      );
      $msg = 'Error reading JSON: ' . ($json_errors[json_last_error()]);
      $msg .= 'Please check that your document is correctly formated JSON. This might help: http://jsonlint.com';
      $msg .= 'Try another file?';
      JEditError::errorMsg($msg, BASE . 'load_new_file.php', 'Go Back');
      $_SESSION['file'] = '';
      return false;
    }
    // No errors all good.
    return true;
  } 

  // TODO make private and make getItemFromContent function instead.
  public static function isValidItem($item, $type) { 
    if (!is_object($item)) {
      $msg = 'Sorry your selection was not found in the ' . $type . ' section of ' . $_SESSION['file'] . '. ';
      JEditError::errorMsg($msg, BASE, 'Please go back and choose another', BASE . 'content.php');
      return false;
    }     
    // There is an item... might need another check to see if valid JSON obj
    // ... but for now
    return true;
  }
}
