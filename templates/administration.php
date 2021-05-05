<?php
	
	include_once "libs/maLibUtils.php";
	include_once "libs/maLibSQL.pdo.php";
	include_once "libs/maLibSecurisation.php"; 
	include_once "libs/modele.php"; 
	include_once "libs/maLibForms.php";

$idUser = valider("idUser","SESSION");

if(valider("connecte","SESSION")){

  	if(valider("isAdmin","SESSION"))
    	$admin=isAdmin($idUser);

    else {
      header("Location:index.php?view=catalogue");
      die("");
    }
}

else {
    header("Location:index.php?view=connexion");
    die("");
}

?>
<!-- Bootstrap -->
<script src="vendor/jquery/jquery.min.js"></script>
<script type="text/javascript" src="jquery-ui/jquery-ui.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">

var idUser=<?php echo $idUser; ?>;
var admin=<?php echo $admin; ?>;
var nom;
var jCompte = $('<div class="attente"></div>');
var jButtonOK = $('<input type="button" value="Accepter"/>').click(function() {
									var id=$(this).prop("id");
									if($("#admin").is(":checked"))
										var promouvoir="1";
									else var promouvoir="0";
				 					$.ajax({
                    					url: "libs/dataBdd.php?action=Accepter&idUser="+$(this).prop("id")+"&admin="+admin+"&promouvoir="+promouvoir,
                    					type : "PUT",
                    					success:function (oRep){
											console.log("Inscrit");
											console.log(oRep);
											$.ajax({
												url: "libs/dataBdd.php",
												data:{"action":"CompteAttente","admin":admin,"idUser":id},
												type : "GET",
												success:function (oRep){
											 		console.log(oRep);
											 		for (var i = 0; i <oRep.length; i++) {
											 			sendMail(oRep[i].mdp,oRep[i].mail);
											 		}

											 		$("#"+id).hide('slow', function() { 
															$(this).remove();
															$("#btn").remove();
														});

												},
												error : function(jqXHR, textStatus)
												{
													console.log("erreur");

												},
												dataType: "json"
											});//fin 2e requete
                						}//fin succes
                					});//fin 1er requete
                					$(this).parent().hide('slow', function() { 
                						$(this).remove();
                					});
				 				});

var jBox=$('<input type="checkbox" name="admin" id="admin"/><label for="admin ">admin</label>');

var jButtonNO = $('<input type="button" value="Refuser"/>').click(function() {
				 					$.ajax({
                    					url: "libs/dataBdd.php?action=Refuser&idUser="+$(this).prop("id")+"&admin="+admin,
                    					type : "DELETE",
                    					success:function (){
											console.log("refuser");
                						}//fin succes
                					});//fin 1er requete
                					$(this).parent().hide('slow', function() { 
                						$(this).remove();
                					});
				 				});

var jBtnAccepter=$('<input type="button" id="btn" value="Tout accepter"/>').click(function(){
	$(".attente").each(function () {
		var id=$(this).prop("id");
		$.ajax({
	                url: "libs/dataBdd.php?action=Accepter&idUser="+id+"&admin="+admin,
	                type : "PUT",
	                success:function (oRep){
	                	console.log(oRep);
	                	$.ajax({
			                url: "libs/dataBdd.php",
			                data:{"action":"CompteAttente","admin":admin,"idUser":id},
			                type : "GET",
			                success:function (oRep){
						 		console.log(oRep);
						 		for (var i = 0; i <oRep.length; i++) {
						 			sendMail(oRep[i].mdp,oRep[i].mail);
						 		}

						 		$("#"+id).hide('slow', function() { 
                						$(this).remove();
										$("#btn").remove();
                					});

							},
							error : function(jqXHR, textStatus)
							{
								console.log("erreur");

							},
							dataType: "json"
						});//fin 2e requete

				}//fin succes
				});//fin 1er requete 

	})//fin each
});//fin click

var jOptionSelect = $('<option></option>');


