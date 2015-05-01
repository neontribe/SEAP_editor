<?php session_start(); ?>
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
<header>
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
</header>
<main>
