<!DOCTYPE html>
<html>

<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="css/main.css"/>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.11.0.custom/jquery-ui.min.css"/>

<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>

<title>Product Evaluation Filters</title>
</head>
<body>
<?php
session_start();
$conn = db2_connect('S106B0CP', 'phpuser', 'phpusri7');
if($conn){
$hospital = $_POST['facility'];
$item = $_POST['thisitem'];

echo "Getting item ".$item." for ".$hospital."<br />";
$nbrstmt = "select count(*) as totalstmt from r50modsdta.prdstmt";
$stmtresult = db2_exec($conn,$nbrstmt);
if($stmtresult){
   $rs = db2_fetch_array($stmtresult,0);
   $total_questions = $rs[0];
}

$qry = "select stmtid,evalchoic from r50modsdta.prdeval where facname='".$hospital."' and evalitem='".$item."' order by stmtid,evalchoic";

$stmt = db2_exec($conn, $qry);
if($stmt){
  while($row=db2_fetch_assoc($stmt)){
	print_r($row);
	echo "<br />";
}
}
}else{
	echo db2_conn_errormsg();
}
?>
</body>
</html>