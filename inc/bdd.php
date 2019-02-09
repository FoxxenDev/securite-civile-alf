<?php

//$bdd = new PDO('mysql:host=127.0.0.1;dbname=secu', 'root', '');
$bdd = new PDO('mysql:host=127.0.0.1;dbname=secuv2', 'root', '');
//$bdd = new PDO('mysql:host=localhost;dbname=secu', 'root', 'foxxenpils');
//$bdd = new PDO('mysql:host=localhost;dbname=secuv2', 'root', 'foxxenpils');
//$bdd = new PDO('mysql:host=51.75.124.125;dbname=secu', 'testlocal2', 'foxxenpils');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);