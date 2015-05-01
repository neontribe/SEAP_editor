<?php
/**
 * @file
 * The single item edit form.
 *
 */

include("includes/header.php");

// get file from session?
$file = 'my file name';
// get item from params
$type = filter_input(INPUT_GET,"type",FILTER_SANITIZE_STRING);
$key = filter_input(INPUT_GET,"key",FILTER_SANITIZE_STRING);
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
