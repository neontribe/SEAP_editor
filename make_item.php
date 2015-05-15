<?php
/**
 * @file
 * Methods for creating form html from json 
 */

/**
 *  Make form elements from JSON by type.
 */
function make_form_element($fieldtype, $fieldname, $fieldvalue) {  
  // Append _randomnum to $fieldname for unique field names.
  $formfieldname = $fieldname . '_' . mt_rand(100000000, 999999999);
  // In case of obj or array, disgard array notation from name for label
  $fieldname_arr = explode('[', $fieldname);
  if (sizeof($fieldname_arr > 2)) {
    $formfieldlabel = trim(end($fieldname_arr),']');
  } else {
    $formfieldlabel = $fieldname_arr[0];
  }
  switch($fieldtype) {
    case 'string':
      if (strlen($fieldvalue) > 100) {
              return '<label>' . $formfieldlabel . '</label><textarea name="' . $formfieldname . '" value="' . $fieldvalue . '" rows="3" cols="100">' . $fieldvalue . '</textarea>';
      } else {
        return '<label>' . $formfieldlabel . '</label><input name="'. $formfieldname .'" type="text" size="' . round(strlen($fieldvalue) * 1.5) . '" value="' . $fieldvalue . '">';
      }
    break;
    case 'integer':
      return '<label>' . $formfieldlabel . '</label><input name="'. $formfieldname . '" type="text" value="' . $fieldvalue . '">';
      // unfortunately SEAP app mixes int with in same field type so we have to use text here      
      //return '<label>'. $formfieldlabel . '</label><input name="'. $formfieldname . '" type="number" value="' . $fieldvalue . '">';
    break;
    case 'boolean':
      return 'I am a boolean';
    break;
    case 'double':
      return 'I am a float';
    break;

    // If an array - treat as set
    case 'array':
      $output = '<fieldset name="' . $fieldname . '_arr"><legend>' . $fieldname . '</legend>';
      foreach ($fieldvalue as $field) {
        $output .= make_form_element(gettype($field), $fieldname, $field);
      }
      return $output . '</fieldset>';
    break;

    // If object - treat as set of fields.
    case 'object':
      $output = '<fieldset name="' . $fieldname . '_obj">';
      // TODO this should really be incremental value - what scope to store it in?
      $i = mt_rand(100, 999);
      $value_arr = get_object_vars($fieldvalue);
      foreach ($value_arr as $key => $value) {
        $fieldsetfieldname = $fieldname . '['.$i.'][' . $key . ']';  
        $output .= make_form_element(gettype($value), $fieldsetfieldname, $value);
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
