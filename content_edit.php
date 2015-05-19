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
if (!is_valid_item($edit_item, $type)) { die; }

?>

<div class="debug">
  <?= var_dump($edit_item); ?>
</div>

<h1>JSON Item Editor</h1>
editing item from section <strong><?= $type ?></strong> in <strong><?= $file ?></strong>

<div class="options">
<a href="<?=BASE ?>" class="nav-link">choose another item</a> or <a href="<?=BASE ?>load_new_file.php" class="nav-link">edit another file</a>
</div>

<form method="post" action="save_item.php">
<fieldset name="<?= $type ?>"><legend><h2><?= $key ?></h2></legend>
    <?php foreach($edit_item as $fieldname => $fieldvalue): ?>
    <p class="debug"><?=var_dump($fieldvalue); ?></p>
    <?php $fieldtype = gettype($fieldvalue);?>
    <p>
      <?= make_form_element($fieldtype, $fieldname, $fieldvalue); ?>
    </p>
    <?php endforeach; ?>
  </fieldset>
  <input type="hidden" name="orig_key" value="<?= $key ?>" />
  <input type="hidden" name="orig_type" value="<?= $type ?>" />
  <button type="submit">Save <?= $type ?> item</button>
</form>
<!-- TODO add ajax preview? -->
<?php include("includes/footer.php"); ?>
