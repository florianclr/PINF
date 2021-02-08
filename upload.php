<?php
include_once("libs/modele.php");
include_once ("libs/maLibUtils.php");
include_once ("libs/maLibSecurisation.php"); 
// https://www.php.net/manual/fr/features.file-upload.post-method.php
$mode=valider("mode"); 

if($mode=="img" || $mode=="preview")
  $target_dir = "./images/" ;

else if($mode=="pdf")
  $target_dir = "./plan/";

$target_file = $target_dir . basename($_FILES["file"]["name"]);


$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["file"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
}

// Check if file already exists
if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["file"]["size"] > 1000000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// vérifie les formats
if($imageFileType != "pdf" && $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}
if ($mode == "pdf" &&  $imageFileType != "pdf") {
   echo "Sorry, only PDF files are allowed.";
   $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "error" ;
// if everything is ok, try to upload file
} else {

  if($mode=="preview"){
    $target_file = $target_dir . "preview"; 
  }
  if (move_uploaded_file($_FILES["file"]["tmp_name"],$target_file)) {
  	  echo $target_file; 
    //echo "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). "has been uploaded.";
  } else {
    echo "error";
  }
}
?>
