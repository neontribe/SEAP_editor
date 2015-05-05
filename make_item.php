<?php
/**
 * @file
 * Methods for creating form html from json 
 */

/**
 *  Make form elements from JSON by type.
 */
function make_form_element($fieldtype, $fieldname, $fieldvalue) {
  switch($fieldtype) {
    case 'string':
      if (strlen($fieldvalue) > 100) {
        return '<label>' . $fieldname . '</label><textarea name="' . $fieldname . '" value="' . $fieldvalue . '" rows="3" cols="100">' . $fieldvalue . '</textarea>';
      } else {
        return '<label>' . $fieldname . '</label><input name="'. $fieldname .'" type="text" size="' . round(strlen($fieldvalue) * 1.5) . '" value="' . $fieldvalue . '">';
      }
    break;
    case 'integer':
      return '<label>'.$fieldname.'</label><input name="'. $fieldname .'" type="number" value="' . $fieldvalue . '">';
    break;
    case 'boolean':
      return 'I am a boolean';
    break;
    case 'double':
      return 'I am a float';
    break;

    // If an array - treat as set
    case 'array':
      $output = '<fieldset><legend>' . $fieldname . '</legend>';
      foreach ($fieldvalue as $field) {
        $output .= make_form_element(gettype($field), 'name', $field);
      }
      return $output . '</fieldset>';
    break;

    // If object - treat as set of fields.
    case 'object':
      $output = '<fieldset>';
      foreach ($fieldvalue as $fieldname => $value) {
        $output .= make_form_element(gettype($value), $fieldname, $value);
      }
      return $output . '</fieldset>';
    break;

    // If null value return as empty textfield.
    case 'NULL':
      return make_form_element('string', $fieldname, '');
    break;
    case 'resource':
    default:
      return 'No form field available for that type. Please contact developer.';
    break;
  }
}
