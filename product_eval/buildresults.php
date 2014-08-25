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
<div id="chartmain">
<script>
$(document).ready(function(){
	createCharts();
	
});
</script>
<?php

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

	$nrows = $nbrquestions;

	echo "<input type=\"hidden\" name=\"nrows\" value=".($nrows)." ></input>";
	}else{
	die("Failed to get connection for charts");
	}
?>
<div id="chartarea">
<?php 
$tbl_rows = (int)$nrows/2;
$final_row = (int)$nrows%2;
?>
<table>
<?php 
$j = 1;
for($i=1;$i<=$tbl_rows;$i++){
	
	echo "<tr>";
	echo "<td id='chart".$j."'></td>";
	echo "<td id='chart".($j+1)."'></td>";
	echo "</tr>";
	$j+=2;
}
if($final_row == 1){
	echo "<tr>";
	echo "<td ></td>";
	echo "<td ></td>";
	echo "</tr>";
}
?> 
</table>
</div>
</div>
<script type="text/javascript">

function createCharts(){
	
	var chart = AmCharts.makeChart("chart1", {
	    "type": "pie",
	    "theme": "light",
	    "dataProvider": [
	{
	        "country": "Lithuania",
	        "litres": 501.9
	    },
	{
	        "country": "Czech Republic",
	        "litres": 301.9
	    }, {
	        "country": "Ireland",
	        "litres": 201.1
	    }, {
	        "country": "Germany",
	        "litres": 165.8
	    }, {
	        "country": "Australia",
	        "litres": 139.9
	    }, {
	        "country": "Austria",
	        "litres": 128.3
	    }, {
	        "country": "UK",
	        "litres": 99
	    }, {
	        "country": "Belgium",
	        "litres": 60
	    }, {
	        "country": "The Netherlands",
	        "litres": 50
	    }],
	    "valueField": "litres",
	    "titleField": "country"
	});
}
</script>
</body>
</html>