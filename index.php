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
    return ' checked';
  }
  return ''; 
}

?>
<h1>JSON Content Editor</h1>
<form method="post" action="content.php">
  <div class="radio-list">
    <? foreach ($files as $file): ?>
    <label>
      <input type="radio" name="select_file" value="<?= $file ?>" <?= set_selected($file, $_SESSION['file']); ?>>
      <span><?=$file?></span>
    </label>
    <? endforeach; ?>
  </div>
  <button type="submit" class="nav-link">Go to file</button>
</form>
<div id="content">
  Please select a file to edit.
</div>
<?php include("includes/footer.php"); 
