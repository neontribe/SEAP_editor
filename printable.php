<?php
include('includes/header.php');
require_once('JEditError.class.php');
require_once('JEditFilter.class.php');

if (!isset($_POST['select_file']) && !isset($_SESSION['file'])) {
  JEditError::errorMsg('Please select a file to edit.', null, '', BASE);
  return;
}
 
$filename = isset($_POST['select_file']) ? $_POST['select_file'] : $_SESSION['file'] ;
$filepath = FILES_DIR . '/' . $filename;
$_SESSION['file'] = $filename;

$content = JEditError::loadFileContent($filepath);
if(!$content) { die; }
 
$title_arr = explode('.', $filename);
?>

<h1><?= $title_arr[0]; ?></h1>
<?php foreach ($content as $type => $gubbins): ?>
  <h2><?= $type ?></h2>
  <?php $grouped_questions = JEditFilter::group_by_key('category', $gubbins); ?>
  <?php foreach ($grouped_questions as $category => $questions): ?>
    <h2><?= $category; ?></h2>
    <ul>
      <?php foreach ($questions as $q): ?>
      <li>
        <?= $q['question']; ?>
        <?php foreach ($q['answers'] as $answer): ?>
        <?= $answer->label; ?>
        <?= $answer->value; ?>
        <?php endforeach; ?>
      </li>
      <?php endforeach; ?>
    </ul>
  <?php endforeach; ?>

<?php endforeach; ?>
<?php include('includes/footer.php'); ?>
