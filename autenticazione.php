<?php
 	session_start();
 	error_reporting(E_ALL);
 	require("funzioni.php");

 	$username = $_POST["username"];
 	$password = $_POST["password"]; 

 	if (utenteValido($username, $password)) {
 		$_SESSION["autenticato"] = TRUE;
 		$_SESSION["username"] = $username;
 		header("Location: ricerca.php");
 		exit();
 	} else {
 		echo "Accesso fallito";
 	}
?>