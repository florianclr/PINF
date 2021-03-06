<?php
	
	include_once "libs/maLibUtils.php";
	include_once "libs/maLibSQL.pdo.php";
	include_once "libs/maLibSecurisation.php"; 
	include_once "libs/modele.php"; 
	include_once "libs/maLibForms.php";

$login = valider("login", "COOKIE");
$passe = valider("passe", "COOKIE");
  

if ($checked = valider("remember", "COOKIE")) $checked = "checked"; 

if(valider("connecte","SESSION")) {
	$connecte = 1;
	$passe = getInfo($_SESSION["idUser"], "mdp");
	$mail = getInfo($_SESSION["idUser"], "mail");
	$tel = getInfo($_SESSION["idUser"], "telephone");
} 
else 
	$connecte = 0;

?>
<!-- Bootstrap -->
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
               	getMail();  // envoi d'un mail
             },
             "Annuler": function() {
               	$(this).dialog("close"); // ferme la pop up 
               	$(this).remove(); // supprime la pop up
             },
         },
         close: function() { // lorsqu'on appuie sur la croix pour fermer la pop up
            $(this).remove(); // supprime la pop up 
         }
	}); // DOC jquery UI pop-up : https://jqueryui.com/dialog/#modal-message
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

function getMail() {

    $.ajax({
                url: "libs/dataBdd.php",
                data:{"action":"Mail"},
                type : "GET",
                success:function (oRep){
                 console.log(oRep);
                 sendMail(oRep);

             },
            error : function(jqXHR, textStatus)
            {
                console.log("#erreur");

            },
            dataType: "json"
            });

}

function sendMail(mailDestinataire) {


	var surname = $("#surname").val().trim();
	var firstname = $("#firstname").val().trim();
	var mail = $("#mail").val().trim();
	var tel = $("#tel").val().trim();

	var ok = true;

	if (surname == "") {
		// Nom invalide
		ok = false;
		$("#surname").css("border", "1px solid red");
	}
	else
		$("#surname").css("border", "");

	if (firstname == "") {
		// Prénom invalide
		ok = false;
		$("#firstname").css("border", "1px solid red");
	}
	else
		$("#firstname").css("border", "");

	if (mail == "" || !validateEmail(mail)) {
		// Mail invalide
		ok = false;
		$("#mail").css("border", "1px solid red");
	}
	else
		$("#mail").css("border", "");

	if (tel == "") {
		// Téléphone invalide
		ok = false;
		$("#tel").css("border", "1px solid red");
	}
	else
		$("#tel").css("border", "");

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
		var body = "Veuillez valider ou refuser la cr&eacute;ation du compte sur votre page administrateur.";

		$.ajax({
			url: 'PHPMailer/mail.php',
			method: 'POST',
			dataType: 'json',

			data: {
				name: "decima-ne-pas-repondre",
				email: email,
				subject: subject,
				body: body,
				mailD: mailDestinataire
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

		$("#erreur").hide();

		$.ajax({ // ajax 1 vérification de l'existence du compte
		    url: "libs/dataBdd.php",
		    data:{"action":"CompteExiste","mail": mail},
		    type : "GET",
		    success:function (compteExiste) {

		    	$("#erreur").hide();

		    	console.log(compteExiste);

		    	if (compteExiste == '"1"') {
		    	var alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		    	var word = "";
		    	var letter = '';

		    	for (var i = 0; i < 6; i++) {
					letter = alphabet[Math.floor((Math.random() * alphabet.length))];
					word += letter;
					
				}

				var expediteur = "decima-ne-pas-repondre";
				var email = "no-reply@decima.fr";
				var subject = "Code de reinitialisation de votre mot de passe Decima";
				var body = "Voici le code permettant de vous connecter &agrave; votre compte D&eacute;cima : " + word;

				$.ajax({ // ajax 2 code de validation
					url: 'PHPMailer/mail.php',
					method: 'POST',
					dataType: 'json',

					data: {
						name: "decima-ne-pas-repondre",
						email: email,
						subject: subject,
						body: body,
						mailD: mail
					},

					success: function(response) {

						var nbEssais = 3;
						$("#Envoyer").click(
											function() {

												nbEssais--;
												if (nbEssais) {
													
													if ( $("#mailInput").val() == word ) {

														$("#erreur").hide();

														var newPassword = ""

														for (var i = 0; i < 10; i++) {
															letter = alphabet[Math.floor((Math.random() * alphabet.length))];
															newPassword += letter;
														}

														$.ajax({ // ajax 3 changement du mdp dans la BDD

														url: "libs/dataBdd.php",
													    data:{"action":"resetMdp","login": mail, "newMdp": newPassword},
													    type : "POST",
													    dataType: 'json',

													    success:function (response) {

													    	$("#erreur").hide();

														    subject = "Votre nouveau mot de passe Decima";
															body = "Voici votre nouveau mot de passe permettant de vous connecter &agrave; votre compte D&eacute;cima : " + newPassword;

															$.ajax({ // ajax 4 envoi du nouveau mdp
															url: 'PHPMailer/mail.php',
															method: 'POST',
															dataType: 'json',

															data: {
																name: "decima-ne-pas-repondre",
																email: email,
																subject: subject,
																body: body,
																mailD: mail
															},

															success: function(response) {

																$("#erreur").hide();



																$("#veuillez").html("Votre mot de passe a été réinitialisé. Vous pouvez à présent vous connecter avec votre nouveau mot de passe envoyé par mail. Il sera ensuite possible de le changer dans l'onglet Connexion/Compte.");

																$("#mailInput").remove();

																$("#erreur").hide();

																$("#Envoyer").click(
																					function() {

																						document.location.replace("./index.php?view=connexion");
																					});

																$("#Envoyer").val("Revenir à la page de connexion");

																

															}
														}); // fin ajax 4


													    }

														}); // fin ajax 3


														
													}
													else 
													{
														$("#erreur").html("Code incorrect (" + nbEssais + " essais restants)").show();
													}
												}	
												else
												{
													document.location.replace("./index.php?view=connexion");
												}
											
										});

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
