<?php

require_once("inc/bdd.php");
$id = $_GET["id"];
$pageBefore = $_GET["pagebefore"];
$bdd->prepare("DELETE FROM notification WHERE user_id = ?")->execute([$id]);
header("Location: ".$pageBefore);
exit();