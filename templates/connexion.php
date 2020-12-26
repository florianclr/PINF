<?php
	include_once "libs/maLibUtils.php";
	include_once "libs/maLibSQL.pdo.php";
	include_once "libs/maLibSecurisation.php"; 
	include_once "libs/modele.php"; 
	include_once "libs/maLibForms.php";

$login = valider("login", "COOKIE");
$passe = valider("passe", "COOKIE");


//print_r($_COOKIE);  

if ($checked = valider("remember", "COOKIE")) $checked = "checked"; 


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
				$("#erreur").html("erreur");
				 
			},
			dataType: "json"
			});
}	

</script>

<body>
 
 <h1 class="my-4">Connexion</h1>

<div id="erreur"></div>

 <div id="connexion">
Login : <input type="text" id="login"  value="<?php echo $login;?>"/><br />
Passe : <input type="password" id="passe" value="<?php echo $passe;?>"  /><br />
<label for="remember">Se souvenir de moi </label><input type="checkbox" <?php echo $checked;?> name="remember" id="remember" value="ok"/> <br />
<input type="submit" name="action" value="Connexion" onclick="connexion();" />
</div>
        

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
