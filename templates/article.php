 
<?php
$produit = valider("produit");
?>


<script type="text/javascript">
  var tab = ['mediumblue', 'darkred', 'yellowgreen', 'indigo', 'darkcyan'];

  var produit="<?php echo $produit; ?>";
  console.log(produit);

  var jImg=$('<div class="card h-100" id="imgProduct"><img class="card-img-top" alt=""/></div>');

  var jTitre=$('<div class="card h-100" id="titleProduct"><h4 class="card-title"></h4></div>');

  var jDescription=$('<div class="contenu"><h5>Description</h5><p id="description"></p></div>');
  
  var jTable=$('<table><tr><td>Matière</td><td id="mat"></td></tr><tr><td >Finition</td><td id="fin"></td></tr><tr><td>N° de plan</td><td id="plan"></td></tr></table>');
  var jLien = $('<a></a>');

  $.ajax({
    url: "libs/dataBdd.php",
    data:{"action":"Produit","idProduit":produit},
    type : "GET",
    success: function(oRep){
      var couleurFond = tab[(oRep[0].refcategories)-1];
        console.log(oRep);
        console.log(couleurFond);
        
        $(".product").append(jTitre.clone(true).html(oRep[0].titre));
        $("#titleProduct").css("background-color", couleurFond);
        $(".row").append(jImg.clone(true));
        $(".row .card-img-top").attr("src","./ressources/"+oRep[0].image+".jpeg");
        $(".row").append(jDescription.clone(true));
        $(".row #description").html(oRep[0].description);
        
        $(".contenu").append(jTable.clone(true));
        $(".contenu #mat").html(oRep[0].nomM);
        $(".contenu #fin").html(oRep[0].nomF);
        $(".contenu #plan").append(jLien.clone(true).attr("href","templates/telecharger.php?pdf="+oRep[0].planPDF).html(oRep[0].numeroPlan))
    },
    error : function(jqXHR, textStatus) {
      console.log("erreur");  
    },
    dataType: "json"
    });       


</script>
<body>

   
    <div class="container">
      <div class="product"></div> 
      <div class="row">
      </div>
     
    </div>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>

