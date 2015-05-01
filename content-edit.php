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

if (!can_read_file()) { return; }

$content = file_get_contents($file);

if (!file_has_content($content)) { return; }

$content = json_decode($content);

// Make sure we have valid json content
if (!is_valid_json()) { return; };

var_dump($content);

?>

<h1>JSON Item Editor</h1>
<div class="options">
  Go back to <a href="/">choose another question</a> or <a href="loadnewfile.php">edit another file</a>
</div>
<h2><?= $key ?></h2> from section <strong><?= $type ?></strong> in <?= $file ?></h2>

<form method="post">
</form>
<!-- TODO add ajax preview? -->
<?php include("includes/footer.html"); ?>
