<?php
  
  include_once "../libs/maLibUtils.php";
	// chemin physique de la page courante

$plan=valider("pdf");

  header('Content-Disposition: Attachment;filename='.$plan);
  // pour renommer la ressource du point de vue du navigateur
  echo("test");

  $name = '../plan/'.$plan; 

  header("Content-Type: application/pdf");  
  // de type pdf

  header("Content-Length: " . filesize($name)); 
  // dont on récupère la taille pour l'envoyer au navigateur 

  readfile($name);  
  // envoi du contenu binaire 
?>


