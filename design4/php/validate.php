<?php

/* RECEIVE VALUE */
$validateValue=$_GET['username'];
//$validateId=$_REQUEST['fieldId'];



//Open database connection
	$con = mysql_connect("localhost","root","Taolak_2012");
	mysql_select_db("jtabletestdb", $con);
        
        //Get records from database
		
                
                    //$arrayToJs = array();
                    //$arrayToJs[0] = $validateId;
                        
                       
                            
                            $result = mysql_query("SELECT username FROM admin where username= '".$validateValue."';");
                            $numRows = mysql_num_rows($result);
                            
                             if($numRows>0){       // validate??
                            //$arrayToJs[1] = true;           // RETURN TRUE
                            //echo json_encode($arrayToJs);           // RETURN ARRAY WITH ERROR
                            echo true;
                        }else{
                                //$arrayToJs[1] = false;          // RETURN FALSE
                                //echo json_encode($arrayToJs);       // RETURN ARRAY WITH success
                                echo false;
                             }
                            
                            
                    
                       
                       

		


?>