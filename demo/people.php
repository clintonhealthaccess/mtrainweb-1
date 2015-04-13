<?php


	$con = mysql_connect("localhost","root","");
	mysql_select_db("jtabletestdb", $con);


	if($_GET["action"] == "list"){
		try{
			// $jTableResult = array();
			// $jTableResult['Result'] = 'ERROR';
			// $jTableResult['Message'] = 'this is an error inside action list';
			// print json_encode($jTableResult);

			//Get record count
			$result = mysql_query("SELECT COUNT(*) AS RecordCount FROM people;");
			$row = mysql_fetch_array($result);
			$recordCount = $row['RecordCount'];

			//Get records from database
			$result = mysql_query("SELECT * FROM people ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . "");
			
			// //Add all records to an array
			$rows = array();
			while($row = mysql_fetch_array($result)){
			      $rows[] = $row;
			}

			// //Return result to jTable
			$jTableResult = array();
			$jTableResult['Result'] = "OK";
			$jTableResult['TotalRecordCount'] = $recordCount;
			$jTableResult['Records'] = $rows;
			print json_encode($jTableResult);
		} catch(Exception $e) {
			$jTableResult = array();
			$jTableResult['Result'] = 'ERROR';
			$jTableResult['Message'] = 'this is an error';
			print json_encode($jTableResult);
		}
	}



?>