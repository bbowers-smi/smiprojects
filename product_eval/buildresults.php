<!DOCTYPE html>
<html>

<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="css/main.css"/>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.11.0.custom/jquery-ui.min.css"/>

<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="charts/amcharts.js"></script>
<script type="text/javascript" src="charts/pie.js"></script>

<title>Product Evaluation Filters</title>
</head>
<body>
<?php
session_start();
$conn = db2_connect('S106B0CP', 'phpuser', 'phpusri7');
if($conn){
$hospital = $_POST['facility'];
$item = $_POST['thisitem'];

$questnbr = "select count(*) as nbrquestions from r50modsdta.prdstmt where stmtdel = 'A'";
$questrs = db2_exec($conn, $questnbr);
if($questrs){
	$row = db2_fetch_assoc($questrs);
	$nbrquestions = (int)$row['NBRQUESTIONS'];
}else{
	die("Failed getting number of questions.".db2_stmt_errormsg());
}
echo "Questions ".$nbrquestions;
$nrows = (int)($nbrquestions/2);
$extrarow = (int)$nbrquestions%2;
echo "<input type=\"hidden\" name=\"nrows\" value=".($nrows)." ></input>";
echo "<input type=\"hidden\" name=\"extras\" value=".($extrarow)." ></input>";
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
<div id="chartarea">
<div id="col1">
</div>
<div id="col2">
</div>
</div>
<script type="text/javascript">
buildChartDivs();

function buildChartDivs(){
	var ndiv = $('input[name="nrows"]').val();
	var extdiv = $('input[name="extras"]').val();
	var cellctr = 1;
	
	var newhtml=("div id='chart"+cellctr+"'");
	
	if(cellctr%2 == 0){
		$('#col1').html(newhtml);
	}
	if(cellctr%2 == 1){
		$('#col2').html(newhtml);
	}
function buildCharts(){
	var chart = AmCharts.makeChart("chartdiv", {
	    "type": "pie",
	    "theme": "none",
	    "dataProvider": [{
	        "country": "Lithuania",
	        "value": 260
	    }, {
	        "country": "Ireland",
	        "value": 201
	    }, {
	        "country": "Germany",
	        "value": 65
	    }, {
	        "country": "Australia",
	        "value": 39
	    }, {
	        "country": "UK",
	        "value": 19
	    }, {
	        "country": "Latvia",
	        "value": 10
	    }],
	    "valueField": "value",
	    "titleField": "country",
	    "outlineAlpha": 0.4,
	    "depth3D": 15,
	    "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
	    "angle": 30,
	    "exportConfig":{	
	      menuItems: [{
	      icon: 'charts/images/export.png',
	      format: 'png'	  
	      }]  
		}
	});
}
}
</script>
</body>
</html>