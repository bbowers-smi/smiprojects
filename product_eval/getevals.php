<?php 
include_once 'logging.php';

$log = new Logging();
$log->lfile('/www/salessvr/htdocs/product_eval/logs/evallog.txt');
//Get connection
$conn = db2_connect('S106B0CP', 'phpuser', 'phpusri7');

if($conn){
	
	$hospital = $_POST['hospital'];
	$getitem = $_POST['item'];
	$question_start = 0;
	$evalrs = array();
	$yesctr = 0;
	$noctr = 0;
	$questqry = "select stmtid,evalchoic from r50modsdta.prdeval where facname='".$hospital."' and evalitem='".$getitem."' order by stmtid,evalchoic";
	
	$qryrs = db2_exec($conn, $questqry);
	if($qryrs){
		while($row=db2_fetch_assoc($qryrs)){
			
			if($question_start == 0){
				$question_start = $row['STMTID'];
			}else if($question_start != $row['STMTID']){
				$evalrs[$question_start] = array('yes'=>$yesctr,'no'=>$noctr);
				$yesctr = 0;
				$noctr = 0;
				$question_start = $row['STMTID'];
			}
			if($row['EVALCHOIC'] == "Y"){
				$yesctr+=1;
			}else{
				$noctr+=1;
			}
		}
		$evalrs[$question_start] = array('yes'=>$yesctr,'no'=>$noctr);
	}else{
		die("Unable to query eval file".db2_stmt_errormsg());
	}
	
$result = array('evals'=>$evalrs);

	echo json_encode($result);
}else{
	
	echo db2_conn_errormsg();
}
?>