<h1>ESA Content</h1>

<?php
$file = 'files/' . $_POST['selected_file'];

//Enable Error Reporting and Display:
/**
error_reporting(~0);
ini_set('display_errors', 1);

if (ini_get('allow_url_fopen') == 1) {
    echo '<p style="color: #0A0;">fopen is allowed on this host.</p>';
} else {
    echo '<p style="color: #A00;">fopen is not allowed on this host.</p>';
}
**/

$content = file_get_contents($file);
$content = json_decode($content);

// Check for json errors
var_dump(json_last_error());

$questions = $content->questions;
$advice = $content->advice;
?>

<h2>Questions</h2>
<ul>

   <? foreach ($questions as $question): ?>
   <li><?= $question->question; ?></li>
  <? endforeach; ?>

</ul>

<h2>Advice</h2>
<ul>
  <? foreach ($advice as $advice): ?>
    <li><?= $advice->title; ?></li>
  <? endforeach; ?>
</ul>

