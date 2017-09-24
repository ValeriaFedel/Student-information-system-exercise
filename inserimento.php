<?php
	session_start();
	include("funzioni.php");
	if (isset($_SESSION["autenticato"])) {	
?>
<html>
<head>
	<title>Inserimento studente</title>
</head>
<body>
<h2>Inserimento nuovo studente:</h2>

<?php
		if ($_SERVER["REQUEST_METHOD"] == "GET") {	  // metodo GET
	?>
<form <?php echo "action=\"", $_SERVER["PHP_SELF"], "\"";?> method="post">
	Matricola: <input type="text" name="matricola"/><br/>
	Cognome: <input type="text" name="cognome"/><br/>
	Nome: <input type="text" name="nome"/><br/>
	Data di nascita (GG/MM/AAAA): <input type="text" name="GG" maxlength="2" size="3"> / 
								  <input type="text" name="MM" maxlength="2" size="3"> / 
								  <input type="text" name="AAAA" maxlength="4" size="5"> <br/>
	Laurea: <select name="laurea">
				<option value="1">Informatica</option>
				<option value="2">TWM</option>
			</select><br/>
	<input type="submit" value="Aggiungi >>" >
</form>


<?php
		} else {	// metodo POST
	?>

<h4>Esito query:</h4>

<?php
	$data_nascita = $_POST["AAAA"] . "-" . $_POST["MM"] . "-" . $_POST["GG"];
	registraStudente($_POST["matricola"],$_POST["cognome"], $_POST["nome"], $data_nascita, $_POST["laurea"]);
	}
?>

<div id = "menu">
	<a href="ricerca.php">Torna al form di ricerca</a><br/>
	<a href="registrazione.php">Registra un nuovo esame</a><br/>
	<a href="logout.php">Logout</a>
</div>


</body>
</html>

<?php
	 } else {
		header("Location: login.php");
		exit();
	}
?>