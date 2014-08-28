<script>
$(document).ready(function(){
	createCharts();
	
});
</script>
<?php

//$conn = db2_connect($host, $user, $password);
if($conn){
	
    $nbrquestions = 0;
	$questnbr = "select stmtid,stmttxt from r50modsdta.prdstmt where stmtdel = 'A'";
	$questrs = db2_exec($conn, $questnbr);
	if($questrs){
     while($row = db2_fetch_assoc($questrs)){
		$question_text[] = $row['STMTTXT'];
		$list_questid[] = $row['STMTID'];
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
	echo "<td id='chart".$j."'><br /><div id=\"legenddiv\"></div></td>";
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
	var hospital = "<?php echo $hospital;?>";
	var item = "<?php echo $item;?>";
	var getnbrcharts = $('input[name="nrows"]').val();
	var listquestid = <?php echo json_encode($list_questid);?>;

	
	$.ajax({
		type: 'POST',
		url: 'http://www1.senecamedical.com/product_eval/getevals.php',
		dataType: 'json',
		data:{hospital: hospital,item: item},
		success: function(response){
			
	        var evalarr = response.evals;
	       
	 j=0;      
	for(i=1;i<=getnbrcharts;i++){
		var yctr = 0;
        var nctr = 0;
		$.each(evalarr[listquestid[j]],function(index,value){
             
              if(index == "yes"){
                  yctr = value;
              }
              if(index == "no"){
                  nctr = value;
              }
              
			});
		
		var chartname = "chart"+i;
	var chart = AmCharts.makeChart(chartname, {
	    "type": "pie",
	    "dataProvider": [
	{
	        "preference": "Yes",
	        "nbrprefer": yctr
	    },
	{
	        "preference": "No",
	        "nbrprefer": nctr }],
	        "radius":"20%",
	        "labelRadius":5,
	    "valueField": "nbrprefer",
	    "titleField": "preference",
	    "processDelay":10
	});
	j++;
	}
		}    
	});
	
}

</script>
