<?php
/**
 * @file
 * drop session and start again with new file
 *
 */
include('includes/header.php');

$_SESSION['file'] = '';
header('Location: ' . BASE);
