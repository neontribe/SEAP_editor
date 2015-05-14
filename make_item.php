<?php
/**
 * @file
 * Methods for creating form html from json 
 */

/**
 *  Make form elements from JSON by type.
 */
function make_form_element($fieldtype, $fieldname, $fieldvalue, $fieldnum = null) {  
  // Append trailing _ to $fieldname for easy remove of unique field names.
  $formfieldname = $fieldname . '_';     
  switch($fieldtype) {
    case 'string':
      if (strlen($fieldvalue) > 100) {
              return '<label>' . $fieldname . '</label><textarea name="' . $formfieldname . $fieldnum . '" value="' . $fieldvalue . '" rows="3" cols="100">' . $fieldvalue . '</textarea>';
      } else {
        return '<label>' . $fieldname . '</label><input name="'. $formfieldname . $fieldnum .'" type="text" size="' . round(strlen($fieldvalue) * 1.5) . '" value="' . $fieldvalue . '">';
      }
    break;
    case 'integer':
      return '<label>'.$fieldname.'</label><input name="'. $formfieldname . $fieldnum .'" type="text" value="' . $fieldvalue . '">';
      // unfortunately SEAP app mixes int with in same field type so we have to use text here      
      //return '<label>'.$fieldname.'</label><input name="'. $formfieldname . $fieldnum .'" type="number" value="' . $fieldvalue . '">';
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
        $output .= make_form_element(gettype($field), $fieldname, $field, $fieldnum);
        $fieldnum++;
      }
      return $output . '</fieldset>';
    break;

    // If object - treat as set of fields.
    case 'object':
      if ($fieldnum === null) { $fieldnum = 0; }
      $output = '<fieldset>';
      foreach ($fieldvalue as $fieldname => $value) {
        $output .= make_form_element(gettype($value), $fieldname, $value, $fieldnum);
        $fieldnum++;
      }
      return $output . '</fieldset>';
    break;

    // If null value return as empty textfield.
    case 'NULL':
      return make_form_element('string', $fieldname, '', $fieldnum);
    break;
    case 'resource':
    default:
      return 'No form field available for that type. Please contact developer.';
    break;
  }
}
