<?php
require_once('includes/header.php');
require_once('JEditError.class.php');

// No file selected.
if(!array_key_exists('fileToUpload', $_FILES)
    || $_FILES['fileToUpload']['name'] ==='' ) {
  JEditError::errorMsg('Please select a file to upload.', null, '', BASE);
  die;
}

$target_dir = 'files/';
$target_file = $target_dir . basename($_FILES['fileToUpload']['name']);
$fileExt = pathinfo($target_file, PATHINFO_EXTENSION);
$msg = '';

if(isset($_POST['submit'])) {

  // File does not have json extention
  if($fileExt != 'json') {
    JEditError::errorMsg('File ext is not json.', null, '', BASE);
    die;
  }

  // Is tick to replace existing file ticked?
  $replace = $_POST['replace'] ? true : false;
 
  // Check if file already exists and only replace if ticked
  if (file_exists($target_file) && $replace === false) {
    $msg = 'Sorry, a file with that name already exists. Please rename and try again or select the replace option.';
    JEditError::errorMsg($msg, null, '', BASE);
    die;
  }

  // Check file size
  if ($_FILES['fileToUpload']['size'] > 500000) {
    $msg = 'Sorry, your file is too large.';
    JEditError::errorMsg($msg, null, '', BASE);
    die;
  }

  // TODO check for valid JSON
  //if(! valid JSON) {
  //  $msg = 'Sorry, your file is not valid JSON.';
  //  JEditError::errorMsg($msg, null, '', BASE);
  //  die;
  //}

  // if everything is ok, try to upload file
  if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
    $msg = 'The file '. basename( $_FILES['fileToUpload']['name']). ' has been uploaded.';
    JEditError::errorMsg($msg, null, '', BASE, true);
    die;
  } else {
    $msg = 'Sorry, there was an error uploading your file.';
    JEditError::errorMsg($msg, null, '', BASE);
    die;
  }
}
?>

