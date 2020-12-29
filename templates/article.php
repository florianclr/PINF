 <head>
   <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
 </head>
 
 <script src="vendor/jquery/jquery.min.js"></script>
<?php
	include_once "libs/maLibUtils.php";
	include_once "libs/maLibSQL.pdo.php";
	include_once "libs/maLibSecurisation.php"; 
	include_once "libs/modele.php"; 
	include_once "libs/maLibForms.php";

$produit = valider("produit");
?>

<script type="text/javascript">
	var produit="<?php echo $produit; ?>";
	console.log(produit);

var jImg=$('<div class="card h-100"><img class="card-img-top" alt=""/></div>');

var jTitre=$('<div class="card h-100"><h4 class="card-title"></h4></div>');

var jDescription=$('')


$.ajax({
    url: "libs/dataBdd.php",
    data:{"action":"Produit","idProduit":produit},
    type : "GET",
    success:function (oRep){
      console.log(oRep);
       $(".row").append(jTitre.clone(true).html(oRep[0].titre))
      $(".row").append(jImg.clone(true));
       $(".row .card-img-top").attr("src","./ressources/"+oRep[0].image+".jpeg");

        
    },
    error : function(jqXHR, textStatus) {
      console.log("erreur");  
    },
    dataType: "json"
  });       


</script>
<body>
 
 <h1 class="my-4"></h1>
 <div class="container">
<div class="row"></div>
</div>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
