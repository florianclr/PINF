<?php
	
	include_once "libs/maLibUtils.php";
	include_once "libs/maLibSQL.pdo.php";
	include_once "libs/maLibSecurisation.php"; 
	include_once "libs/modele.php"; 
	include_once "libs/maLibForms.php";

	// on sélectionne une rubrique différente du site dans le menu
	//$(".sr-only").html("(current)");

if(valider("connecte","SESSION"))
	if(valider("isAdmin","SESSION"))
		$admin=1;
	else $admin=0;
else $admin=0;

?>
<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script type="text/javascript" src="jquery-ui/jquery-ui.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">

var jCompte = $('<div class="attente"></div>');
var jButtonOK = $('<input type="button" value="Accepter"/>').click(function() {
				 					$.ajax({
                    					url: "libs/dataBdd.php?action=Accepter&idUser="+$(this).prop("id")+"&admin="+admin,
                    					type : "PUT",
                    					success:function (){
											console.log("Inscrit");
                						}//fin succes
                					});//fin 1er requete
                					$(this).parent().hide('slow', function() { 
                						$(this).remove();
                					});
				 				});
var jButtonNO = $('<input type="button" value="Refuser"/>');
var admin=<?php echo $admin; ?>;

$.ajax({
                url: "libs/dataBdd.php",
                data:{"action":"CompteAttente","admin":admin,},
                type : "GET",
                success:function (oRep){
			 	console.log(oRep);
				 	if(oRep.length != 0){
				 		$("#listeCompte").append($("<h4><b>Demandes de création de compte</b></h4></br>"));
				 		for (var i = 0; i < oRep.length; i++) {
				 			$("#listeCompte").append(jCompte.clone(true).attr("id",oRep[i].id)
				 				.append($('<div class="demande">Demande de <b>'+oRep[i].prenom+' '+oRep[i].nom+'</b></div>'))
				 				.append(jButtonOK.clone(true).attr("id",oRep[i].id))
				 				.append(jButtonNO.clone(true))
				 			);//fin append
				 		}
				 	}
			 	
			
			 },
			error : function(jqXHR, textStatus)
			{
				console.log("erreur");
				 
			},
			dataType: "json"
			});


</script>

<body>
<div id="listeCompte"></div>
</br></br></br></br></br>
</body>

</html>
