<?php 

//ottengo la connessione al DB tramithe la classe PDO di PHP
function getConnection() {

	global $db_Manage_Film_Example_db_Url, $db_Manage_Film_Example_db_User, $db_Manage_Film_Example_db_Pass, $db_Manage_Film_Example_db_DbName;
	
	try {
		$dbh = new PDO("mysql:host=".$db_Manage_Film_Example_db_Url.";dbname=".$db_Manage_Film_Example_db_DbName, $db_Manage_Film_Example_db_User, $db_Manage_Film_Example_db_Pass);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->exec("SET NAMES utf8");
	} catch (PDOException $e) {
		exit('Connection failed: ' . $e->getMessage());
	}
	
	return $dbh;
}


/*
 *  Esegue le query
 *  
 *  PARAMETRI:
 *  $sql:			Stringa della query
 *  $paramsPlain:	Parametri della query
 *  $verbose:		Se impostato a true(dafault) stampa il risultato nella request in formato JSON, altrimenti ritorna l'oggetto PHP
 */

function makeQuery($sql, $paramsPlain=array(), $verbose=true){
	try {

		//DEBUG
		//   		echo "\n".$sql."\n";
		//   		print_r($paramsPlain);

		$dbh = getConnection();
		$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

		// Prepare query parameters
		$params = array();
		foreach($paramsPlain as $key => $field)
			$params[':'.$key] = $field;

			// Execute query
			$query = $sth->execute($params);
			if (!$query )
			{
				echo $sql."\nERROR: ";
				print_r($dbh->errorInfo());
			}

			// Select Result
			if (stripos($sql,"INSERT ") !== FALSE)
			{
				$result['id']=$dbh->lastInsertId();
			}
			else if (stripos($sql,"UPDATE ") !== FALSE)
			{
				$result=$paramsPlain;
			}
			else if (stripos($sql,"SELECT ") !== FALSE)
			{
				$rs = $sth->fetchAll(PDO::FETCH_OBJ);
				if (stripos($sql," LIMIT 1") === FALSE)
					$result=$rs;
				else if (count($rs)==1)
					$result=$rs[0];
				else if ($verbose) {
					if (count($rs)==0)
						return printError($sql, $paramsPlain, "No result found", $verbose);
					else
						return printError($sql, $paramsPlain,  "Too many results found", $verbose);
				} else {
					$result = null;
				}
				
			}
			else
			{
				$result=$query;
			}
				
			if ($verbose)
				echo json_encode($result);

				return $result;

	}catch(PDOException $e) {
		return printError($sql, $paramsPlain,  $e->getMessage(), $verbose);
	}
}

//stampa l'errore della query
function printError($sql, $params, $msg, $verbose){
	$error = array('error' => array(
			'query' => $sql,
			'params' => $params,
			'message' => $msg,
	));

	echo json_encode($error);
	global $app;
	$app->response()->status(500);
	return null;
	exit;
}

?>