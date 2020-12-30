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

if(valider("connecte","SESSION")){
	$style="none";
} 
else $style="block";

?>
<script type="text/javascript">

function connexion() {
	var login =$("#login").val();
	var passe=$("#passe").val();
	if($("#remember").is(":checked"))var remember="1";
	else var remember="0";
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

</script>

<body>

<div id="erreur"></div>
<div style="display:<?php echo $style; ?>">
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
