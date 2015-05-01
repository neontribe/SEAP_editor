<?php
/**
 * @file
 * The main page for editing SEAP content
 *
 */

include("includes/header.php");

//$_SESSION['file'] = $_POST['select_file'];

// The directory where the editable files live.
$dir = 'files';

// Files to exclude.
$exclude = array('.', '..', 'README.md');

$allfiles = scandir($dir);
$files = array_diff($allfiles, $exclude);

function set_selected($file, $session_file) {
  if ($file == $session_file) {
    return ' selected="selected"';
  }
  return ''; 
}

?>
<h1>JSON Content Editor</h1>
<form method="post" action="content.php">
  <select id="edit-this-file" name="select_file">
      <option value="">Please select a file</option>
    <? foreach ($files as $file): ?>
      <option value="<?= $file ?>" <?= set_selected($file, $_SESSION['file']); ?>><?= $file ?></option>
    <? endforeach; ?>
  </select>
  <button type="submit">Edit file</button>
</form>
<div id="content">
  Please select a file to edit.
</div>
<?php include("includes/footer.php"); 
