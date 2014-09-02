<!DOCTYPE html>
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
$hospital = "";
if(isset($_POST['facility'])){
	$hospital = $_POST['facility'];
	
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

<title>Product Evaluation Filters</title>
<style>
#chartmain {
	width: 600px;
	margin: 10px auto;
}
#chartwrapper {
	position: relative;
	width: 600px;
	height: 100px;
}
#charttable td {
	width: 300px;
	height: 300px;
}
</style>
</head>
<body>
<div id="menu">
<p>Select the facility, then select the item to review.</p>

<form name="filtered" action="" method="post">
<table>
	<tr>
		<td><input type="submit" name="menubtn" id="menubtn" value="Main Menu"/></td>
		<td><a href="#" onclick="printscreen();">Print</a></td>
	</tr>
</table>
<table id="filters">
	<tr>
		<th>Facility</th>
		<th>Seneca Item</th>
	</tr>
	<tr>
		<td><select name="facility" id="facility" onchange="getitems();">
		<option value="None">Select Hospital...</option>
		<option value="ALL">All Hospitals</option>
		<?php 
		foreach($hospital_list as $key=>$value){

			if($hospital == trim($value)){
				echo "<option value=\"".trim($value)."\" selected>".trim($value)."</option>";
				
			}else{
			echo "<option value=\"".trim($value)."\">".trim($value)."</option>";
			}
			}
		?>
		</select></td>
		<td>&nbsp;</td>
		<td class="itmlst"></td>
	</tr>
	
</table>

</form>

<div id="chartsect">
<?php 
if(isset($_POST['facility']) && isset($_POST['thisitem'])){
$hospital = $_POST['facility'];
$item = $_POST['thisitem'];

include 'buildresults.php';
}
?>
</div>

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
				
				var itemdropdown = "<select name='thisitem' onchange='getresults()'>";
				itemdropdown+="<option value=0>Select Item...</option>";
				$.map(response.item,function(value){
					itemdropdown+="<option value='"+value+"'>"+value+"</option>";
					
					});
			    
				itemdropdown+="</select>";
				
				$('td.itmlst').html(itemdropdown);
			}
	    
			});
	
}
function getresults(){
	document.filtered.submit();	
}


</script>
<?php 
if($hospital != ""){
echo "<script>window.onload=getitems();</script>";
}
?>
<script type="text/javascript">
function printscreen(){
	$('.btn-style,table#filters,#menu>p').toggle();
	
	window.print();
	$('.btn-style,table#filters,#menu>p').toggle();
}
</script>
</body>
</html>