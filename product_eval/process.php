<?php

if(isset($_POST['menubtn'])){
	header("Location: prodeval_menu.php");
	exit();
}

$loadxml = simplexml_load_file('evalconfig.xml');

$user = $loadxml->userid;
$password = $loadxml->password;

$host = $loadxml->host;

$conn = db2_connect($host, $user, $password);

$evalid = 1;
if($conn){

$getid = "select max(PRDEUID) as rowid from r50modsdta.prdeval";
$stmt = db2_exec($conn, $getid);
if($stmt){
	$row = db2_fetch_assoc($stmt);
	if($row['ROWID'] >= 1){
		$evalid = $row['ROWID']+1;	
	}
}


$usrlogin = $_POST['salesm'];
$repname = trim($_POST['repname']);

$evaluation_date = $_POST['evaldate'];

$facility = $_POST['facility'];
$department = $_POST['dept'];
$seneca_item = $_POST['senitem'];
$nbr_of_questions = $_POST['nbrquestions'];
$seneca_item = $_POST['senitem'];

$comments = str_replace("'", "''", $_POST['comments']);

$comm1 = substr($comments,0,64);
$comm2 = substr($comments,64);
echo $comm1."<br />";
echo $comm2."<br />";

for($i=1;$i<=$nbr_of_questions;$i++){
	if(isset($_POST['eval'.$i])){
		
	$eval[$i] = $_POST['eval'.$i];
	
//Add record
$insrec = "insert into r50modsdta.prdeval (PRDEDEL,PRDEUID,PRDUID,EVALITEM,EVLRNAME,EVALDATE,FACNAME,DEPTNAME,STMTID,EVALCHOIC,ADDCOMM1,ADDCOMM2) values('A',".$evalid.",'".$usrlogin."','".$seneca_item."','
".trim($repname)."','".$evaluation_date."','".strtoupper($facility)."','".$department."',".$i.",'".$eval[$i]."','".$comm1."','".$comm2."')";
$inseval = db2_exec($conn,$insrec);
echo $insrec;
if(!$inseval){
	
	die("Error inserting evaluation. ".db2_stmt_errormsg());
}
	}
}
}else{
	echo db2_conn_errormsg();
}

header("Location: prod_eval.php");
?>