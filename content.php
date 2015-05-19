<?php
include('includes/header.php');
require_once('error.php');

if (!can_read_file()) { return; }

if (!isset($_POST['select_file']) && !isset($_SESSION['file'])) { 
  _error_html('Please select a file to edit.', null, '', BASE);
  return;
}
 
$filename = isset($_POST['select_file']) ? $_POST['select_file'] : $_SESSION['file'] ;
$file = 'files/' . $filename;
$_SESSION['file'] = $filename;

if (!can_write_file($filename)) { return; }

$content = file_get_contents($file);

//if (!file_has_content($content)) { return; }

$content = json_decode($content);

// Make sure we have valid json content
if (!is_valid_json()) { return; }
 
$title_arr = explode('.', $filename);
?>

<h1><?= $title_arr[0]; ?></h1>
<?php foreach ($content as $type => $gubbins): ?>
  <h2><?= $type ?></h2>
  <form method="post" action="<?=BASE ?>content_edit.php?type=<?=$type; ?>&key=<?=NEW_ITEM ?>">
    Click to edit item or 
    <button type="submit">Add a new one</button>
  </form>
  <?php $filter_strings = get_allowed_filters($gubbins); ?>
  <?php if($filter_strings): ?> 
    <form method="post" action="<?=BASE ?>content.php?filter=hello">
    <?php foreach($filter_strings as $filter_key => $values): ?>
    <span><?=$filter_key; ?></span>
      <select>
        <?php foreach($values as $k => $filter_value): ?>
          <option value="<?=$filter_value; ?>"><?=$k; ?></option>
        <?php endforeach; ?> 
      </select>
    <?php endforeach; ?>
    <button type="submit">filter <?=$type; ?></button>
    </form>
  <?php endif; ?>
  <ul>
  <?php // We can only edit data that has values. ?>
  <?php if (is_array($gubbins) || is_object($gubbins)): ?>
      <?php foreach ($gubbins as $item): ?>
        <?php $title_key = key($item); ?> 
        <li>
          <a href="<?=BASE ?>content_edit.php?type=<?=$type;?>&key=<?=$item->$title_key?>"><?= $item->$title_key; ?>
          </a>
          <?php foreach(explode('|', FILTER_BY) as $filterby): ?>
            <?php if (isset($item->$filterby)): ?>
              <span><?= $filterby; ?>: 
              <?=$item->$filterby; ?></span>
            <?php endif; ?>
          <?php endforeach; ?>
        </li>
      <?php endforeach; ?>
    <?php else: ?>
      <li><?= $gubbins;?></li>
    <?php endif; ?>
  </ul>
  
<?php endforeach; ?>
<?php include('includes/footer.php'); ?>
