<?php

	require("../vendor/autoload.php");
	require_once("../inc/bdd.php");
	require_once("../inc/functions.php");

	if(isset($_POST) && !empty($_POST)){
		$reqg = $bdd->query("SELECT adress FROM general");
		$resultg = $reqg->fetch();
		$req = $bdd->query("SELECT `id` FROM `pdf_facture_meca` ORDER BY `id` DESC LIMIT 1");
		$result = $req->fetch();
		$id_old = intval($result->id);
		$id = $id_old + 1;
		$number = "";
		if($id < 10){
			$number = "00".$id;
		}elseif($id < 100 && $id >= 10){
			$number = "0".$id;
		}else{
			$number = $id;
		}
		$mecanicien = $_POST["mecanicien"];
		$client = $_POST["client"];
		$adresse = $_POST["adresse"];
		$ville = $_POST["ville"];
		$total_ht = 0;
		$total_ttc = 0;
		$taxe = 0;
		
		ob_start();
		require("facture_template.php");
		$content = ob_get_clean();
		
		$pdf = new \mikehaertl\wkhtmlto\Pdf($content);
		$name = $client."_".date("H-i-s")."_".date("d-m-Y").".pdf";
		$name = str_replace(" ", "", $name);
		//$pdf->send();
		if($pdf->saveAs("/var/www/html/pdf_facture_meca/".$name)){
			$bdd->prepare("INSERT INTO pdf_facture_meca (name, client) VALUES (?, ?)")->execute([$name, $client]);
			session_start();
			postToDiscord("Facture n° ".$id.". Nom du client : ".$client.". Lien : ".$resultg->adress."pdf_facture_meca/".$name);
			$_SESSION['flash']['success'] = "La facture à bien été généré sous le nom : ".$name;
			header("Location: liste_facture.php");
		}
	}else{
		$_SESSION['flash']['danger'] = "Vous ne pouvez pas accéder à cette page comme sa !";
		header("Location: liste_facture.php");
	}
?>