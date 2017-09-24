<?php	
	session_start();
	include("funzioni.php");
	if (isset($_SESSION["autenticato"])) {	
?>
<html>
<head>
	<title>Ricerca</title>
</head>
<body>
	<?php
		if ($_SERVER["REQUEST_METHOD"] == "GET") {	  // metodo GET: pag. chiamata per una nuova ricerca
	?>
	<h2>Ricerca studenti:</h2>
	<form <?php echo "action=\"", $_SERVER["PHP_SELF"], "\"";?> method="post">
		Matricola: <input type="text" name="matricola"/><br/>
		Cognome: <input type="text" name="cognome"/><br/>
		Nome: <input type="text" name="nome"/><br/>
		<input type="submit" value="Cerca >>">
	</form>


	<div id = "menu">
		<a href="inserimento.php">Aggiungi un nuovo studente</a><br/>
		<a href="registrazione.php">Registra un nuovo esame</a><br/>
		<a href="logout.php">Logout</a>
	</div>

	<?php
		} else {	// metodo POST: elab. e stampa del risultato della ricerca
	?>
	
	<h2>Studenti trovati:</h2>
	
	<?php
		$campi = array('matricola', 'cognome', 'nome');
		$condizioni = array();
		foreach ($campi as $campo) {
			if(isset($_POST[$campo]) && $_POST[$campo] != '') 		// se il campo è settato e non vuoto
				$condizioni[] = "$campo LIKE '%" . $_POST[$campo] . "%'";
		}
		generaStudenti($condizioni);	
	?>
	<br/>

	<div id = "menu">
		<a <?php echo "href=\"", $_SERVER["PHP_SELF"], "\"";?>>Nuova ricerca</a><br/>
		<a href="inserimento.php">Aggiungi un nuovo studente</a><br/>
		<a href="registrazione.php">Registra un nuovo esame</a><br/>
		<a href="logout.php">Logout</a>
	</div>

</body>
</html>

<?php
	}} else {		// sessione finita
		header("Location: login.php");
		exit();
	}
?>