var JMenuSelectF =$('<label for="listeFinition">Liste des finitions : </label> <select class="listeAdministration" id="listeFinition"> </select>')
	.click(function () {

		var idF=$(this).val();
		var nbFinitions;
		
		
		
		$.ajax({
        url: "libs/dataBdd.php?action=NbFinitions&idFinition="+idF,
        type : "GET",
        success:function (oRep){
        	console.log(oRep);
        	nbFinitions=oRep;
        	$("#warning").remove();

        },// fin succes
        error : function(jqXHR, textStatus) {
          console.log("erreur");
        },

        dataType: "json"
      });// fin requête ajax

		$('body').append(popupFinition.clone(true));
		$("#popupF").dialog({
      modal: true, 
      height: 250,
      width: 400,
      buttons: { // on ajoute des boutons à la pop up 
        "OUI": function(){
        if(nbFinitions==0){

	      $.ajax({
	        url: "libs/dataBdd.php?action=Finition&idFinition="+idF,
	        type : "DELETE",
	        success:function (oRep){
	        	console.log(oRep);
	        	$("#popupF").dialog( "close" ); // ferme la pop up 
	        $("#popupF").remove(); // supprime la pop up
	        $("#listeFinition option:selected").remove();
	        

	        },// fin succes
	        error : function(jqXHR, textStatus) {
	          console.log("erreur");
	        },

	        dataType: "json"
	      });// fin requête ajax

		}

		else {
			 	$("#popupF").remove(); // supprime la pop up
				$("#infoSA").append("<div id='warning'>Impossible de supprimer une finition paramétrée pour des ferrures déjà existantes</div>");
		}

        },
        "NON": function() {
        $(this).dialog("close"); // ferme la pop up 
        $(this).remove(); // supprime la pop up
        },
      },
      close: function() { // lorsque on appui sur la croix pour fermer la pop up
      $(this).remove(); // supprime la pop up 
      }

    });

});

var JMenuSelectM =$('<label for="listeMatiere">Liste des matières : </label> <select class="listeAdministration" id="listeMatiere"> </select>')
	.click(function () {

		var idM=$(this).val();
		var nbMatiere;
		$("#warning").remove();
		
		$.ajax({
        url: "libs/dataBdd.php?action=NbMatieres&idMatiere="+idM,
        type : "GET",
        success:function (oRep){
        	console.log(oRep);
        	nbMatiere=oRep;
        

        },// fin succes
        error : function(jqXHR, textStatus) {r
          console.log("erreur");
        },

        dataType: "json"
      });// fin requête ajax

		$('body').append(popupMatiere.clone(true));
		$("#popupMat").dialog({
      modal: true, 
      height: 250,
      width: 400,
      buttons: { // on ajoute des boutons à la pop up 
        "OUI": function(){

        if(nbMatiere==0){
	      $.ajax({
	        url: "libs/dataBdd.php?action=Matiere&idMatiere="+idM,
	        type : "DELETE",
	        success:function (oRep){
	        	console.log(oRep);
	        	$("#popupMat").dialog("close"); // ferme la pop up 
	        $("#popupMat").remove(); // supprime la pop up
	        $("#listeMatiere option:selected").remove();
	        

	        },// fin succes
	        error : function(jqXHR, textStatus) {
	          console.log("erreur");
	        },

	        dataType: "json"
	      });// fin requête ajax
	    }
	    else {
	    	 	$("#popupMat").remove(); // supprime la pop up
	        $("#infoSA").append("</br><div id='warning'>Impossible de supprimer une matière paramétrée pour des ferrures déjà existantes</div>");
	    }


        },
        "NON": function() {
        $(this).dialog("close"); // ferme la pop up 
        $(this).remove(); // supprime la pop up
        },
      },
      close: function() { // lorsqu'on appuie sur la croix pour fermer la pop up
      $(this).remove(); // supprime la pop up 
      }

    });

});

var JMenuSelectU =$('<label for="listeUtilisateur">Liste des utilisateurs : </label> <select class="listeAdministration" id="listeUtilisateur"> </select>').click(function () {

		var idU=$(this).val();
		var nbUser;
		$("#warning").remove();
		
		$.ajax({
        url: "libs/dataBdd.php?action=NbDevisUser&idUser="+idU,
        type : "GET",
        success:function (oRep){
        	console.log(oRep);
        	nbUser=oRep;
        

        },// fin succes
        error : function(jqXHR, textStatus) {r
          console.log("erreur");
        },

        dataType: "json"
      });// fin requête ajax

		$('body').append(popupUser.clone(true));
		$("#popupUs").dialog({
      modal: true, 
      height: 250,
      width: 400,
      buttons: { // on ajoute des boutons à la pop up 
        "OUI": function(){

        if(nbUser==0){
	      $.ajax({
	        url: "libs/dataBdd.php?action=User&idUser="+idU,
	        type : "DELETE",
	        success:function (oRep){
	        	console.log(oRep);
	        	$("#popupUs").dialog("close"); // ferme la pop up 
			    $("#popupUs").remove(); // supprime la pop up
			    $("#listeUtilisateur option:selected").remove();
	        

	        },// fin succes
	        error : function(jqXHR, textStatus) {
	          console.log("erreur");
	        },

	        dataType: "json"
	      });// fin requête ajax
	    }
	    else {
	    	$("#popupUs").remove(); // supprime la pop up
	        $("#infoSA").append("</br><div id='warning'>Veuillez supprimer tous les devis de l'utilisateur avant de le supprimer</div>");
	    }


        },
        "NON": function() {
        $(this).dialog("close"); // ferme la pop up 
        $(this).remove(); // supprime la pop up
        },
      },
      close: function() { // lorsqu'on appuie sur la croix pour fermer la pop up
      $(this).remove(); // supprime la pop up 
      }

    });

});
	
	

