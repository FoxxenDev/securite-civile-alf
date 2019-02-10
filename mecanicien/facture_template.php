<html>
	<head>
		<meta charset="utf-8">
		<title>Facture Sécurité Civile</title>
		<link rel="stylesheet" href="<?= realpath('css/facture.css') ?>">
		<link href="https://fonts.googleapis.com/css?family=Lato|Lobster|Open+Sans" rel="stylesheet">
		<link rel="icon" href="img/logo.ico">	
	</head>

	<body>
		<h1>Facture</h1>
		
		<img class="img_logo" src="<?= realpath("img/logo.png") ?>" width="100px" height="100px">
		
		<div class="address address-from">
			<div class="address_label">De</div>
			<div class="address_content">
				<strong>Sécurité Civile</strong><br>
				138 186 CSP Athira<br>
			</div>
		</div>
		
		<div class="address address-to">
			<div class="address_label">Pour</div>
			<div class="address_content">
				<strong><?= $client ?></strong><br>
				<?= $adresse ?><br>
				<?= $ville ?><br>
			</div>
		</div>
		
		<div class="infos">
			<div class="infos_label">Facture n°</div>
			<div class="infos_content"><strong><?= $number ?></strong></div>
			<div class="infos_label">Emise le</div>
			<div class="infos_content"><?= date("d/m/Y") ?></div>
		</div>
		
		<div class="cb"></div>
		
		<table class="items">
			<thead>
				<tr>
					<th>Description</th>
					<th style="width: 150px">Prix HT</th>
					<th style="width: 150px">Prix TTC</th>
				</tr>
			</thead>
			<tbody>
			<?php for($i = 0; $i < (count($_POST)-4)/2; $i++): 
				$total_ht = $total_ht + $_POST["prix_ht".$i];
				$total_ttc = $total_ttc + round(($_POST["prix_ht".$i]*5)/100)+$_POST["prix_ht".$i];
				$taxe = $total_ttc - $total_ht;
			?>
				<tr>
					<td><?= $_POST["description".$i] ?></td>
					<td><?= number_format($_POST["prix_ht".$i], 0, "", " ") ?> €</td>
					<td><?= number_format(round(($_POST["prix_ht".$i]*5)/100)+$_POST["prix_ht".$i], 0, "", " ") ?> €</td>
				</tr>
			<?php endfor; ?>
				<tr class="total total-first">
					<td colspan="2" class="total_label">Sous-total HT</td>
					<td><strong><?= number_format($total_ht, 0, "", " ") ?> €</strong></td>
				</tr>
				<tr class="total">
					<td colspan="2" class="total_label">Taxe 5% </td>
					<td><strong><?= number_format($taxe, 0, "", " ") ?> €</strong></td>
				</tr>
				<tr class="total total-big">
					<td colspan="2" class="total_label">Total</td>
					<td><strong><?= number_format($total_ttc, 0, "", " ") ?> €</strong></td>
				</tr>
			</tbody>
		</table>
		
		<div class="cb"></div>
		
		<br><br><br>
		
		<div class="signature chef">
			<div class="signature_label">Chef Mécanicien</div>
			<div class="signature_content">T. Williams</div>
		</div>
		<div class="signature mecanicien">
			<div class="signature_label">Mécanicien</div>
			<div class="signature_content"><?= $mecanicien ?></div>
		</div>
		<div class="signature client">
			<div class="signature_label">Client</div>
			<div class="signature_content"><?= $client ?></div>
		</div>
		<div class="cb"></div><br>
	<div class="footer">
			Sécurité Civile - Service gouvernemental
		</div>
		
	</body>
</html>