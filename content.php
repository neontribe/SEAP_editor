<?php
$file = 'files/' . $_POST['selected_file'];

require_once('error.php');

if (!can_read_file()) { return; }

$content = file_get_contents($file);

if (!file_has_content($content)) { return; }

$content = json_decode($content);

// Make sure we have valid json content
if (!is_valid_json()) { return; };
?>

<h1><?= explode('.', $_POST['selected_file'])[0]; ?></h1>

<?php foreach ($content as $category => $gubbins): ?>
  <h2><?= $category ?></h2>
  <ul>
    <? foreach ($gubbins as $item): ?>
      <?php $title_key = key($item); ?> 
      <li><?= $item->$title_key; ?></li>
    <? endforeach; ?>
  </ul>
<?php endforeach; ?>
