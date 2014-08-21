<?php 
include_once 'logging.php';

$log = new Logging();
$log->lfile('/home/BrianB/prodeval.log');
//Get connection
$conn = db2_connect('S106B0CP', 'ws03', 'lauren16');

if($conn){
		
	$item = $_POST['item'];
	
$itemqry = "select icdsc1, icdsc2 from r50files.vinitem where icitem='".$item."'";
$stmt = db2_exec($conn, $itemqry);

$row=db2_fetch_assoc($stmt);

	$desc1 = trim($row['ICDSC1']);
	$desc2 = trim($row['ICDSC2']);
	
	$log->lwrite("Description is ".$desc1."".$desc2);
	
	if($desc1 != "" && $desc2 != ""){
	$desc = $desc1."".$desc2;
	}else{
		$desc = "<h3 style='color: red;'>Not a valid Seneca item.</h3>";
	}
	$result = array('descr'=>$desc);
	echo json_encode($result);
}else{
	echo db2_conn_errormsg();
}
?>