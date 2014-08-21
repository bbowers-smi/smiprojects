<?php 
include_once 'logging.php';

$log = new Logging();
$log->lfile('/www/salessvr/htdocs/product_eval/logs/evallog.txt');
//Get connection
$conn = db2_connect('S106B0CP', 'phpuser', 'phpusri7');

if($conn){
	
	$hospital = $_POST['hospital'];
	
$itemqry = "select distinct(evalitem) from r50modsdta.prdeval where facname='".trim($hospital)."'";

$stmt = db2_exec($conn, $itemqry);
$item = array();
while($row=db2_fetch_assoc($stmt)){
	$item[] = trim($row['EVALITEM']);
	
}

$result = array('item'=>$item);

	echo json_encode($result);
}else{
	
	echo db2_conn_errormsg();
}
?>