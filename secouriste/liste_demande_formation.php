<?php

require_once("../inc/functions.php");

if(!isConnected()){header("Location: ../login.php");$_SESSION['flash']['danger'] = "Vous devez être connecté pour accéder à cette page !";exit();}

if(!isRank("Formateur") && !isRank("Admin site")){header("Location: ../index.php");$_SESSION['flash']['danger'] = "Vous n'avez pas l'autorisation d'accéder à cette page !";exit();}

require("../inc/header.php");
require_once("../inc/bdd.php");

$req = $bdd->query("SELECT formation.*, user.username FROM formation LEFT JOIN user ON user.id=formation.user_id ORDER BY formation.id DESC");

?>

<div class="row">
	<div class="col-xs-12">
		<table id="absence" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Nom du secouriste</th>
					<th>Type de formation</th>
					<th>Date de demande</th>
				</tr>
			</thead>
			<tbody>
				<?php while($result = $req->fetch()): ?>
				<tr>
					<th>
						<?= $result->username ?>
					</th>
					<td>
						<?= $result->name ?>
					</td>
					<td>
						<?= date("d-m-Y", strtotime($result->date)) ?>
					</td>
				</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->

<?php require("../inc/footer.php"); ?>

<script>
$(function () {
    $('#absence').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>