<?php session_start(); ?>
<!DOCTYPE html>
<html dir="ltr" lang="en-GB">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link href="../css/style.css" rel="stylesheet" media="all" />
<title>JSON content editor - SEAP</title>
</head>
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
