<?php

try
{
			
	//$con = mysql_connect("localhost","techiepl_user50","user50pass");
	//mysql_select_db("techiepl_lomagdb", $con);

	//Open database connection
	$con = mysql_connect("localhost","root","");
	mysql_select_db("jtabletestdb", $con);

	//Getting records (listAction)
	if($_GET["action"] == "list")
	{
		try{
			

			//Get record count
			$result = mysql_query("SELECT COUNT(*) AS RecordCount FROM people;");
			$row = mysql_fetch_array($result);
			$recordCount = $row['RecordCount'];

			//Get records from database
			$result = mysql_query("SELECT * FROM people ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . "");
			
			// //Add all records to an array
			$rows = array();
			while($row = mysql_fetch_array($result))
			{
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
	//Creating a new record (createAction)
	else if($_GET["action"] == "create")
	{
            
           //Insert record into database
           //$result = mysql_query("INSERT INTO people(FacilityName, FacilityAddress, LocalGovernmentArea, State) VALUES('" . $_POST["FacilityName"] . "', '" . $_POST["FacilityAddress"] . "', '" . $_POST["LocalGovernmentArea"] . "', '" . $_POST["State"] . "')");
           $result = mysql_query("INSERT INTO people(FacilityName, FacilityAddress, LocalGovernmentArea, State) VALUES(" . 
                            "'" . $_POST["FacilityName"] . "'," .
                            "'" . $_POST["FacilityAddress"] . "'," .
                            "'" . $_POST["LocalGovernmentArea"] . "'," . 
                            "'" . $_POST["State"] . "')");
		
		//Get last inserted record (to return to jTable)
		$result = mysql_query("SELECT * FROM people WHERE PersonId = LAST_INSERT_ID()");
                //$result = mysql_query("SELECT * FROM people ORDER BY PersonId DESC LIMIT 1");
		$row = mysql_fetch_array($result);

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['Record'] = $row;
		print json_encode($jTableResult);
	}
	//Updating a record (updateAction)
	else if($_GET["action"] == "update")
	{
		//Update record in database
            $result = mysql_query("UPDATE people SET FacilityName = '" . $_POST["FacilityName"] . "', FacilityAddress = '" . $_POST["FacilityAddress"] . "', LocalGovernmentArea = '" . $_POST["LocalGovernmentArea"] . "', State = '" . $_POST["State"] . "'  WHERE PersonId = " . $_POST["PersonId"] . "");

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}
	//Deleting a record (deleteAction)
	else if($_GET["action"] == "delete")
	{
		//Delete from database
		$result = mysql_query("DELETE FROM people WHERE PersonId = " . $_POST["PersonId"] . ";");

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}
	else if($_GET['action']=="lgalist"){
		$lgalist = array(
				array('Value'=>0,'DisplayText'=>'-- Select Local Government Area --'),
				array('Value'=>'Gbagada','DisplayText'=>'Gbagada'),
				array('Value'=>'Shomolu','DisplayText'=>'Shomolu'),
				array('Value'=>'Kosofe','DisplayText'=>'Kosofe')
			);
				//'Gbagada', 'Shomolu', 'Kosofe');
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['Options'] = $lgalist;
		print json_encode($jTableResult);

	}


	//Close database connection
	mysql_close($con);

}
catch(Exception $ex)
{
    //Return error message
	$jTableResult = array();
	$jTableResult['Result'] = "ERROR";
	$jTableResult['Message'] = $ex->getMessage();
	print json_encode($jTableResult);
}
	
?>