<?php
/**
 * @file
 * drop session and start again with new file
 *
 */
session_start();
if (isset($_SESSION) && $_SESSION['file']) {
  unset($_SESSION['file']);
}
session_destroy();

header('Location: ' . '/');
die();
