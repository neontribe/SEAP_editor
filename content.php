<?php
include('includes/header.php');
require_once('JEditError.class.php');

if (!isset($_POST['select_file']) && !isset($_SESSION['file'])) { 
  JEditError::errorMsg('Please select a file to edit.', null, '', BASE);
  return;
}
 
$filename = isset($_POST['select_file']) ? $_POST['select_file'] : $_SESSION['file'] ;
$filepath = FILES_DIR . $filename;
$_SESSION['file'] = $filename;

$content = JEditError::loadFileContent($filepath);
if(!$content) { die; }
 
$title_arr = explode('.', $filename);

/**
 * Get active filter
 */
function get_active_filter($filter_name) {
  $active_filter = isset($_POST[$filter_name]) ? $_POST[$filter_name] : '';
  return $active_filter;
}

/**
 * Match selected with post value.
 */
function is_selected($selected, $value) {
  if ($selected === $value) { 
    return 'selected'; 
  } else {
    return '';
  }
}

/**
 * Return value if there is an active filter or false if not.
 */
function is_filtered_content() {
  if (!$_POST){ return false; }
  foreach ($_POST as $post_key => $post_value) {
    $key_arr = explode('-', $post_key);
    // If there has been a non-empty filter value submitted.
    if ($key_arr[0] === 'filter' && $post_value) { return $post_value; }
  }      
  return false;
}

/**
 * Show only items in filtered content.
 */
function in_filtered_content($type, $item) {
  $key_arr = array();
  $filter_value = array();
  $match = false;
  if (!$_POST) { return true; }
  // Get item type and filter key from post key
  foreach($_POST as $post_key => $post_value) {            
    $key_arr[] = explode('-', $post_key);
    $filter_value[] = $post_value;
  }
  // Remove empty from filter_value array
  $filter_value = array_filter($filter_value);
  // Check if the item contains the selected filter value
  foreach($key_arr as $selected_filter_keys) {
    if ($selected_filter_keys[0] === 'filter') {
      $filter_type = $selected_filter_keys[1];
      $filter_key = $selected_filter_keys[2];
    } else {
      // Stop -no filter selected
      return true;
    }
    // Content is the filtered type and has the selected key
    if ($filter_type === $type && property_exists($item, $filter_key)) { 
      if (in_array($item->$filter_key, $filter_value)) {
        $match = true;
      } 
    } else {
     $match = false;
    }
  // If all selected
  if (!$filter_value) { $match = true; }
  }
  return $match;
}
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
    <?php $selected = get_active_filter('filter-' . $type . '-' . $filter_key); ?>
    <span><?=$filter_key; ?></span>
    <select name="filter-<?=$type . '-' . $filter_key; ?>">
        <?php foreach($values as $k => $filter_value): ?>
          <option value="<?=$filter_value; ?>" <?=is_selected($selected, $filter_value); ?>> <?=$k; ?></option>
        <?php endforeach; ?> 
      </select>
    <?php endforeach; ?>
    <button type="submit">filter <?=$type; ?></button>
    </form>
  <?php endif; ?>
  <ul>
  <?php if (is_filtered_content()): ?>
    <h2><?=is_filtered_content(); ?></h2>
  <?php endif;?>
  <?php // We can only edit data that has values. ?>
  <?php if (is_array($gubbins) || is_object($gubbins)): ?>
      <?php foreach ($gubbins as $item): ?>
        <?php $title_key = key($item); ?> 
        <?php if(in_filtered_content($type, $item)): ?>
          <li>
            <a href="<?=BASE ?>content_edit.php?type=<?=$type;?>&key=<?=$item->$title_key?>"><?= $item->$title_key; ?>
            </a>
            <?php if(!is_filtered_content()): ?>
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
