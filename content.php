<h1>ESA Content</h1>

<?php
$file = 'files/' . $_POST['selected_file'];;
echo $file;
$content = json_decode(file_get_contents($file));
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

