 
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
  
  var jTable=$('<div id="T1"><table><tr><td>Matière</td><td id="mat"></td></tr><tr><td >Finition</td><td id="fin"></td></tr><tr><td>N° de plan</td><td id="plan"></td></tr></table></div>');

  var jLien = $('<a></a>');

  var jTable2=$('<div id="T2"><table><tr id="dim"></tr><tr id="prix"></tr></table></div>');

  var jTable3=$('<div id="T3"><table id="options"></table></div>');

  var jCheckBox=$('<td><input type="checkbox"/></td>');

	// T1
	$.ajax({
    url: "libs/dataBdd.php",
    data:{"action":"Produit","idProduit":produit},
    //data:{"action":"Prix","idProduit":produit},
    //data:{"action":"Options","idProduit":produit},
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
        $("#description").html(oRep[0].description);
        
        // T1
        $(".contenu").append(jTable.clone(true));
        $("#mat").html(oRep[0].nomM);
        $("#fin").html(oRep[0].nomF);
        $("#plan").append(jLien.clone(true).attr("href","templates/telecharger.php?pdf="+oRep[0].planPDF).html(oRep[0].numeroPlan))
        
    },
    error : function(jqXHR, textStatus) {
      console.log("erreur");  
    },
    dataType: "json"
    });   
      
    
	// T2
	$.ajax({
    url: "libs/dataBdd.php",
    data:{"action":"Prix","idProduit":produit},
    type : "GET",
    success: function(oRep){
        console.log(oRep);
        $("#description").html(oRep[0].description);
        
        $(".contenu").append(jTable2.clone(true));
        $("#dim").append($('<td></td>').html("Dimensions"))
        $("#prix").append($('<td></td>').html("PU"))
        for (var i = 0; i< oRep.length; i++) {
		     $("#dim").append($('<td class="intervalles"></td>').html(oRep[i].min+":"+oRep[i].max));
		     $("#prix").append($('<td class="prixU"></td>').html(oRep[i].prixU));
        }
    },
    error : function(jqXHR, textStatus) {
      console.log("erreur");
    },
    dataType: "json"
    });
    

	// T3
    $.ajax({
    url: "libs/dataBdd.php",
    data:{"action":"Options","idProduit":produit},
    type : "GET",
    success: function(oRep){
        console.log(oRep);
        
        $("#description").html(oRep[0].description);
        $(".contenu").append(jTable3.clone(true));
        for (var i = 0; i< oRep.length; i++) {
           $("#options").append($('<tr></tr>').attr("id",oRep[i].id));
           $("#"+oRep[i].id).append(jCheckBox.clone(true).attr("id",oRep[i].id)).append($('<td></td>').html(oRep[i].nom)).append($('<td class="prixOpt"></td>').html(oRep[i].prix));

        }

    },
    error : function(jqXHR, textStatus) {
      console.log("erreur");
    },
    dataType: "json"
    });

   


</script>
<body>

   <br/><br/>
    <div class="container">    
      <div class="product"></div> <!-- TODO:MODIF NOM -->
      <div class="row"></div>
    <br/><br/><br/><br/><br/><br/><br/>
    </div>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>

