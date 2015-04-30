<h1>ESA Content</h1>

<?php
$file = 'files/' . $_POST['selected_file'];

require_once('error.php');

if (!can_read_file()) { return; }

$content = file_get_contents($file);
$content = json_decode($content);

// Make sure we have valid json content
if (!is_valid_json()) { return; };

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

