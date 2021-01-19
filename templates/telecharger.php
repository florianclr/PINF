<?php
  
  include_once "../libs/maLibUtils.php";
// chemin physique de la page courante 
 //die(__FILE__);

$plan=valider("pdf");

  header('Content-Disposition: Attachment;filename='.$plan.".pdf");
  // pour renommer la ressource du point de vue du navigateur

  // TODO : changer le chemin du fichier pdf
  $name = '../plan/'.$plan.".pdf"; 

  header("Content-Type: application/pdf");  
  // de type pdf

  header("Content-Length: " . filesize($name)); 
  // dont on récupère la taille pour l'envoyer au navigateur 

  readfile($name);  
  // envoi du contenu binaire 
?>


