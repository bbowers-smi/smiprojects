<script>
$(document).ready(function(){
	createCharts();
	
});
</script>
<?php

//$conn = db2_connect($host, $user, $password);
if($conn){
	$hospital = $_POST['facility'];
	$item = $_POST['thisitem'];
    $nbrquestions = 0;
	$questnbr = "select stmttxt from r50modsdta.prdstmt where stmtdel = 'A'";
	$questrs = db2_exec($conn, $questnbr);
	if($questrs){
     while($row = db2_fetch_assoc($questrs)){
		$question_text[] = $row['STMTTXT'];
		$nbrquestions += 1;
		}
	}else{
		die("Failed getting number of questions.".db2_stmt_errormsg());
	}

	$nrows = $nbrquestions;
	
	echo "<input type=\"hidden\" name=\"nrows\" value=".($nrows)." ></input>";
	}else{
	die("Failed to get connection for charts");
	}
?>

<?php 
$tbl_rows = (int)$nrows/2;
$final_row = (int)$nrows%2;
?>
<div id="chartmain">
  <div id="chartwrapper">
<table id="charttable">
<?php 
$j = 1;
for($i=1;$i<=$tbl_rows;$i++){
	echo "<tr>";
	echo "<th>".$question_text[$i-1]."</th>";
	echo "<th>".$question_text[$i]."</th>";
	echo "</tr>";
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
<?php 
  $questqry = "select e.stmtid,e.evalchoic,s.stmttxt from r50modsdta.prdeval e
		left outer join r50modsdta.prdstmt s on s.stmtid=e.stmtid
		where e.facname='".$hospital."' and e.evalitem='".$item."' order by e.evalchoic";
  $qryrs = db2_exec($conn, $questqry);
  if($qryrs){
	
}else{
	die("Unable to query eval file".db2_stmt_errormsg());
}
?>
<script type="text/javascript">

function createCharts(){
	var getnbrcharts = $('input[name="nrows"]').val();
	for(i=1;i<=getnbrcharts;i++){
		var chartname = "chart"+i;
	var chart = AmCharts.makeChart(chartname, {
	    "type": "pie",
	    "dataProvider": [
	{
	        "preference": "Yes",
	        "nbrprefer": 5
	    },
	{
	        "preference": "No",
	        "nbrprefer": 3 }],
	        "radius":"25%",
	        "labelRadius":4,
	    "valueField": "nbrprefer",
	    "titleField": "preference"
	});
	}
	
}

</script>
