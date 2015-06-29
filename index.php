<?php
/**
 * @file
 * The main page for editing SEAP content
 *
 */

include('views/includes/header.php');

// The directory where the editable files live.
$dir =  FILES_DIR;

// Files to exclude.
$exclude = array('.', '..', 'README.md');

$allfiles = scandir($dir);
$files = array_diff($allfiles, $exclude);

$session_file = '';
if (isset($_SESSION['file'])) { $session_file = $_SESSION['file']; }

function set_selected($file, $session_file) {
  if ($file == $session_file) {
    return ' checked';
  }
  return ''; 
}

?>
<h1>JSON Content Editor</h1>
<form id="file-select-form" method="post" action="views/content.php">
  <div class="radio-list">
    <? foreach ($files as $file): ?>
    <label>
      <input type="radio" name="select_file" value="<?= $file ?>" <?= set_selected($file, $session_file); ?>>
      <span><?=$file?></span>
    </label>
    <? endforeach; ?>
  </div>
  <button type="submit" class="nav-link">Go to file</button>
</form>
<div id="content">
  Please select a file to edit.
</div>

<!-- If there is a session file go straight to content.php -->
<?php if ($session_file !==''): ?>
  <script type="text/javascript">
    //Our form submission function.
    function submitForm() {
      document.getElementById('file-select-form').submit();
    }
    //Call the function submitForm() as soon as the page has loaded.
    window.onload = submitForm;
  </script>
<?php endif; ?>

<?php include("views/includes/footer.php"); 