$.ajax({
                url: "libs/dataBdd.php",
                data:{"action":"CompteAttente","admin":admin,},
                type : "GET",
                success:function (oRep){
			 	console.log(oRep);
					$("#listeCompte").append($("<h4>Demandes de création de compte</h4></br>"));
				 	if(oRep.length != 0){
				 		for (var i = 0; i < oRep.length; i++) {
				 			$("#listeCompte").append(jCompte.clone(true).attr("id",oRep[i].id)
				 				.append($('<div class="demande">Demande de <b>'+oRep[i].prenom+' '+oRep[i].nom+'</b></div>'))
				 				.append(jButtonOK.clone(true).attr("id",oRep[i].id))
				 				.append(jButtonNO.clone(true).attr("id",oRep[i].id))
				 				.append(jBox.clone(true))
				 			);//fin append
							if ($("#btn").length == 0)
								$("h4").after(jBtnAccepter.clone(true));
				 		}
				 	}
					else
			 			$("#listeCompte").append($("<div>Aucune demande de création de compte n'a été envoyée</div></br>"));
			
			 },
			error : function(jqXHR, textStatus)
			{
				console.log("erreur");
				 
			},
			dataType: "json"
});


var popupFinition = $('<div id="popupF" class="popupAdministration" title="Confirmer la suppression"> <h4 id="warningConfirm">Voulez-vous vraiment supprimer cette finition ?</h4>');

var popupMatiere= $('<div id="popupMat" class="popupAdministration" title="Confirmer la suppression"> <h4 id="warningConfirm">Voulez-vous vraiment supprimer cette matière ?</h4>');

var popupUser= $('<div id="popupUs" class="popupAdministration" title="Confirmer la suppression"> <h4 id="warningConfirm">Voulez-vous vraiment supprimer cet utilisateur ?</h4>');

var popupMail = $('<div id="popupM" class="popupAdministration" title="Confirmer le changement"><h4 id="warningConfirm">Voulez-vous vraiment changer le destinataire des mails ?</h4>');

var JMenu =$('<label id="newDest" for="listeMail">Choisir un nouveau destinataire : </label> <select class="listeAdministration" id="listeMail"></select>')
    .click(function () {

        var id = $(this).val();
        nom = $("#listeMail option:selected").text();
        $("#warning").remove();
        
        $('body').append(popupMail.clone(true));
        $("#popupM").dialog({
      modal: true, 
      height: 250,
      width: 400,
      buttons: { // on ajoute des boutons à la pop up 
        "Oui": function(){
		    $.ajax({
		      url: "libs/dataBdd.php?action=Destinataire&idUserD="+id+"&idUser="+idUser,
		      type : "PUT",
		      success:function (oRep){
		          console.log(oRep);
		          $("#popupM").dialog( "close" ); // ferme la pop up 
		          $("#popupM").remove(); // supprime la pop up
		          $("#mail").remove();
							$('#mailActuel').html('Le destinataire actuel des mails est <b>'+nom+'</b>');
							$('#mailActuel').css('margin-bottom','40px');
							$('#chgtDestOK').css('display','block');
							$('#deleteUser').hide();
		      },// fin succes
		      error : function(jqXHR, textStatus) {
		        console.log("erreur");
		      },

		      dataType: "json"
		    });// fin requête ajax


        },
        "Non": function() {
        $(this).dialog( "close" ); // ferme la pop up 
        $(this).remove(); // supprime la pop up
        },
      },
      close: function() { // lorsqu'on appuie sur la croix pour fermer la pop up
      $(this).remove(); // supprime la pop up 
      }

    });

    });

