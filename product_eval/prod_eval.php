<!DOCTYPE html>
<html>

<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="css/main.css"/>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.11.0.custom/jquery-ui.min.css"/>

<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>

<title>Product Evaluation</title>
</head>
<body>
<?php 
$user = 'ws03';

$conn = db2_connect('S106B0CP', 'ws03', 'lauren16');
if($conn){
	$qry = "select stmtid,stmttxt from r50modsdta.prdstmt where stmtdel='A'";
	
	$stmt = db2_exec($conn,$qry);
	
	if($stmt){
		
		while($row = db2_fetch_assoc($stmt)){
		$questions[$row['STMTID']] = $row['STMTTXT'];
		}
	}
}else{
	echo db2_conn_errormsg();
}

if(isset($_COOKIE['holdvals'])){
	$holdvals_str = $_COOKIE['holdvals'];
	$holdvals_arr = json_decode($holdvals_str,true);
	
	$facility = $holdvals_arr['facility'];
	$item = $holdvals_arr['item'];
}else{
	$facility = "";
	$item = "";
}
?>
<div id="main">
<h1>Product Evaluation Survey</h1>
<?php 
$curr_date = date('m/d/Y');
?>
<form action="process.php" name="evalform" class="mainform" method="post">
<input type="hidden" name="salesm" value="<?php echo $user;?>"></input>
<div id="sect1">
<table>
<tr>
<td>Facility:</td>
<td><input type="text" name="facility" id="facility" size="35" value="<?php echo $facility;?>" placeholder="Facility Name..." autofocus></input></td>
</tr>
<tr>
<td>Date:</td>
<td><input type="date" name="evaldate" value="<?php echo $curr_date;?>"><br />MM/DD/YYYY</td>
<tr>
<td>Seneca Item:</td>
<td><input type="text" name="senitem" maxlength="6" size="10" value="<?php echo $item;?>" placeholder="Enter Seneca Item..." onkeyup="getitem();" onchange="getitem();"></input>
<br /><div id="itmdsc"></div></td>
</tr>
<tr>
<td>Department:</td>
<td><input type="text" name="dept" value="" size="25" placeholder="Department..." ></input></td>
</tr>
<tr>
<td>Name:</td>
<td><input type="text" name="repname" size="25" value="" placeholder="Rep Name..." autofocus ></input></td>
</tr>
</table>
</div>
<p style="width: 55%;margin:25px auto;font: bold 14px Arial,Georgia;text-shadow: 2px 2px 3px hsla(0,0%,0%,.7)" >
For each of the following statements about the product, please indicate your opinion by checking the appropriate box.
</p>
<div id="mainbody">
<div id="questions">
<table>
<tr>
<th>&nbsp;</th>
<th colspan="2">&nbsp;</th>
<th>Yes</th>
<th>No</th>
</tr>
<?php 
$questionctr = 1;

foreach($questions as $key=>$value){
	echo "<tr>";
	echo "<td class=\"firstcol\">".trim($value)."</td>";
	echo "<td colspan=\"2\">&nbsp;</td>";
	echo "<td><input type=\"radio\" name=\"eval".$key."\" value=\"Y\" ></input></td>";
	echo "<td><input type=\"radio\" name=\"eval".$key."\" value=\"N\" ></input></td>";
	echo "</tr>";
	$questionctr = $key;
}
echo "<input type=\"hidden\" name=\"nbrquestions\" value=".($questionctr)." ></input>";
?>
</table>
</div>
<br />
<p>
<span style="font: italic 16px Arial,Georgia,sans-serif;text-shadow: 2px 2px 3px hsla(0,0%,0%,.7);">
Additional Comments:</span><br />
<textarea rows="4" cols="60" name="comments"></textarea>
</p>
<input class="btn-style" type="button" name="btn" value="Submit" onclick="submitItem()" ></input>
<a href="prodeval_menu.php" class="btn-style">Main Menu</a>
</div>
</form>
</div>
</body>
<script type="text/javascript">  
function getitem(){
	var senitem = $('input[name="senitem"]').val();
	var itemchars = senitem.length;
	if(itemchars == 6){
		
		$.ajax({
			type: 'POST',
			url: 'http://www1.senecamedical.com/product_eval/getdescription.php',
			dataType: 'json',
			data:{item: senitem},
			success: function(response){  
					 $('#itmdsc').html(response.descr);
			}
			});
	}
}

function submitItem(){
	var fac = $('input[name="facility"]').val();
	var item = $('input[name="senitem"]').val();
	var holdvals = {facility: fac,item: item};
	var storvals = JSON.stringify(holdvals);
	
	$.cookie('holdvals',storvals,0,{path:'/'});
	document.evalform.submit();
}
</script>
	<script type="text/javascript">
	document.getElementById('facility').focus();
	</script>
</html>