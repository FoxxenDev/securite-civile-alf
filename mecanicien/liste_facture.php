<?php
require_once("../inc/functions.php");
require_once("../inc/bdd.php");

if(!isRank("Mécanicien") && !isRank("Chef-Mécanicien") && !isRank("État-Major") && !isRank("Admin site")){header("Location: ../index.php");$_SESSION['flash']['danger'] = "Vous n'avez pas l'autorisation d'accéder à cette page !";exit();}


if(isset($_GET) && !empty($_GET)){
	if($_GET['action'] == "validate" && $_GET['id'] != null){
		session_start();
		$_SESSION['flash']['success'] = "La facture n°".$_GET['id']." à bien été validé";
		$bdd->prepare("UPDATE facture_meca SET validate = 1 WHERE id = ?")->execute([$_GET['id']]);
		header('Location: liste_facture.php');
		exit();
	}elseif($_GET['action'] == "supp" && $_GET['id'] != null && $_GET['name'] != null){
		session_start();
		$_SESSION['flash']['success'] = "La facture n°".$_GET['id']." à bien été supprimé";
		$bdd->prepare("DELETE FROM facture_meca WHERE id = ?")->execute([$_GET['id']]);
		unlink("pdf_facture_meca/".$_GET['name']);
		header('Location: liste_facture.php');
		exit();		
	}
}

require('../inc/header.php');

$req = $bdd->query("SELECT * FROM facture_meca");
?>

<h2>Liste des factures</h2>
    <div class="row">
        <div class="col-xs-12">
            <table id="facture" class="table table-bordered table-striped">
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
                        <td><a href="pdf_facture_meca/<?= $result->name ?>">Voir</a><?php if($result->validate == 0 && isRank("Chef-Mécanicien")){?> | <a href="liste_facture.php?action=validate&id=<?= $result->id ?>"><img width="16px" height="16px" src="../dist/img/check-mark.png" /></a> | <a href="liste_facture.php?action=supp&id=<?= $result->id ?>&name=<?= $result->name ?>"><img width="16px" height="16px" src="../dist/img/remove-symbol.png" /></a><?php } ?></td>
                    </tr>
                    <?php }; ?>
                </tbody>
            </table>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
<a href="form_facture_new.php" class="btn btn-primary">Facturer un client</a>

<?php
require("../inc/footer.php");
?>

<!-- page script -->
<script>
    $( function () {
        $( '#facture' ).DataTable()
    } )
</script>
