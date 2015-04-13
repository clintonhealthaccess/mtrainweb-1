<?php


if(isset($_POST['submit']))
    {
    $question = trim($_POST['question']);
    $optA = trim($_POST['optA']);
    $optB = trim($_POST['optB']);
    $optC = trim($_POST['optC']);
    $optD = trim($_POST['optD']);
    $correctAns =trim($_POST['correctAns']);
    $tiptext = '<strong>'.trim($_POST['correctAns']).'</strong> is the correct answer.';
    $testId = trim($_POST['testId']);
    
    
    
    $optarray = array("A"=>$optA,"B"=>$optB,"C"=>$optC,"D"=>$optD);
    
    $options= json_encode($optarray);
    
    
    
    //Open database connection
	$con = mysql_connect("localhost","root","");
	mysql_select_db("chai_data_entry", $con);
    mysql_query("INSERT INTO cthx_test_question (question_id, question, options, correct_option, test_id, tiptext)VALUES (NULL,'$question','$options','$correctAns',$testId,'$tiptext');");
    
    

        
//        
//   class MyDB extends SQLite3
//   {
//      function __construct()
//      {
//         $this->open('chai_data_entry');
//      }
//   }
//   $db = new MyDB();
//   if(!$db){
//      echo $db->lastErrorMsg();
//   } 
////   else {
////      echo "<br/>Opened database successfully<br/>";
////   }
//   $sql =<<<EOF
//      INSERT INTO cthx_test_question (question_id, question, options, correct_option, test_id, tiptext)
//      VALUES (NULL,'$question','$options','$correctAns',$testId,'$tiptext');
//EOF;
//
//   $ret = $db->exec($sql);
//   if(!$ret){
//      echo $db->lastErrorMsg();
//   } else {
//      echo "<div align='center'><p style='color:green; background-color:#ccc; width:300px; text-align:center;'>Records Inserted successfully</p></div>";
//   }
//   $db->close();
//    
   
   
   
    }
    echo "<div align='center'><p style='color:green; background-color:#ccc; width:300px; text-align:center;'>Records Inserted successfully</p></div>";
    include("data_entry_form.php");
?>