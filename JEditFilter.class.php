<?php
/**
 * Helpers to filter json file content
 *
 */
class JEditFilter {
  /**
   * Get active filter
   */
  public static function get_active_filter($filter_name) {
    $active_filter = isset($_POST[$filter_name]) ? $_POST[$filter_name] : '';
    return $active_filter;
  }

  /**
   * Match selected with post value.
   */
  public static function is_selected($selected, $value) {
    if ($selected === $value) {
      return 'selected';
    } else {
      return '';
    }
  }

  /**
   * Return value if there is an active filter or false if not.
   */
  public static function is_filtered_content() {
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
  public static function in_filtered_content($type, $item) {
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

  public static function group_by_key($key, $content) {
    $grouped_content = array();
    foreach ($content as $c ) {
      $grouped_content[$c->category][] = array(
              'question' => $c->question,
              'answers' => $c->answers,
      );
    }
    uksort($grouped_content, function($a, $b) {
      if (strpos($a, '*') !== false) {
        $a = str_replace('*', '', $a) . 'z';
      }
      if (strpos($b, '*') !== false) {
        $b = str_replace('*', '', $b) . 'z';
      }
      return strcasecmp($a, $b);
    });
    return $grouped_content;
  }
}

