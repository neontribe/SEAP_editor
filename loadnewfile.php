<?php
/**
 * @file
 * drop session and start again with new file
 *
 */
session_start();
unset($_SESSION['file']);
header('Location: index.php');
exit;
