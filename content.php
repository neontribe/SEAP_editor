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
  <form method="post" action="<?=BASE ?>content_edit.php?type=<?=$type; ?>&key=<?=NEW_ITEM ?>">
    Click to edit item or 
    <button type="submit">Add a new one</button>
  </form>
  <?php $filter_strings = get_allowed_filters($gubbins); ?>
  <?php if($filter_strings): ?> 
    <form method="post" action="<?=BASE ?>content.php">
    <?php foreach($filter_strings as $filter_key => $values): ?>
    <?php $selected = JEditFilter::get_active_filter('filter-' . $type . '-' . $filter_key); ?>
    <span><?=$filter_key; ?></span>
    <select name="filter-<?=$type . '-' . $filter_key; ?>">
        <?php foreach($values as $k => $filter_value): ?>
          <option value="<?=$filter_value; ?>" <?=JEditFilter::is_selected($selected, $filter_value); ?>> <?=$k; ?></option>
        <?php endforeach; ?>
      </select>
    <?php endforeach; ?>
    <button type="submit">filter <?=$type; ?></button>
    </form>
  <?php endif; ?>
  <ul>
  <?php if (JEditFilter::is_filtered_content()): ?>
    <h2><?=JEditFilter::is_filtered_content(); ?></h2>
  <?php endif;?>
  <?php // We can only edit data that has values. ?>
  <?php if (is_array($gubbins) || is_object($gubbins)): ?>
      <?php foreach ($gubbins as $item): ?>
        <?php $title_key = key($item); ?> 
        <?php if(JEditFilter::in_filtered_content($type, $item)): ?>
          <li>
            <a href="<?=BASE ?>content_edit.php?type=<?=$type;?>&key=<?=$item->$title_key?>"><?= $item->$title_key; ?>
            </a>
            <?php if(!JEditFilter::is_filtered_content()): ?>
              <?php foreach(explode('|', FILTER_BY) as $filterby): ?>
                <?php if (isset($item->$filterby)): ?>
                  <span><?= $filterby; ?>: 
                  <?=$item->$filterby; ?></span>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endif; ?>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>
    <?php else: ?>
      <li><?= $gubbins;?></li>
    <?php endif; ?>
  </ul>

<?php endforeach; ?>
<?php include('includes/footer.php'); ?>
