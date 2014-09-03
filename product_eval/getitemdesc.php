<?php 

//Get connection
$loadxml = simplexml_load_file('evalconfig.xml');

$user = $loadxml->userid;
$password = $loadxml->password;

$host = $loadxml->host;
$conn = db2_connect($host, $user, $password);
if($conn){
	
	$hospital = $_POST['hospital'];

	if($hospital == "ALL"){
		$itemqry = "select distinct(evalitem) from r50modsdta.prdeval";
	}else{
		$itemqry = "select distinct(evalitem) from r50modsdta.prdeval where facname='".trim($hospital)."'";
	}
	
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