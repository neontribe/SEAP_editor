<?php
namespace SEAP_editor;

/**
 *  Item
 */

class JEditItem {

  /**
   * Trim and set empty string to null.
   *
   * @param string $value
   *
   * @return string or null $value
   */
  public static function cleanValue($value) {
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
  public static function hasValues($val) {
    foreach ($val as $content) {
      if ($content) {
        return true;
      }
    }
    return false;
  }
}