var jOptionSelect = $('<option></option>');
$(document).ready(function(){

		if (admin == 1 || admin == 2){
		    selectFinition();
		    selectMatiere();
    	}
    	if (admin == 2) {
    		$("#deleteUser").show();
    		selectUser();
    	}

		$.ajax({
		    url: "libs/dataBdd.php",
		    data:{"action":"UserAdmin"},
		    type : "GET",
		    success: function(oRep){
		        console.log(oRep);
		        
		        if (admin == 2) {
				      $('#mail').append(JMenu.clone(true));
				      for (var i = 0; i < oRep.length; i++) {
				          if(oRep[i].admin == 1)
				          	$('#listeMail').append(jOptionSelect.clone(true).html(oRep[i].nom+" "+oRep[i].prenom).val(oRep[i].id));
				          if(oRep[i].admin == 2) {
				          	$('#mailActuel').append('Le destinataire actuel des mails est <b>'+oRep[i].nom+" "+oRep[i].prenom+'</b>');
				          }
				      }
						}
						else {
							for (var i = 0; i < oRep.length; i++) {
				          if(oRep[i].admin == 2) {
				          	$('#mailActuel').append('Le destinataire actuel des mails est <b>'+oRep[i].nom+" "+oRep[i].prenom+'</b>');
				          }
				      }
						}
		    },
		  error : function(jqXHR, textStatus) {
		      console.log("erreur");
		  },
		  dataType: "json"
		});
});

function selectFinition() {

    $.ajax({
          url: "libs/dataBdd.php",
          data:{"action":"Finitions"},
          type : "GET",
          success: function(oRep){
              console.log(oRep);
              $('#finitions').append(JMenuSelectF.clone(true));
              for (var i = 0; i < oRep.length; i++) {
                  $('#listeFinition').append(jOptionSelect.clone(true).val(oRep[i].id).html(oRep[i].nomF));
              }

          },
        error : function(jqXHR, textStatus) {
            console.log("erreur");
        },
        dataType: "json"
      });
}

function selectMatiere() {

    $.ajax({
      url: "libs/dataBdd.php",
      data:{"action":"Matieres"},
      type : "GET",
      success: function(oRep){
          console.log(oRep);
          $('#matiere').append(JMenuSelectM.clone(true));
          for (var i = 0; i < oRep.length; i++) {
              $('#listeMatiere').append(jOptionSelect.clone(true).val(oRep[i].id).html(oRep[i].nomM));
          }

      },
    error : function(jqXHR, textStatus) {
        console.log("erreur");
    },
    dataType: "json"
  });
}

function selectUser() {

    $.ajax({
      url: "libs/dataBdd.php",
      data:{"action":"NoSuperadmins"},
      type : "GET",
      success: function(oRep){
          console.log(oRep);
          $('#utilisateurs').append(JMenuSelectU.clone(true));
          for (var i = 0; i < oRep.length; i++) {
              $('#listeUtilisateur').append(jOptionSelect.clone(true).val(oRep[i].id).html(oRep[i].prenom+" "+oRep[i].nom));
          }

      },
    error : function(jqXHR, textStatus) {
        console.log("erreur");
    },
    dataType: "json"
  });
}

function sendMail(mdp,mailD) {

	var email = "no-reply@decima.fr";
	var subject = "Mot de passe du compte ";

	var body = "Votre compte a été validé, votre mot de passe est <b>"+mdp+"</b> et votre login est <b>"+mailD +"</b>. Pour vous connecter, rendez vous sur http://185.30.209.4/PINF/";

	$.ajax({
		url: 'PHPMailer/mail.php',
		method: 'POST',
		dataType: 'json',

		data: {
			name: "decima-ne-pas-repondre",
			email: email,
			subject: subject,
			body: body,
			mailD: mailD
		},

		success: function(response) {
			console.log(response);
		},

		error: function(response) {	
			console.log(response);
		}
	});

}

</script>

<body>
<div id="listeCompte"></div>
<div id="chgtDestOK">Le destinataire des mails a bien été changé</div>
<div id="infoSA">
	<div class="titreSection">Gestion des mails</div>
	<div id="mailActuel"></div>
	<div id="mail"></div>
	<div class="titreSection">Suppression de finitions / matières</div>
	<div id="finitions"></div>
	<div id="matiere"></div>
	<div id="deleteUser">
		<div class="titreSection">Suppression d'utilisateurs</div>
		<div id="utilisateurs"></div>
	</div>
</div>
</br></br></br></br></br>
</body>

</html>
