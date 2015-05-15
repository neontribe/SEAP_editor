<?php session_start();
// Globals
define("BRAND", "SEAP");
define("BASE", base_url());

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
?>
<!DOCTYPE html>
<html dir="ltr" lang="en-GB">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link href="<?=BASE ?>css/style.css" rel="stylesheet" media="all" />
<link href="<?=BASE ?>css/seap-style.css" rel="stylesheet" media="all" />
<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400italic,700,300" rel="stylesheet" type="text/css" media="none" onload="if(media!='all')media='all'">
<title>JSON content editor - SEAP</title>
</head>

<header role="banner">
  <span aria-label="<?=BRAND ?> Editor Logo" role="img">
    <?=BRAND ?> Editor
  </span>
  <nav class="to-main-menu" role="navigation">
    <ul>
      <li>
      <a href="<?=BASE ?>loadnewfile.php">File chooser</a>
      </li>
      <li>
      <a href="<?=BASE ?>help.php">Help</a>
      </li>
    </ul>
  </nav>
</header>
<div class="page">
<div class="messages"><?php if($_SESSION && isset($_SESSION['messages']) && $_SESSION['messages']): ?>
    <?php foreach ($_SESSION['messages'] as $msg): ?>
      <?= $msg ?>
    <?php endforeach; ?>
    <?php // Seen these - delete ?>
    <?php $_SESSION['messages'] = ''; ?>
  <?php endif;?></div>
<div class="debug">
<pre>
SESSION
----------
<?= var_dump($_SESSION); ?>
</pre>

<pre>
POST
----
<?= var_dump($_POST);?>
</pre>
</div>
<main>
