<?php

require_once("../inc/functions.php");

if(!isConnected()){header("Location: ../login.php");$_SESSION['flash']['danger'] = "Vous devez être connecté pour accéder à cette page !";exit();}

require("../inc/header.php");
require_once("../inc/bdd.php");

$req = $bdd->query("SELECT absence.*, user.username FROM absence LEFT JOIN user ON user.id=absence.user_id ORDER BY absence.id DESC");

?>

<div class="row">
	<div class="col-xs-12">
		<table id="absence" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Nom</th>
					<th>Date de début</th>
					<th>Date de fin</th>
					<th>Commentaire</th>
				</tr>
			</thead>
			<tbody>
				<?php
				
					while($result = $req->fetch()): 
					$date_start = strtotime($result->date_start);
					$date_end = strtotime($result->date_end);
					$date_now = strtotime("now");
				
				?>
				<tr>
					<th>
						<?= $result->username ?>
					</th>
					<td <?php if($date_now >= $date_start){echo 'style="background-color: rgb(0, 128, 0);"';} ?>>
						<?= date("d-m-Y", $date_start) ?>
					</td>
					<td <?php if($date_now >= $date_end){echo 'style="background-color: rgb(0, 128, 0);"';} ?>>
						<?= date("d-m-Y", $date_end) ?>
					</td>
					<td>
						<?= $result->raison ?>
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