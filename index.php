<?php
/**
 * @file
 * The main page for editing SEAP content
 *
 */

include("includes/header.html");

// The directory where the editable files live.
$dir = 'files';

// Files to exclude.
$exclude = array('.', '..', 'README.md');

$allfiles = scandir($dir);
$files = array_diff($allfiles, $exclude);

// If session already started and has current file. Select as default.
session_start();
$session_file = '';
if (isset($_SESSION) && isset($_SESSION['file'])) {
  $session_file = $_SESSION['file'];
}

function set_selected($file, $session_file) {
  if ($file == $session_file) {
    return ' selected="selected"';
  }
  return ''; 
}

?>
<h1>JSON Content Editor</h1>
<form method="post">
  <select id="edit-this-file" name="select_file">
      <option value="">Please select a file</option>
    <? foreach ($files as $file): ?>
      <option value="<?= $file ?>" <?= set_selected($file, $session_file); ?>><?= $file ?></option>
    <? endforeach; ?>
  </select>
</form>
<div id="content">
  Please select a file to edit.
</div>
<?php include("includes/footer.html"); ?>
