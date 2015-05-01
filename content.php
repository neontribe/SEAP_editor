<?php
include('includes/header.php');
require_once('error.php');

if (!can_read_file()) { return; }

if (!isset($_POST['select_file'])) { return; }

$filename = $_POST['select_file'];
$file = 'files/' . $filename;
$_SESSION['file'] = $filename;

$content = file_get_contents($file);

if (!file_has_content($content)) { return; }

$content = json_decode($content);

// Make sure we have valid json content
if (!is_valid_json()) { return; };
?>

<h1><?= explode('.', $filename)[0]; ?></h1>

<?php foreach ($content as $type => $gubbins): ?>
  <h2><?= $type ?></h2>
  <form method="post" action="addnew.php">
    <!-- TODO couple of hidden inputs for category and type -->
    <!-- TODO filter by category radios -->
    Click to edit or 
    <button type="submit">Add a new one</button>
  </form>
  <ul>
    <? foreach ($gubbins as $item): ?>
      <?php $title_key = key($item); ?> 
      <li><a href="content-edit.php?type=<?=$type;?>&key=<?=$item->$title_key?>"><?= $item->$title_key; ?></a></li>
    <? endforeach; ?>
  </ul>
<?php endforeach; ?>
<?php include('includes/footer.php'); ?>
