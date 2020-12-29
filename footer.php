<?php
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

  <title>Shop Homepage - Start Bootstrap Template</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/shop-homepage.css" rel="stylesheet">
  <script src="vendor/jquery/jquery.min.js"></script>

<script type="text/javascript">
  
function deconnexion() {

  $.ajax({
    url: "libs/dataBdd.php",
    data:{"action":"Deconnexion"},
    type : "GET",
    success:function (oRep){
      console.log(oRep);
      document.location.href="./index.php";
    },
    error : function(jqXHR, textStatus){
      console.log("erreur");
    },
    dataType: "json"
  });

}

</script>
</head>
<!-- **** F I N **** H E A D **** -->


<!-- **** B O D Y **** -->
<body>

<footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; Déci'2i</p>
      <p class="m-0 text-center text-white">
      <?php
    // Si l'utilisateur est connecte, on affiche un lien de deconnexion 
    if (valider("connecte","SESSION"))
    {
      echo "Utilisateur <b>$_SESSION[pseudo]</b> connecté depuis <b>$_SESSION[heureConnexion]</b> &nbsp; "; 
      echo '<input type="button" onclick="deconnexion();" value="Se déconnecter"/>';
    }

    ?>
  </p>
    </div>
    <!-- /.container -->
  </footer>





