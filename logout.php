<?php
session_start();
unset($_SESSION['auth']);
setcookie("remember", NULL, -1, NULL, NULL);
$_SESSION['flash']['success'] = "Vous êtes maintenant déconnecter";
header("Location: index.php");
?>