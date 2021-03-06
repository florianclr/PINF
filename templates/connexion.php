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
    			.append($("<label class='champ'>Mail :</label><input type='email' id='email'></br>"))
				.append($("<label class='champ'>Téléphone :</label><input type='text' id='tel'></br>"));
				
var popupMdp = $("<div id='popupM' title='Changer le mot de passe'>")
                .append($('<label class="champ">Ancien mot de passe :</label><input type="password" id="ancienMdp"><label for="displayPasse1" id="labelDisplayPasse1"><input type="checkbox" id="displayPasse1" onclick="Afficher();"/>Afficher</label></br>'))
                .append($('<label class="champ">Nouveau mot de passe :</label><input type="password" id="nouveauMdp"> <label for="displayPasse2" id="labelDisplayPasse2"><input type="checkbox" id="displayPasse2" onclick="Afficher();"/>Afficher</label></br>'));


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
               	emailDoublon();  // envoi d'un mail
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
		var mail = '<?php echo $mail;?>';
		var tel = '<?php echo $tel;?>';

		var newMail = $.trim($("#email").val());
		var newTel = $.trim($("#tel").val());

		if (mail != newMail)
		{
			$.ajax({
                    url: "libs/dataBdd.php?action=Info&info=mail&value="+newMail,
                    type : "PUT",
                    success: function (oRep) {
                    	console.log(oRep);
			$("#modifOK").remove();
                    	$("#compte").before("<div id='modifOK'>Les changements ont bien été effectués</div>");
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
			$("#modifOK").remove();
                    	$("#compte").before("<div id='modifOK'>Les changements ont bien été effectués</div>");
                    },
                    dataType: "json"
                });
		}
		<?php
	}
?>
}

function sendMail(mailDestinataire) {


	var surname = $("#surname").val().trim();
	var firstname = $("#firstname").val().trim();
	var mail = $("#email").val().trim();
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
		$("#email").css("border", "1px solid red");
	}
	else
		$("#email").css("border", "");

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
                console.log("erreur");

            },
            dataType: "json"
            });

}

function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,6})?$/;
  return emailReg.test( $email );
}

function emailDoublon(){
     var mail = $("#email").val().trim();
     $.ajax({
                url: "libs/dataBdd.php",
                data:{"action":"VerifMail","mail":mail},
                type : "GET",
                success:function (oRep){
                 console.log(oRep);
                 if(oRep==false){
                     getMail();
                 }
                 else{
                 	$("#email").css("border", "1px solid red");
                    $("#popup").append("<div id='erreurMail'>Cette adresse mail est déjà prise</div>");
                    $("#erreurMail").show();
                 }
             },
            error : function(jqXHR, textStatus)
            {
                console.log("erreur");
            },
            dataType: "json"
            });
}

function changerMdp() {
	$("#modifOK").remove(); 
    $("#compte").append(popupMdp.clone(true));
    $("#popupM").dialog({
         modal: true, // permet de rendre le reste de la page inaccesible tant que la pop up est ouverte
         height: 330,
         width: 400,
         buttons: { // on ajoute des boutons à la pop up 
             "Changer": function(){
             	oldMdp = $("#ancienMdp").val();
				newMdp = $("#nouveauMdp").val();
             	$.ajax({
                    url: "libs/dataBdd.php?action=changeMdp&oldMdp="+ oldMdp + "&newMdp=" + newMdp ,
                    type : "PUT",
                    success: function (oRep){
                    	console.log(oRep);
						$("#compte").before("<div id='modifOK'>Mot de passe changé</div>");
                    },
                    error: function(oRep){
                    	$("#compte").before("<div id='modifOK'>Ancien ou nouveau mot de passe invalide</div>"); 
                    },
                    dataType: "json"
                });
                $(this).dialog("close"); // ferme la pop up 
                $(this).remove(); // supprime la pop up
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

function Afficher() {

    if ($("#displayPasse1").prop("checked") == true)
            $("#ancienMdp").attr("type", "text");
        else if ($("#displayPasse1").prop("checked") == false)
            $("#ancienMdp").attr("type", "password");

        if ($("#displayPasse2").prop("checked") == true)
            $("#nouveauMdp").attr("type", "text");
        else if ($("#displayPasse2").prop("checked") == false)
            $("#nouveauMdp").attr("type", "password");

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
            <h4>Modifier mes informations</h4><br/>
            <input type="submit" value="Modifier le mot de passe" onclick="changerMdp();"/>
            <br/><br/>
            Mail : <input type="text" id="email" value="<?php echo $mail;?>"/><br/><br/>
            Tél : <input type="text" id="tel"  value="<?php echo $tel;?>"/><br/><br/>
            <input type="submit" name="action" value="Valider" onclick="updateInfos('<?php echo $_SESSION["idUser"];?>');"/><br/><br/>
    </div>
	<br/><br/><br/><br/><br/><br/><br/><br/><br/>

<?php
}
else
{
?>
	<br/><br/>
	<div id="erreur"></div>
	<div>
	<div id="connexion">
		<h1 class="my-4" id="connexionLabel">Connexion</h1>
		Login : <input type="text" id="login"  value="<?php echo $login;?>"/><br/><br/>
		Mot de passe : <input type="password" id="passe" value="<?php echo $passe;?>"/>
		<br/><br/>
		<div id="forgotPassword"><a href="index.php?view=forgotPassword">Mot de passe oublié</a></div><br/>
		<input type="checkbox" <?php echo $checked;?> name="remember" id="remember" value="ok"/>
		<label for="remember">Se souvenir de moi</label>
		<br/><br/>
		<input type="submit" name="action" value="Se connecter" onclick="connexion();"/><br/><br/>
	</div>

	<div id="newAccount"><a href="#" onclick="createPopUp();">Demander l'ouverture d'un compte</a></div>
</div>

  

</body>

</html>
<?php
}
?>
