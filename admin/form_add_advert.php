<?php

require_once("../inc/functions.php");
require_once("../inc/bdd.php");

if(!isRank("État-Major") && !isRank("Admin site")){header("Location: ../index.php");$_SESSION['flash']['danger'] = "Vous n'avez pas l'autorisation d'accéder à cette page !";exit();}

if(!empty($_POST["username"]) && !empty($_POST["raison"])){
	
	$color = explode(":", $_POST["type"])[1];
	$type = explode(":", $_POST["type"])[0];
		
	$bdd->prepare("INSERT INTO advert (user_id_to, user_id_from, type, color, raison, date_start, date_end) VALUES (?, ?, ?, ?, ?, ?, ?)")->execute([$_POST["user_to"], $_POST["username"], $type, $color, $_POST["raison"], date("Y-m-d", strtotime("now")), $_POST["date_end"]]);
	
	$bdd->prepare("UPDATE user SET advert_id = ? WHERE id = ?")->execute([$bdd->lastInsertId(), $_POST["user_to"]]);
	
	header("Location: liste_advert.php");
	exit();
	
}

$req = $bdd->query("SELECT id, username FROM user");

require("../inc/header.php"); 

?>
<div class="box">
	<form method="post" id="form1" role="form">
		<div class="box-header with-border">
			<h3 class="box-title">Sanctionner un membre</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<!-- text input -->
			<input type="hidden" name="username" value="<?= $_SESSION["auth"]->id ?>">
			<div class="form-group">
				<label>Nom</label>
				<input type="text" disabled id="mailInput" class="form-control" value="<?= $_SESSION["auth"]->username ?>">
			</div>
			
			<div class="form-group">
				<label for="type">Type de sanction</label>
				<select class="form-control" name="type" id="type">
					<option selected disabled>(Sélectionnez une sanction)</option>
					<option style="background-color: #ff9900;"  value="Avertissement 1:#ff9900">Avertissement 1</option>
					<option style="background-color: #ff0000;"  value="Avertissement 2:#ff0000">Avertissement 2</option>
					<option style="background-color: #cc4125;"  value="Dégrade:#cc4125">Dégrade</option>
					<option style="background-color: #990000;"  value="Mise à pieds:#990000">Mise à pieds</option>
					<option style="background-color: #660000;"  value="Viré:#660000">Viré</option>
				</select>
			</div>
			
			<div class="form-group">
				<label for="user_to">Donné à</label>
				<select id="user_to" name="user_to" class="form-control">
					<?php while($result = $req->fetch()): ?>
					<option value="<?= $result->id ?>"><?= $result->username ?></option>
					<?php endwhile; ?>
				</select>
			</div>
			
			<div class="form-group date">
				<label>Date de fin</label>
				<input class="form-control" type="text" id="date_end" name="date_end" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask="" title="Veuillez entrer une date valide !" required="">
			</div>
			
			<div class="form-group">
				<label>Raison</label>
				<textarea name="raison" class="form-control" rows="3"></textarea>
			</div>
			
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<button id="submit" type="submit" class="btn btn-primary">Sanctionner</button>
		</div>
	</form>
</div>
<?php require("../inc/footer.php"); ?>
<!-- Page script -->
<script>
	$( function () {
		
		$( "#date_end" ).datepicker({
			format: "yyyy-mm-dd",
			weekStart: 1,
			language: "fr",
			orientation: "bottom",
			autoclose: true,
			startDate: new Date()
		});
		
		$('[data-mask]').inputmask()
	} )
</script>