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

//var_dump($content);
echo '<p>Type: '. $type . '</p>';
echo '<p>Key: ' . $key . '</p>';

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

//var_dump($edit_item);

?>

<h1>JSON Item Editor</h1>
<div class="options">
  Go back to <a href="/">choose another question</a> or <a href="loadnewfile.php">edit another file</a>
</div>
<h2><?= $key ?></h2> from section <strong><?= $type ?></strong> in <?= $file ?></h2>

<form method="post">
</form>
<!-- TODO add ajax preview? -->
<?php include("includes/footer.php"); ?>
