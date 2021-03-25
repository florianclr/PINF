<?php
	
	include_once "libs/maLibUtils.php";
	include_once "libs/maLibSQL.pdo.php";
	include_once "libs/maLibSecurisation.php"; 
	include_once "libs/modele.php"; 
	include_once "libs/maLibForms.php";

	// on sélectionne une rubrique différente du site dans le menu
	//$(".sr-only").html("(current)");

$login = valider("login", "COOKIE");
$passe = valider("passe", "COOKIE");
  

if ($checked = valider("remember", "COOKIE")) $checked = "checked"; 

if(valider("connecte","SESSION")) {
	$connecte = 1;
	$passe = getInfo($_SESSION["idUser"], "mdp");
	$mail = getInfo($_SESSION["idUser"], "mail");
	$tel = getInfo($_SESSION["idUser"], "telephone");
	// echo $passe;
	// echo $mail;
	// echo $tel;
} 
else 
	$connecte = 0;

?>
<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script type="text/javascript" src="jquery-ui/jquery-ui.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">

var popupCreate = $("<div id='popup' title='Créer un compte'>")
				.append($("<label class='champ'>Nom :</label><input type='text' id='surname'></br>"))
				.append($("<label class='champ'>Prénom :</label><input type='text' id='firstname'></br>"))
    			.append($("<label class='champ'>Mail :</label><input type='email' id='mail'></br>"))
				.append($("<label class='champ'>Téléphone :</label><input type='text' id='tel'></br>")); 


function connexion() {
	var login = $("#login").val();
	var passe = $("#passe").val();
	if($("#remember").is(":checked"))var remember="1";
	else var remember="0";
	console.log(remember);

	$.ajax({
                url: "libs/dataBdd.php",
                data:{"action":"Connexion","login":login,"passe":passe,"remember":remember},
                type : "GET",
                success:function (oRep){
			 	console.log(oRep);
			 	document.location.href="./index.php?view=catalogue";
			
			 },
			error : function(jqXHR, textStatus)
			{
				$("#erreur").html("Login ou mot de passe incorrect").show();
				 
			},
			dataType: "json"
			});
}

function createButton() {
	$("#newAccount").show(); 
}

function createPopUp(){
	 $("#connexion").append(popupCreate.clone(true));
     $("#popup").dialog({
         modal: true, // permet de rendre le reste de la page inaccesible tant que la pop up est ouverte
		 height: 330,
		 width: 400,
         buttons: { // on ajoute des boutons à la pop up 
             "Envoyer ma demande": function(){
               	sendMail();  // envoi d'un mail
             },
             "Annuler": function() {
               	$(this).dialog("close"); // ferme la pop up 
               	$(this).remove(); // supprime la pop up
             },
         },
         close: function() { // lorsque on appui sur la croix pour fermer la pop up 
            console.log("Fermeture du pop-up");
            $(this).remove(); // supprime la pop up 
         }
	}); // DOC jquery UI : https://jqueryui.com/dialog/#modal-message
}

function updateInfos(idUser) {
	<?php
	if ($connecte)
	{
		?>
		var passe = '<?php echo $passe;?>';
		var mail = '<?php echo $mail;?>';
		var tel = '<?php echo $tel;?>';

		var newPasse = $.trim($("#passe").val());
		var newMail = $.trim($("#mail").val());
		var newTel = $.trim($("#tel").val());

		console.log(newPasse);
		console.log(newMail);
		console.log(newTel);

		if (passe != newPasse)
		{
			$.ajax({
                    url: "libs/dataBdd.php?action=Info&info=mdp&value="+newPasse,
                    type : "PUT",
                    success: function (oRep){
                    	console.log(oRep);
                    },
                    dataType: "json"
                });
		}

		if (mail != newMail)
		{
			$.ajax({
                    url: "libs/dataBdd.php?action=Info&info=mail&value="+newMail,
                    type : "PUT",
                    success: function (oRep) {
                    	console.log(oRep);
                    },
                    dataType: "json"
                });
		}

		if (tel != newTel)
		{
			$.ajax({
                    url: "libs/dataBdd.php?action=Info&info=telephone&value="+newTel,
                    type : "PUT",
                    success:function (oRep){
                    	console.log(oRep);
                    },
                    dataType: "json"
                });
		}
		<?php
	}
?>
}

function sendMail() {


	var surname = $("#surname").val().trim();
	var firstname = $("#firstname").val().trim();
	var mail = $("#mail").val().trim();
	var tel = $("#tel").val().trim();

	var ok = true;

	if (surname == "") {
		console.log("NOM PAS OK");
		ok = false;
		$("#surname").css("border", "1px solid red");
	}
	else
		$("#surname").css("border", "");

	if (firstname == "") {
		console.log("PRENOM PAS OK");
		ok = false;
		$("#firstname").css("border", "1px solid red");
	}
	else
		$("#firstname").css("border", "");

	if (mail == "" || !validateEmail(mail)) {
		console.log("MAIL PAS OK");
		ok = false;
		$("#mail").css("border", "1px solid red");
	}
	else
		$("#mail").css("border", "");

	if (tel == "") {
		console.log("TEL PAS OK");
		ok = false;
		$("#tel").css("border", "1px solid red");
	}
	else
		$("#tel").css("border", "");

	console.log(surname);
	console.log(firstname);
	console.log(mail);
	console.log(tel);

	if (ok) {

		$.ajax({
		    url: "libs/dataBdd.php",
		    data:{"action":"Compte","surname":surname,"firstname":firstname, "mail": mail, "tel": tel},
		    type : "POST",
		    success:function (){
				console.log("Nouveau compte créé");}
			});

		

		var expediteur = "decima-ne-pas-repondre";
		var email = "no-reply@decima.fr";
		var subject = "Demande d'ouverture de compte de " + $.trim(firstname) + " " + $.trim(surname);
		var body = "Veuillez valider ou refuser la creation du compte sur votre page administrateur";

		$.ajax({
			url: 'PHPMailer/mail.php',
			method: 'POST',
			dataType: 'json',

			data: {
				name: "decima-ne-pas-repondre",
				email: email,
				subject: subject,
				body: body,
				mailD: "benoit.blart@gmail.com"
			},

			success: function(response) {
					$("#popup").dialog("close"); // ferme la pop up 
	               	$("#popup").remove(); // supprime la pop up
					$("#newAccount").replaceWith("<div id='demandOK'>Votre demande a été prise en compte</div>");
			},

			error: function(response) {	
				console.log(response);
			}
		});
	}

}

