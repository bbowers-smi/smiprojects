<!DOCTYPE html>
<?php
$user = 'PHPUSER';
$password = 'phpusri7';

$host = 'S106B0CP';

$conn = db2_connect($host, $user, $password);
if($conn){
	
	$gethospitals = "select distinct(facname) from r50modsdta.prdeval";
	$stmt = db2_exec($conn, $gethospitals);
	if($stmt){
		
		while($row=db2_fetch_assoc($stmt)){
			
			$hospital_list[] = $row['FACNAME'];
			
		}
	}
}else{
	die("Unable to establish DB connection".db2_conn_errormsg());
}
?>
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
<script type="text/javascript" src="charts/themes/chalk.js"></script>

<title>Product Evaluation Filters</title>
</head>
<body>
<div id="menu">
<p>To look at the results for, first select the hospital then select the item.</p>
<form name="filtered" action="buildresults.php" method="post">
<table>
	<tr>
		<th>Facility</th>
		<th>Seneca Item</th>
	</tr>
	<tr>
		<td><select name="facility" id="facility" onchange="getitems();">
		<option value="None">Select Hospital...</option>
		<?php 
		foreach($hospital_list as $key=>$value){
			echo "<option value=\"".$value."\">".$value."</option>";
			}
		?>
		</select></td>
		<td class="itmlst"></td>
	</tr>
	
</table>
</form>
</div>
<script type="text/javascript">
function getitems(){
	var hospital = $('select[name="facility"] option:selected').val();
   
	$.ajax({
			type: 'POST',
			url: 'http://www1.senecamedical.com/product_eval/getitems.php',
			dataType: 'json',
			data:{hospital: hospital},
			success: function(response){
				console.log(response.item);
				var itemdropdown = "<select name='thisitem' onchange='getresults()'>";
				itemdropdown+="<option value=0>Select Item...</option>";
				$.map(response.item,function(value){
					itemdropdown+="<option value='"+value+"'>"+value+"</option>";
					
					});
			    
				itemdropdown+="</select>";
				console.log(itemdropdown);
				$('td.itmlst').html(itemdropdown);
			}
	    
			});
	
}
function getresults(){
	document.filtered.submit();	
}

function buildCharts(){
var chart = AmCharts.makeChart("chartdiv", {
    "type": "pie",
    "theme": "chalk",
    "dataProvider": [{
        "title": "New",
        "value": 4852
    }, {
        "title": "Returning",
        "value": 9899
    }],
    "titleField": "title",
    "valueField": "value",
    "labelRadius": 5,

    "radius": "42%",
    "innerRadius": "60%",
    "labelText": "[[title]]"
});
}
</script>
</body>
</html>