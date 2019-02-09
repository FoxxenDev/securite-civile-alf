<?php

require_once("../inc/functions.php");

if(!isConnected()){header("Location: ../login.php");$_SESSION['flash']['danger'] = "Vous devez être connecté pour accéder à cette page !";exit();}

require_once("../inc/bdd.php");



if(!empty($_POST["id_username"]) && !empty($_POST["date_start"]) && !empty($_POST["date_end"])){
	
	$bdd->prepare("INSERT INTO absence (user_id, date_start, date_end, raison) VALUES (?, ?, ?, ?)")->execute([$_POST["id_username"], $_POST["date_start"], $_POST["date_end"], htmlentities($_POST["raison"])]);
	$id = $bdd->lastInsertId();
	$bdd->prepare("UPDATE user SET absence_id = ? WHERE id = ?")->execute([$id, $_POST["id_username"]]);
	header("Location: liste_absence.php");
	exit();
	
}

require("../inc/header.php"); 

?>
<div class="box">
	<form method="post" id="form1" role="form">
		<div class="box-header with-border">
			<h3 class="box-title">Déclarer une absence</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<!-- text input -->
			<input type="hidden" name="id_username" value="<?= $_SESSION["auth"]->id ?>">
			<div class="form-group">
				<label>Nom</label>
				<input type="text" disabled id="mailInput" class="form-control" value="<?= $_SESSION["auth"]->username ?>">
			</div>

			<div class="form-group date">
				<label>Date de début</label>
				<input class="form-control" type="text" id="date_start" name="date_start" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask="" required>
			</div>
			
			<div class="form-group date">
				<label>Date de fin</label>
				<input class="form-control" type="text" id="date_end" name="date_end" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask="" required>
			</div>
			
			<div class="form-group">
				<label>Commentaire</label>
				<textarea name="raison" class="form-control" rows="3"></textarea>
			</div>
			
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<button id="submit" type="submit" class="btn btn-primary">Poster mon absence</button>
		</div>
	</form>
</div>
<?php require("../inc/footer.php"); ?>
<!-- Page script -->
<script>
	$( function () {
		$( "#date_start" ).datepicker({
			format: "yyyy-mm-dd",
			weekStart: 1,
			language: "fr",
			orientation: "bottom",
			autoclose: true,
			startDate: new Date()
		});
		
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