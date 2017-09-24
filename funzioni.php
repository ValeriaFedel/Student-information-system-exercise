<?php
	require("configurazione.php");

	/* LOGIN.php */

	function utenteValido($username, $password) {
		$conn = dbConnect();
		$sql = "SELECT password FROM admin WHERE username = '" . addslashes($username) . "'";
		$risposta = mysqli_query($conn, $sql) or die("Errore nella query: ". $sql . "\n" . mysqli_error($conn));
		if (mysqli_num_rows($risposta) == 0)
			return FALSE;
		$riga = mysqli_fetch_row($risposta);
		mysqli_close($conn);
		return (md5($password) == $riga[0]);
	}


	/* RICERCA.php */

	function generaStudenti($condizioni){
		$conn = dbConnect();
		$query = "SELECT * FROM studenti";
		if(count($condizioni) > 0) {
        	$query .= " WHERE " . implode (' AND ', $condizioni);
    	}
    	$risposta = mysqli_query($conn, $query) or die("Errore nella query: ". $query . "\n" . mysqli_error($conn));
    	if (mysqli_num_rows($risposta) == 0)
			echo "<p>Nessuno studente trovato.<p>";
		else {
			while ($riga = mysqli_fetch_array($risposta)) {
				 echo "<a href= \"libretto.php?matricola=". $riga["matricola"] . "\">" . $riga["cognome"] . " " . $riga["nome"] . "</a></br>";		// stampa cognome e nome con link a: libretto.php?matricola=(n. matricola)
			}
		}
		mysqli_close($conn);	
	}


	/* LIBRETTO.php */

	function generaInfoGenerali($matricola) {
		$conn = dbConnect();
		$sql = "SELECT studenti.nome, cognome, data_nascita, matricola, lauree.nome as laurea FROM studenti inner join lauree on lauree.PK_id = studenti.FK_laurea WHERE matricola='$matricola'";
		$risposta = mysqli_query($conn, $sql) or die("Errore nella query: ". $sql . "\n" . mysqli_error($conn));
		echo "<tr>
				<th>Matricola</th>
				<th>Cognome</th>
				<th>Nome</th>
				<th>Data di nascita</th>
				<th>Laurea</th></tr>
				<tr>";
		$riga = mysqli_fetch_array($risposta);
		echo "<td>".$riga["matricola"]."</td>".
			 "<td>".$riga["cognome"]."</td>".
			 "<td>".$riga["nome"]."</td>".
			 "<td>".$riga["data_nascita"]."</td>".
			 "<td>".$riga["laurea"]."</td></tr>";
		mysqli_close($conn);	
	}


	function generaEsamiSostenuti($matricola) {
		$conn = dbConnect();
		$sql = "SELECT  corsi.nome as corso,
						corsi.cfu as cfu,
						esami.voto,
						esami.lode,
						esami.data,
						lauree.nome as laurea,
						admin.nome,
						admin.cognome,
						docenti.nome as nome_doc,
						docenti.cognome as cognome_doc

				FROM (((((esami inner join corsi
				on corsi.PK_id=esami.FK_corso)
				inner join studenti on studenti.matricola=esami.FK_studente) 
				inner join lauree on lauree.PK_id=corsi.FK_laurea)
				inner join admin on admin.PK_id=esami.FK_admin)
				right join docenti on docenti.PK_id=corsi.FK_docente1)
				
				WHERE studenti.matricola='$matricola'";
	
		$risposta = mysqli_query($conn, $sql) or die("Errore nella query: ". $sql . "\n" . mysqli_error($conn));

		if (mysqli_num_rows($risposta) == 0)
			echo "<p>Nessun esame sostenuto.<p>";
		else {
			echo "<tr>
				<th>Corso</th>
				<th>Docente</th>
				<th>Laurea</th>
				<th>CFU</th>
				<th>Voto</th>
				<th>Data</th>
				<th>Registrato da</th></tr>";

			while ($riga = mysqli_fetch_array($risposta)) {
				if ($riga["lode"] == 1) 
					$riga["lode"] = "e lode";
				else
					$riga["lode"] = "";
				echo "<tr><td>".$riga["corso"]."</td>".	// corso
					 "<td>".$riga["cognome_doc"]." ".$riga["nome_doc"]."</td>".	// docente
					 "<td>".$riga["laurea"]."</td>".	// laurea
					 "<td>".$riga["cfu"]."</td>".	// CFU
					 "<td>".$riga["voto"]." ".$riga["lode"]."</td>".	// voto
					 "<td>".$riga["data"]."</td>".	// data
					 "<td>".$riga["cognome"]." ".$riga["nome"]."</td></tr>"; //	registrato da
			}
		}
		mysqli_close($conn);	
	}



	/* INSERIMENTO.php */
	
	function registraStudente($matricola,$cognome,$nome,$data_nascita,$laurea) {
		$conn = dbConnect();
		$sql = "INSERT INTO studenti(matricola, FK_laurea, nome, cognome, data_nascita)
				VALUES ('" . $matricola . "', '" . $laurea . "', '" . $nome . "', '" . $cognome . "', '" . $data_nascita . "')";
		$risposta = mysqli_query($conn, $sql);
		if ($risposta) {
			echo "Query eseguita con successo<br/><br/>";
		} else
			die("Errore nella query: ". $sql . "\n" . mysqli_error($conn));
		mysqli_close($conn);
	}



	/* REGISTRAZIONE.php */

	function elencoStudenti() {
		$conn = dbConnect();
		$sql = "SELECT studenti.cognome, studenti.nome, studenti.matricola, lauree.nome as laurea 
				FROM studenti inner join lauree on lauree.PK_id=studenti.FK_laurea";
		$risposta = mysqli_query($conn, $sql) or die("Errore nella query: ". $sql . "\n" . mysqli_error($conn));
		mysqli_close($conn);
		return $risposta;		
	}

	function elencoCorsi() {
		$conn = dbConnect();
		$sql = "SELECT corsi.nome as corso, corsi.PK_id, lauree.nome as laurea 
				FROM corsi inner join lauree on lauree.PK_id=corsi.FK_laurea";
		$risposta = mysqli_query($conn, $sql) or die("Errore nella query: ". $sql . "\n" . mysqli_error($conn));
		mysqli_close($conn);
		return $risposta;
	}

	function registraEsame($studente, $corso, $voto, $lode, $data, $username) {
		$conn = dbConnect();
		$sql = "SELECT PK_id FROM admin WHERE username='" . $username . "'";
		$risposta = mysqli_query($conn, $sql) or die("Errore nella query: ". $sql . "\n" . mysqli_error($conn));
		$riga = mysqli_fetch_row($risposta);
		$sql = "INSERT INTO esami(FK_corso, FK_studente, voto, lode, data, FK_admin)
				VALUES ('" . $corso . "', '" . $studente . "', '" . $voto . "', '" . $lode . "', '" . $data . "', '" .$riga[0] . "')";
		$risposta = mysqli_query($conn, $sql) or die("Errore nella query: ". $sql . "\n" . mysqli_error($conn));
		if ($risposta) {
			echo "Query eseguita con successo<br/><br/>";
		} else
			die("Errore nella query: ". $sql . "\n" . mysqli_error($conn));
		mysqli_close($conn);
	}



	/* dbConnect() */

	function dbConnect() {
		global $config;
		$conn = @mysqli_connect("localhost", $config["mysql_user"], $config["mysql_pwd"]) 
			or die("Errore nella connessione al db: " . mysqli_connect_error());
		mysqli_select_db($conn, $config["nome_db"])
			or die("Errore nella selezione del db: " . mysqli_error($conn));
		return $conn;
	}

?>