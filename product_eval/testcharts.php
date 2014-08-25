<!DOCTYPE html>
<html>

<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.11.0.custom/jquery-ui.min.css"/>

<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="charts/amcharts.js"></script>
<script type="text/javascript" src="charts/pie.js"></script>

<title>Test Charts</title>
<style>
#main {
	width: 500px;
	margin: 10px auto;
}
#chartwrapper {
	position: relative;
	width: 300px;
	height: 100px;
}
#chart1,#chart2,#chart3,#chart4 {
	width: 200px;
	height: 200px;
}
</style>
</head>
<body>
<div id="main">
  <div id="chartwrapper">
<table>
<tr>
	<td id="chart1"></td>
	<td id="chart2"></td>
</tr>
<tr>
	<td id="chart3"></td>
	<td id="chart4"></td>
</tr>
</table>
  </div>
</div>

</body>
</html>