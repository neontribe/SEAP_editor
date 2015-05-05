<?php
/**
 * @file
 * The single item edit form.
 *
 */

include("includes/header.php");

// get file path
$file = 'files/' . $_SESSION['file'];

// get item from params
$type = filter_input(INPUT_GET,"type",FILTER_SANITIZE_STRING);
$key = filter_input(INPUT_GET,"key",FILTER_SANITIZE_STRING);

// get question from file
require_once('error.php');

if (!can_read_file()) { die; }

$content = file_get_contents($file);

if (!file_has_content($content)) { die; }

$content = json_decode($content);

// Make sure we have valid json content
if (!is_valid_json()) { die; }

$items = $content->$type;
$edit_item = '';

foreach ($items as $item) {
   $item_key = key($item);  
   if ($item->$item_key === $key) {
   $edit_item = $item;
 }
}

// If we haven't found the item
if (!is_valid_item($edit_item, $type)) { die; }

/**
 *  Make form elements from type
 */
function make_form_element($fieldtype, $fieldname, $fieldvalue) {
  switch($fieldtype) {
    case 'string':
      if (strlen($fieldvalue) > 100) {
        return '<label>' . $fieldname . '</label><textarea name="' . $fieldname . '" value="' . $fieldvalue . '" rows="3" cols="100">' . $fieldvalue . '</textarea>';
      } else {
        return '<label>' . $fieldname . '</label><input type="text" size="' . round(strlen($fieldvalue) * 1.5) . '" value="' . $fieldvalue . '">';
      }
    break;
    case 'integer':
	    return '<label>'.$fieldname.'</label><input type="number" value="' . $fieldvalue . '">';
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

?>

<div class="debug">
  <?= var_dump($edit_item); ?>
</div>

<h1>JSON Item Editor</h1>
editing item from section <strong><?= $type ?></strong> in <strong><?= $file ?></strong>

<div class="options">
  <a href="/" class="nav-link">choose another question</a> or <a href="loadnewfile.php" class="nav-link">edit another file</a>
</div>

<form method="post">
<fieldset><legend><h2><?= $key ?></h2></legend>
    <?php foreach($edit_item as $fieldname => $fieldvalue): ?>
    <p class="debug"><?=var_dump($fieldvalue); ?></p>
    <?php $fieldtype = gettype($fieldvalue);?>
    <p>
      <?= make_form_element($fieldtype, $fieldname, $fieldvalue); ?>
    </p>
    <?php endforeach; ?>
  </fieldset>
</form>
<!-- TODO add ajax preview? -->
<?php include("includes/footer.php"); ?>
