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

<div class="print-button no-print"><button onclick="window.print()">Print</button></div>
NOTE: Answers which have question text in them rather than a point value trigger followup questions. The followup questions can be found at the end of that activity section, with an * proceeding the activity name.
<div class="question-wrapper">
<h1 class="hide"><?= $title_arr[0]; ?></h1>
<?php foreach ($content as $type => $gubbins): ?>
  <h2 class="hide"><?= $type ?></h2>
  <?php $grouped_questions = JEditFilter::group_by_key('category', $gubbins); ?>
  <?php foreach ($grouped_questions as $category => $questions): ?>
    <h2><?= $category; ?></h2>

    <ul class="questions">
      <?php foreach ($questions as $q): ?>
        <li>
          <div class="the-question">
            <?= $q['question']; ?>
          </div>
          <div class="the-answers">
            <?php foreach ($q['answers'] as $answer): ?>
              <div class="the-label">
                <?= $answer->label; ?> : <span class="the-value"><?= $answer->value; ?></span>
              </div>
              <?php endforeach; ?>
          </div>
        </li>
        <?php endforeach; ?>
    </ul>

  <?php endforeach; ?>

<?php endforeach; ?>
</div>
<?php include('includes/footer.php'); ?>
