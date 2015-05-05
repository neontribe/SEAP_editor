<?php session_start();
// Globals
define("BRAND", "SEAP");
?>
<!DOCTYPE html>
<html dir="ltr" lang="en-GB">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link href="../css/style.css" rel="stylesheet" media="all" />
<link href="../css/seap-style.css" rel="stylesheet" media="all" />
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
        <a href="/loadnewfile.php">File chooser</a>
      </li>
      <li>
        <a href="help.php">Help</a>
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
