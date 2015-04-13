<?php

//  if($_GET["action"] == "list")
    {
        $con = mysql_connect("localhost","root","");
        mysql_select_db("chaidb", $con);

        $result = mysql_query("SELECT COUNT(*) AS RecordCount FROM cthx_cadre;");
        $row = mysql_fetch_array($result);
        $recordCount = $row['RecordCount'];

        $result = mysql_query("SELECT * FROM cthx_cadre ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . ";");
        //Add all records to an array
        $rows = array();
        while($row = mysql_fetch_array($result)){
            $rows[] = $row;
        }

        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult); 
    }
?>
