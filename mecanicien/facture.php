<?php

require('header.php');

if(isset($_GET) && !empty($_GET)){
	if($_GET['action'] == "validate" && $_GET['id'] != null){
		session_start();
		$_SESSION['flash']['success'] = "La facture n°".$_GET['id']." à bien été validé";
		$bdd->prepare("UPDATE pdf_facture_meca SET validate = 1 WHERE id = ?")->execute([$_GET['id']]);
		header('Location: facture.php');
		exit();
	}elseif($_GET['action'] == "supp" && $_GET['id'] != null && $_GET['name'] != null){
		session_start();
		$_SESSION['flash']['success'] = "La facture n°".$_GET['id']." à bien été supprimé";
		$bdd->prepare("DELETE FROM pdf_facture_meca WHERE id = ?")->execute([$_GET['id']]);
		unlink("pdf_facture_meca/".$_GET['name']);
		header('Location: facture.php');
		exit();		
	}
}

$req = $bdd->query("SELECT * FROM pdf_facture_meca");
?>

<h2>Liste des factures</h2>
<table class="table table-striped">
	<thead>
		<tr>
			<th>N°</th>
			<th>Non client</th>
			<th>Nom document</th>
			<th>Validée</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php while($result = $req->fetch()){ ?>
		<tr>
			<th scope="row"><?= $result->id ?></th>
			<th><?= $result->client ?></th>
			<td><?= $result->name ?></td>
			<td><?php if($result->validate == 0){echo "Non";}else{echo "Oui";} ?></td>
			<td><a href="pdf_facture_meca/<?= $result->name ?>">Voir</a><?php if($result->validate == 0 && getLevel(meca) == 4){?> | <a href="facture.php?action=validate&id=<?= $result->id ?>"><img width="16px" height="16px" src="../img/check-mark.png" /></a> | <a href="facture.php?action=supp&id=<?= $result->id ?>&name=<?= $result->name ?>"><img width="16px" height="16px" src="../img/remove-symbol.png" /></a><?php } ?></td>
		</tr>
		<?php }; ?>
	</tbody>	
</table>
<a href="facture_new.php" class="btn btn-primary" style="width: 100%">Facturer un client</a>

<?php
require("footer.php");
?>