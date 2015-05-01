<?php
/**
 * @file
 * drop session and start again with new file
 *
 */
session_start();
$_SESSION['file'] = '';
header('Location: /');
