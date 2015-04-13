<?php


//        $arr = array(3 => 51, 4 => 52, 53, 2 => 54, 55);
//        foreach($arr as $key => $value){
//            echo $key . ' => ' . $value . '<br/>';
//        }
//        exit;
        
	$host = 'localhost';
	$db = 'chaidb';
	$user = 'root';
	$pass = '';

	if($conn = mysql_connect($host, $user, $pass))
                ;
        else 
            die ('Connection failed');
        
        mysql_select_db($db, $conn);
        
        $stateid = 37;
        
        //get all facilities in temp table
        $sql = 'Select l.*, t.lga from  bak_cthx_lga  l LEFT JOIN transit t ON ' .
               'l.state_id=t.state_id AND l.lga_name = t.lga WHERE l.state_id='.$stateid .
               ' AND t.lga_id = 0';
        
        $result = mysql_query($sql, $conn);
        
        if (!$result) {
            $message  = 'Invalid query: ' . mysql_error() . "\n";
            $message .= 'Whole query: ' . $sql;
            die($message);
         }
        
         
        echo "Processing state $stateid<br/><br/>";
        
        
        $count =0;
        while($row = mysql_fetch_array($result)){
            
            echo 'ID: ' . $row['lga_id'] . ' ' . $row['lga'] . '<br/>';            
            
            $sql = 'update transit set lga_id = ' . $row['lga_id'] . ' where lga =\'' . addslashes($row['lga']) . '\'';
            
            mysql_query($sql);
            
            
            $count++;
            //echo 'LGA ' . $count . ' updated.<br/>';
        }
        
        echo 'number of rows affected: ' . $count;
        
        
?>