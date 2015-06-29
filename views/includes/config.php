<?php
// Globals
define("BRAND", "SEAP");
define("BASE", base_url());
define("NEW_ITEM", "UNTITLED New item");
define("FILTER_BY", implode('|', array('category')));
define("FILES_DIR", 'files');


/**
 *  Get base url.
 */
function base_url($atRoot=FALSE, $atCore=FALSE, $parse=FALSE){
  if (isset($_SERVER['HTTP_HOST'])) {
    $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
    $hostname = $_SERVER['HTTP_HOST'];
    $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
    $core = $core[0];
    $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
    $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
    $base_url = sprintf( $tmplt, $http, $hostname, $end );
  } else {
  $base_url = 'http://localhost/';
  }
  if ($parse) {
    $base_url = parse_url($base_url);
    if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
  }
  return $base_url;
}

/**
 * Get filters allowed from config and return as array.
 */
function get_allowed_filters($items) {
  $allowed_filters = array();
  $filter_keys = explode('|', FILTER_BY);      
  foreach ($filter_keys as $filter_key) {
    $allowed_filters[$filter_key]['--All--']='';
    foreach ($items as $item) {
      if(isset($item->$filter_key)) {
        // only get unique values
        $allowed_filters[$filter_key][$item->$filter_key] = $item->$filter_key;
      }
    }
  }
  return $allowed_filters;
}

