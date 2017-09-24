<?php
	session_start();
	include("funzioni.php");
	if (isset($_SESSION["autenticato"])) {	
		// uso $_GET["matricola"] per ottenere le info su un certo studente
?>
<html>
<head>
	<title>Libretto</title>
</head>
<body>

<h2>Informazioni generali</h2>
<table border="1">
<?php
	generaInfoGenerali($_GET["matricola"]);
?>
</table>

<h2>Esami sostenuti</h2>
<table border="1">
<?php
	generaEsamiSostenuti($_GET["matricola"]);
?>
</table>

<br/>
	<div id = "menu">
		<a href="ricerca.php">Nuova ricerca</a><br/>
		<a href="inserimento.php">Aggiungi un nuovo studente</a><br/>
		<a href="registrazione.php">Registra un nuovo esame</a><br/>
		<a href="logout.php">Logout</a>
	</div>

<?php
	} else {
		header("Location: login.php");
		exit();
	}
?>
</body>
</html>