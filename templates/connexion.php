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
<script type="text/javascript">

function connexion() {
	var login =$("#login").val();
	var passe=$("#passe").val();
	if($("#remember").is(":checked"))
		var remember="1";
	else 
		var remember="0";
	console.log(remember);

	$.ajax({
                url: "libs/dataBdd.php",
                data:{"action":"Connexion","login":login,"passe":passe,"remember":remember},
                type : "GET",
                success:function (oRep){
			 	console.log(oRep);
			 	document.location.href="./index.php";
			
			 },
			error : function(jqXHR, textStatus)
			{
				$("#erreur").html("Login ou mot de passe incorrect").show();
				 
			},
			dataType: "json"
			});
}

function updateInfos(idUser) {
	<?php
	if ($connecte)
	{
		?>
		var passe = '<?php echo json_encode($passe);?>';
		var mail = '<?php echo json_encode($mail);?>';
		var tel = '<?php echo json_encode($tel);?>';

		var newPasse = $.trim($("#passe").val());
		var newMail = $.trim($("#mail").val());
		var newTel = $.trim($("#tel").val());

		console.log(newPasse);
		console.log(newMail);
		console.log(newTel);

		if (passe != newPasse)
		{
			$.ajax({
	                url: "libs/dataBdd.php",
	                data:{"action":"Info","info":"mdp","value":newPasse},
	                type : "GET",
	                success:function (){
					console.log("Mot de passe changé");}
				});
		}

		if (mail != newMail)
		{
			$.ajax({
	                url: "libs/dataBdd.php",
	                data:{"action":"Info","info":"mail","value":newMail},
	                type : "GET",
	                success:function (oRep){
					console.log(oRep);}
				});
		}

		if (tel != newTel)
		{
			$.ajax({
	                url: "libs/dataBdd.php",
	                data:{"action":"Info","info":"telephone","value":newTel},
	                type : "GET",
	                success:function (){
					console.log("Tél changé");}
				});
		}
		<?php
	}
?>
}

</script>

<body>

<?php

if ($connecte)
{
?>
	<div id="compte">
			<h1 class="my-4">Mon compte</h1>
			<h5>Vos informations</h5><br/><br/>
			Mot de passe : <input type="password" id="passe"  value="<?php echo $passe;?>"/><br/><br/>
			Mail : <input type="text" id="mail" value="<?php echo $mail;?>"/><br/><br/>
			Tél : <input type="text" id="tel"  value="<?php echo $tel;?>"/><br/><br/>
			<input type="submit" name="action" value="Valider" onclick="updateInfos('<?php echo $_SESSION["idUser"];?>');"/><br/><br/>
		</div>

<?php
}
else
{
?>
	<div id="erreur"></div>
	<div>
		<div id="connexion">
			<h1 class="my-4">Connexion</h1>
			Login : <input type="text" id="login"  value="<?php echo $login;?>"/><br/><br/>
			Mot de passe : <input type="password" id="passe" value="<?php echo $passe;?>"/><br/><br/><br/>
			<input type="checkbox" <?php echo $checked;?> name="remember" id="remember" value="ok"/>
			<label for="remember">Se souvenir de moi</label>
			<br/><br/>
			<input type="submit" name="action" value="Se connecter" onclick="connexion();"/><br/><br/>
		</div>
		<div id="newAccount"><a href="#">Demander l'ouverture d'un compte</a></div>
	</div>


	


  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
<?php
}
?>