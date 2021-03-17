<?php

// librairies php //  
	include_once "libs/maLibUtils.php";
	include_once "libs/maLibSQL.pdo.php";
	include_once "libs/maLibSecurisation.php"; 
	include_once "libs/modele.php"; 
	include_once "libs/maLibForms.php";

// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php");
	die("");
}
if(valider("connecte","SESSION"))
  if(valider("isAdmin","SESSION"))
    $admin=1;
  else $admin=0;
else $admin=0;

if(valider("connecte","SESSION"))$connecte=1;
else $connecte=0;
// Pose qq soucis avec certains serveurs...
echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<!-- **** H E A D **** -->
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Decima</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/homepage.css" rel="stylesheet">  <!-- => contient notre css -->

  <link href="jquery-ui/jquery-ui.css" rel="stylesheet" />
  <link href="jquery-ui/jquery-ui.structure.css" rel="stylesheet" />
  <link href="jquery-ui/jquery-ui.theme.css" rel="stylesheet" />

  <script src="vendor/jquery/jquery.min.js"></script>  <!-- => css de la bootstrap -->


</head>
<!-- **** F I N **** H E A D **** -->

<!-- **** B O D Y **** -->
<body>

<!-- style inspiré de http://www.bootstrapzero.com/bootstrap-template/sticky-footer --> 

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">

     <img id="logo" src="ressources/logo.jpg"/>
     
    <div class="container">

      <!-- BOUTON D'AFFICHAGE DES MENUS LORSQUE LA PAGE RÉTRÉCIT  -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- BARRE DES MENUS : TODO  MODIF CODE ET COMPRENDE BOSSTRAP -->
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
        	<?php 
        	if($connecte){
        		echo '<li class="nav-item active">
            <a class="nav-link" href="index.php?view=catalogue">Catalogue
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="index.php?view=devis">Devis</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="#">Planning</a>
          </li>';
      			};
        	 ?>
        	
          
          <li class="nav-item active">
            <a class="nav-link" href="index.php?view=connexion">Connexion/Compte</a>
          </li>
           <?php 
          if($admin){

            echo '<li class="nav-item active">';
            echo '<a class="nav-link" href="index.php?view=administration">Administration</a>';
            echo "</li>";
          }
           ?>
        </ul>
      </div>
    </div>
  </nav>

</body>







