<?php
	
	include_once "libs/maLibUtils.php";
	include_once "libs/maLibSQL.pdo.php";
	include_once "libs/maLibSecurisation.php"; 
	include_once "libs/modele.php"; 
	include_once "libs/maLibForms.php";

	// on sélectionne une rubrique différente du site dans le menu
	//$(".sr-only").html("(current)");

$devis = valider("devis");
 $idUser = valider("idUser","SESSION"); 

if (!valider("connecte","SESSION")){
  header("Location:index.php?view=connexion");
  die("");
}

if(valider("connecte","SESSION")) {
	$connecte = 1;
} 
else 
	$connecte = 0;

?>
<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script type="text/javascript" src="jquery-ui/jquery-ui.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">

  var devis="<?php echo $devis; ?>";
  var idUser="<?php echo $idUser; ?>";
  var prixTotal=0;

  var jButton = $('<input type="button" id="commander" value="Passer la commande"/>').click(function () {
  		
  		$.ajax({
		    url: "libs/dataBdd.php?action=Commander&idDevis="+devis+"&idUser="+idUser,
		    type : "PUT",
		    success: function(oRep){
		        console.log(oRep);
		        //TODO redirection vers la page des devis

		    },
		    error : function(jqXHR, textStatus) {
		      console.log("erreur");
		    },
		    dataType: "json"
		});

  });

var jTitre =$('<div class="card h-100" id="titleProduct"><h4 class="card-title"></h4></div>');

var jTable=$('<table id="devis"><tr id="lig0"><td class="tabDevis"></td><td>Nom Ferrure</td><td>Quantité</td><td>Prix</td></tr></table>');


var jImg=$('<img  class="imgSuppArtDevis" src="./ressources/moins.png"/>').click(function(){
	console.log($(this).prop("id"));

	$.ajax({
			    url: "libs/dataBdd.php?action=FerrureDevis&idFerrureDevis="+$(this).prop("id")+"&idUser="+idUser,
			    type : "DELETE",
			    success: function(oRep){
			    	console.log(oRep);
			      			
			    },
			    error : function(jqXHR, textStatus) {
			      console.log("erreur");
			    },
			    dataType: "json"
			    });

	$(this).parent().parent().hide('slow', function() { 
                						$(this).remove();
             					});

});

$.ajax({
    url: "libs/dataBdd.php",
    data:{"action":"listerDevis","idDevis":devis,"idUser":idUser},
    type : "GET",
    success: function(oRep){
        console.log(oRep);
        //$("#titleProduct").html("Devis pour le client "+oRep[0].nomClient+" pour le projet "+oRep[0].nomProjet);
        $(".card-title").html("Devis");

    },
    error : function(jqXHR, textStatus) {
      console.log("erreur");
    },
    dataType: "json"
    });

	$.ajax({
    url: "libs/dataBdd.php",
    data:{"action":"listerFerruresDevis","idDevis":devis,"idUser":idUser},
    type : "GET",
    success: function(oRep){
        console.log(oRep);

        for (var i =0; i < oRep.length; i++) {


        	$("#lig0")
        	.after($('<tr id="lig'+(i+1)+'""><td class="tabDevis" id="img"></td><td class="tabDevis" id="nomF'+i+'"></td><td class="tabDevis" id="qte'+i+'"></td><td class="tabDevis"id="prix'+i+'""></td></tr>'));

        	$("#img").prepend(jImg.clone(true).attr("id",oRep[i].id));


        	$("#qte"+i).html(oRep[i].quantite);
        	$("#prix"+i).html(oRep[i].prix + "€");
        	remplirTab(i,oRep);
        	prixTotal+=parseInt(oRep[i].prix);

		}//fin for
		$(".row").append($('<div></div>').html("Le prix total de votre devis est de : "+prixTotal+" €"));

		$(".row").append(jButton.clone(true));

	},
	error : function(jqXHR, textStatus) {
	console.log("erreur");
	},
	dataType: "json"
	});

function remplirTab(i,oRep) {

	$.ajax({
		url: "libs/dataBdd.php",
		data:{"action":"Produit","idProduit" :oRep[i].refFerrures},
			    type : "GET",
			    success: function(oRep){
			   		$("#nomF"+i).html(oRep[0].titre);
			    },
			    error : function(jqXHR, textStatus) {
			      	console.log("erreur");
			    },
			    dataType: "json"
			    });
}

$(document).ready(function(){
	$(".row").append(jTitre.clone(true));
	$(".row").append(jTable.clone(true));
})
</script>

<body>

   <br/>
    <div class="container">    
      <div class="row"></div>
    <br/>
    </div>

</body>

</html>

