<?php

	if(!empty($_GET) && !empty($_POST)){
		require_once("inc/bdd.php");
		session_start();
		$token = $_GET["token"];
		$id = $_GET["id"];
		$password = $_POST["password"];
		$password_confirm = $_POST["password2"];
		$username = $_POST["username"];
		
		$req = $bdd->prepare("SELECT token FROM user WHERE id = ?");
		$req->execute([$id]);
		$token_bdd = $req->fetch();
		
		if($token != $token_bdd->token){
			$_SESSION['flash']->danger = "Ce token n'existe pas";
			header("Location: index.php");
			exit();
		}
		
		$password_hash = password_hash($password, PASSWORD_BCRYPT);
		
		$req1 = $bdd->prepare("UPDATE user SET username = ?, password = ?, token = ? WHERE id = ?");
		$req1->execute([$username, $password_hash, NULL, $id]);
		
		$req2 = $bdd->prepare("SELECT user.id, user.password, user.username, user.email, user.rank, user.register_at, rank_sc.rank_sc_name, rank_meca.rank_meca_name, rank_meca.rank_meca_level, rank_sru.rank_sru_name, rank_sru.rank_sru_level FROM user LEFT JOIN rank_sc ON user.rank_sc_id=rank_sc.rank_sc_id LEFT JOIN rank_meca ON user.rank_meca_id=rank_meca.rank_meca_id LEFT JOIN rank_sru ON user.rank_sru_id=rank_sru.rank_sru_id WHERE id = ?");
		$req2->execute([$id]);
		$user = $req2->fetch();
		
		$rankValue = explode(";", $user->rank);
		
		$count = count($rankValue);
		unset($rankValue[$count-1]);
			
		$user->rank = $rankValue;
		
		$_SESSION['auth'] = $user;
		$_SESSION['flash']->success = "Votre compte à bien été validé, et vous êtes maintenant connecté";
		header("Location: index.php");
		exit();
			
	}

	require_once('inc/header.php'); 

?>

<div class="box">
	<div class="box-header with-border">
			<h3 class="box-title">Confirmez votre inscription</h3>
		</div>
	<div class="box-body">
<form method="post">
	<div id="usernamediv" class="form-group">
		<label for="username">Prénom + Nom RP</label>
		<input class="form-control" type="text" name="username" placeholder="Prénom + Nom RP" id="username"  aria-describedby="usernamespan">
		<span id="usernamespan" class="help-block">Le pseudo doit être du format : Prénon Nom</span>
	</div>
	<div id="passworddiv" class="form-group">
		<label for="password1">Mot de passe</label>
		<input class="form-control" type="password" name="password" placeholder="Mot de passe" id="password1">
	</div>
	<div id="password2div" class="form-group">
		<label for="password2">Confirmez votre mot de passe</label>
		<input class="form-control" type="password" name="password2" placeholder="Confirmez votre mot de passe" id="password2" aria-describedby="passwordconfirm">
		<span id="passwordconfirm" class="help-block">Les mots de passes ne correspondent pas</span>
	</div>
	<button id="submit" class="btn btn-success">Valider</button>
</form>
</div>
</div>
<?php

	require_once('inc/footer.php');

?>

<script type="text/javascript">
	$(function(){
		 
		$valid = false;
		$("#passwordconfirm").hide();
		$("#usernamespan").hide();
		
		$("#username").keyup(function(){
			
			if(!$("#username").val().match(/^\w+([\.-]?\w+)*\s\w+([\.-]?\w+)*$/)){
				
				$("#usernamediv").addClass("has-error");
				$("#usernamediv").removeClass("has-success");
				$("#usernamespan").fadeIn();
				$valid = false;
				
			}else{
				
				$("#usernamediv").addClass("has-success");
				$("#usernamediv").removeClass("has-error");
				$("#usernamespan").fadeOut();
				$valid = true;
				
			}
			
		});
		
		$("#password2").keyup(function(){
			
			if($("#password2").val() != ""){
				if($("#password2").val() != $("#password1").val()){

					$("#password2div").addClass("has-error");
					$("#password2div").removeClass("has-success");
					$("#passworddiv").addClass("has-error");
					$("#passworddiv").removeClass("has-success");
					$("#passwordconfirm").fadeIn();
					$valid = false;

				}else{
					
					$("#passwordconfirm").fadeOut();
					$("#password2div").addClass("has-success");
					$("#password2div").removeClass("has-error");
					$("#passworddiv").addClass("has-success");
					$("#passworddiv").removeClass("has-error");
					$valid = true;

				}
			}
			
		});
		
		$("#submit").click(function(){
			
			if($valid == true){
				$("#salut").fadeOut();
			}
			return $valid;
			
		});
		
	});
</script>