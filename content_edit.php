<?php
/**
 * @file
 * The single item edit form.
 *
 */

include('includes/header.php');
require_once('JEditError.class.php');

// get file path
$filepath = FILES_DIR . $_SESSION['file'];

// get item from params
$type = filter_input(INPUT_GET,"type",FILTER_SANITIZE_STRING);
$key = filter_input(INPUT_GET,"key",FILTER_SANITIZE_STRING);

// load file content and find item
$content = JEditError::loadFileContent($filepath);

if (!$content) { die; }

$items = $content->$type;
$edit_item = '';

require_once('make_item.php');

// if we are creating a new item - provide blank fields using first as tpl.
if ($key === NEW_ITEM) {
  $edit_item = $items[0];
  // Make sure we have an object
  if (!is_object($edit_item)) { return; }
  foreach ($edit_item as $set_label => $field) {
    $edit_item_field = $edit_item->$set_label;
    $newfieldtype = gettype($field);
    if (is_array($edit_item_field) || is_object($edit_item_field)) {
      $edit_item->$set_label = convert_to_empty($newfieldtype, $field);
    } else {
      // provide and empty default value
      $edit_item->$set_label = convert_to_empty($newfieldtype, $field);
    }
  }
} else {
  // Find the item
  foreach ($items as $item) {
    $item_key = key($item);
    if ($item->$item_key === $key) {
      $edit_item = $item;
    }
  }
}

// If we haven't found the item
if (!JEditError::isValidItem($edit_item, $type)) { die; }

?>

<h1>JSON Item Editor</h1>
editing item from section <strong><?= $type ?></strong> in <strong><?= $filepath ?></strong>

<div class="options">
<a href="<?=BASE ?>" class="nav-link">choose another item</a> or <a href="<?=BASE ?>load_new_file.php" class="nav-link">edit another file</a>
</div>

<form method="post" action="save_item.php">
<fieldset name="<?= $type ?>"><legend><h2><?= $key ?></h2></legend>
    <?php foreach($edit_item as $fieldname => $fieldvalue): ?>
    <?php $fieldtype = gettype($fieldvalue);?>
    <p>
      <?= make_form_element($fieldtype, $fieldname, $fieldvalue); ?>
    </p>
    <?php endforeach; ?>
  </fieldset>
  <input type="hidden" name="orig_key" value="<?= $key ?>" />
  <input type="hidden" name="orig_type" value="<?= $type ?>" />
  <button type="submit" name="action" value="save">Save <?= $type ?> item</button>
  <button type="submit" name="action"  value="delete" onClick="return confirm('Are you sure you want to delete?')">Delete <?= $type ?> item</button>
</form>
<!-- TODO add ajax preview? -->
<?php include("includes/footer.php"); ?>