function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,6})?$/;
  return emailReg.test( $email );
}


function forgotPassword() {

	var mail = $("#mailInput").val();

	if (validateEmail(mail)) {

		$.ajax({
		    url: "libs/dataBdd.php",
		    data:{"action":"CompteExiste","mail": mail},
		    type : "GET",
		    success:function (compteExiste) {

		    	console.log(compteExiste);

		    	if (compteExiste == '"1"') {
		    	var alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		    	var word = "";
		    	var letter = '';

		    	for (var i = 0; i < 6; i++) {
					letter = alphabet[Math.floor((Math.random() * alphabet.length))];
					word += letter;
					
				}

				console.log(word);

				var expediteur = "decima-ne-pas-repondre";
				var email = "no-reply@decima.fr";
				var subject = "Mot de passe oublié";
				var body = "Voici le code permettant de vous connecter à votre compte Décima : " + word;

				$.ajax({
					url: 'PHPMailer/mail.php',
					method: 'POST',
					dataType: 'json',

					data: {
						name: "decima-ne-pas-repondre",
						email: email,
						subject: subject,
						body: body,
						mailD: "zakirio2727@gmail.com"
					},

					success: function(response) {

						var nbEssais = 3;
						$("#Envoyer").click(
											function() { 
												console.log($("#mailInput").val());
												console.log(word);

												nbEssais--;
												if (nbEssais) {
													
													if ( $("#mailInput").val() == word ) {
															$.ajax({
															    url: "libs/dataBdd.php",
															    data:{"action":"CompteByMail","mail": mail},
															    type : "GET",
															    dataType: "json",
															    success:function (oRep) {
															    	console.log("oRep")
															    	console.log(oRep);

															    	var login = oRep[0].mail;
															    	var passe = oRep[0].mdp;
															    	// connexion

															    	console.log("login");
															    	console.log(login)
															    	console.log("passe");
															    	console.log(passe);

																	$.ajax({
													                url: "libs/dataBdd.php",
													                data:{"action":"Connexion","login":login,"passe":passe,"remember":0},
													                type : "GET",
													               
													                success:function (oRep) {
													                	console.log("reponseConnexion")
																	 	console.log(oRep);
																	 	document.location.href="./index.php?view=catalogue";
																	 	alert("Vous avez été reconnecté à votre compte. Nous vous recommandons vivement de changer votre mot de passe dans la section Connexion/Compte");
																
																 		},
																	error : function(jqXHR, textStatus) {
																		console.log(textStatus);
																	$("#erreur").html("Code incorrect").show();
																	 
																		},
																	dataType: "json"
																	});
												}

								   		// fin connexion


																});
															} // fin ajax 3
															else {
																$("#erreur").html("Code incorrect (" + nbEssais + " essais restants)").show();
															}
												}
												else
												{
													document.location.href="./index.php?view=connexion";
												}
											}
										);

						$("#veuillez").html("Veuillez entrer le code envoyé par mail :");
						$("#mailInput").val("");



							
					}

				}); // fin ajax 2
			
			// permet de concaténer la lettre affichée au mot pour ensuite le comparer lors de la saisie utilisateur
				}
			else
				$("#erreur").html("Il n'existe pas de compte associé à cette adresse").show();
			}
	}); // fin ajax 1
	}

	else
		$("#erreur").html("Cette adresse mail n'est pas valide").show();

}

</script>

<body onload="createButton();">
<?php

if ($connecte)
{
?>
	<br/><br/>
	<div id="compte">
			<h1 class="my-4">Mon compte</h1>
			<h4>Mes informations</h4><br/>
			Mot de passe : <input type="password" id="passe"  value="<?php echo $passe;?>"/><br/><br/>
			Mail : <input type="text" id="mail" value="<?php echo $mail;?>"/><br/><br/>
			Tél : <input type="text" id="tel"  value="<?php echo $tel;?>"/><br/><br/>
			<input type="submit" name="action" value="Valider" onclick="updateInfos('<?php echo $_SESSION["idUser"];?>');"/><br/><br/>
	</div>
	<br/><br/>

<?php
}
else
{
?>
	<br/><br/>
	<div id="erreur"></div>
	<div>
	<div id="connexion">
		<h1 class="my-4" id="connexionLabel">Mot de passe oublié</h1>
		<p id="veuillez">Veuillez entrer votre adresse mail (un code permettant de se connecter vous sera envoyé) :</p>
		<input type="text" id="mailInput" value=""/><br/>
		<br/>
		<input type="submit" id="Envoyer" name="action" value="Envoyer" onclick="forgotPassword();"/><br/><br/>
	</div>
	<br/>
	<div id="seConnecter" style="text-align:center"><a href="./index.php?view=connexion">Se connecter</a></div>
	<div id="newAccount"><a href="#" onclick="createPopUp();">Demander l'ouverture d'un compte</a></div>
</div>

  

</body>

</html>
<?php
}
?>
