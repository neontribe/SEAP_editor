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
      if (strlen($fieldvalue) > 100) {$inputtype = 'textarea';} else { $inputtype = 'text'; }
      return '<label>'.$fieldname.'<label><input type="'.$inputtype.'" size="' . round(strlen($fieldvalue) * 1.5) . '" value="' . $fieldvalue . '">';
    break;
    case 'integer':
	return 'I am an int';
    break;
    case 'boolean':
	return 'I am a boolean';
    break;
    case 'double':
	return 'I am a float';
    break;
    case 'array':
	return 'Array - iterate again';
    break;
    case 'object':
	return 'Object - no support';
     break;
    case 'resource':
    case 'NULL':
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
<div class="options">
  <a href="/" class="nav-link">choose another question</a> or <a href="loadnewfile.php" class="nav-link">edit another file</a>
</div>
<h2><?= $key ?></h2> from section <strong><?= $type ?></strong> in <?= $file ?></h2>

<form method="post">
  <?php foreach($edit_item as $fieldname => $fieldvalue): ?>
  <pre class="debug"><?=var_dump($fieldvalue); ?></pre>
  <?php $fieldtype = gettype($fieldvalue);?>
  <?= make_form_element($fieldtype, $fieldname, $fieldvalue); ?>
  <?php endforeach; ?>
</form>
<!-- TODO add ajax preview? -->
<?php include("includes/footer.php"); ?>
