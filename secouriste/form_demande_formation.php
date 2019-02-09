<?php 

require_once("../inc/functions.php");

if(!isConnected()){header("Location: ../login.php");$_SESSION['flash']['danger'] = "Vous devez être connecté pour accéder à cette page !";exit();}

require_once("../inc/bdd.php");

if(!empty($_POST["id_username"]) && !empty($_POST["formation"])){
	
	$bdd->prepare("INSERT INTO formation (user_id, name, date) VALUES (?, ?, ?)")->execute([$_POST["id_username"], $_POST["formation"], date("Y-m-d", strtotime("now"))]);
	
	$req = $bdd->query("SELECT id FROM user WHERE rank LIKE '%Formateur%'");
	while($result = $req->fetch()){
		$bdd->prepare("INSERT INTO notification (user_id, icon, slug, content) VALUES (?, ?, ?, ?)")->execute([$result->id, "graduation-cap", "/secuv2/secouriste/liste_demande_formation.php", "Une nouvelle demande de formation à été posté"]);
	}
	
	header("Location: liste_demande_formation.php");
	exit();
	
}

require("../inc/header.php"); 

?>
<div class="box">
	<form method="post" id="form1" role="form">
		<div class="box-header with-border">
			<h3 class="box-title">Demander une formation</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<!-- text input -->
			<input type="hidden" name="id_username" value="<?= $_SESSION["auth"]->id ?>">
			<div class="form-group">
				<label>Nom</label>
				<input type="text" disabled id="mailInput" class="form-control" value="<?= $_SESSION["auth"]->username ?>">
			</div>
			
			<div class="form-group">
				<label for="formation">Type de formation</label>
				<select class="form-control" name="formation" id="formation">
					<option selected disabled>(Sélectionnez une formation)</option>
					<option value="Formation Mécanicien">Formation Mécanicien</option>
					<option value="Formation S.R.U">Formation S.R.U</option>
					<option value="Formation pilotage">Formation pilotage</option>
					<option value="Formation Commandement">Formation Commandement (CODIS)</option>
					<option value="Formation Formateur">Formation Formateur</option>
				</select>
			</div>
			
			<p>Une réponse vous sera fournie sur discord lorsqu'un formateur sera disponible. Ce n'est pas la peine d'harceler un formateur, cela ne ferra que ralentir votre demande</p>
			
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<button id="submit" type="submit" class="btn btn-primary">Demander ma formation</button>
		</div>
	</form>
</div>
<?php require("../inc/footer.php"); ?>