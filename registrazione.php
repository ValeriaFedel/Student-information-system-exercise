<?php
	session_start();
	include("funzioni.php");
	if (isset($_SESSION["autenticato"])) {	
?>
<html>
<head>
	<title>Registrazione esame</title>
</head>
<body>
<h2>Registrazione nuovo esame:</h2>

<?php
		if ($_SERVER["REQUEST_METHOD"] == "GET") {	  // metodo GET
	?>
<form <?php echo "action=\"", $_SERVER["PHP_SELF"], "\"";?> method="post">
	Studente: <select name="studente">
				<?php 	$risposta = elencoStudenti();
						while ($riga = mysqli_fetch_array($risposta)) {
							echo "<option value=\"". $riga["matricola"] ."\">" . $riga["cognome"] . " " . $riga["nome"] . " (" . $riga["laurea"] . ")" . "</option>";
						}
				?>
			  </select><br/>
	

	Corso: <select name="corso">
			<?php   $risposta = elencoCorsi();
					while ($riga = mysqli_fetch_array($risposta)) {
						echo "<option value=\"". $riga["PK_id"] ."\">" . $riga["corso"] . " (" . $riga["laurea"] . ")" . "</option>";
						}
				?>
			  </select><br/>

	Voto: <input type="text" name="voto" maxlength="2" size="3"/> 
	Lode: <input type="checkbox" name="lode" value="1"><br/>
	Data (GG/MM/AAAA):	<input type="text" name="GG" maxlength="2" size="3"> / 
						<input type="text" name="MM" maxlength="2" size="3"> /
						<input type="text" name="AAAA" maxlength="4" size="5"><br/>
	<input type="submit" value="Registra >>" >
</form>


<?php
		} else {	// metodo POST
	?>


<h4>Esito query:</h4>

<?php
	$data = $_POST["AAAA"] . "-" . $_POST["MM"] . "-" . $_POST["GG"];
	registraEsame($_POST["studente"],$_POST["corso"],$_POST["voto"], $_POST["lode"], $data, $_SESSION["username"]);
	}
?>

<div id = "menu">
	<a href="ricerca.php">Torna al form di ricerca</a><br/>
	<a href="inserimento.php">Aggiungi un nuovo studente</a><br/>
	<a href="logout.php">Logout</a>
</div>


</body>
</html>
</body>
</html>

<?php
	} else {
		header("Location: login.php");
		exit();
	}
?>