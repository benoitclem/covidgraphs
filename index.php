
<!DOCTYPE HTML>
<html>
<head>  
<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title:{
<?php
if($_GET['display'] == 'all'){
	echo '		text: "COVID-19 Death Growth"';
}elseif($_GET['display'] == 'ratio'){
	echo '		text: "COVID-19 Death Ratio"';
}else{
	echo '		text: "COVID-19 Death Growth in Europe"';
}
?>

	},
	axisY:{
<?php
if($_GET['display'] == 'ratio'){
echo '		title: "ratio of deaths",';
}else{
echo '		title: "number of deaths",';
}
if($_GET['display'] == 'ratio'){
echo '		suffix: " %",';
}else{
echo '		logarithmic: true,';
}
?>
		titleFontColor: "#6D78AD",
		gridColor: "#6D78AD",
		labelFormatter: addSymbols
	},
<?php
if($_GET['display'] != 'ratio'){
print '	axisX:{
		title: "days since number of deaths >= 5",
		titleFontColor: "#6D78AD",
		labelFormatter: addSymbols
	},
';
}
?>
	legend: {
		cursor: "pointer",
		verticalAlign: "top",
		fontSize: 16,
		itemclick: toggleDataSeries
	},
//	data: [{
//		type: "line",
//		markerSize: 0,
//		showInLegend: true,
//		name: "Log Scale",
//		yValueFormatString: "#,##0 MW",
//		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
//	},
//	{
//		type: "line",
//		markerSize: 0,
//		axisYType: "secondary",
//		showInLegend: true,
//		name: "Linear Scale",
//		yValueFormatString: "#,##0 MW",
//		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
//	}]

<?php 
if($_GET['display'] == 'all'){
	echo file_get_contents('./results_all.json');
}elseif($_GET['display'] == 'ratio'){
	echo file_get_contents('./ratio_all.json');
}else{
	echo file_get_contents('./results.json');
}
?>


});
chart.render();
 
function addSymbols(e){
	var suffixes = ["", "K", "M", "B"];
 
	var order = Math.max(Math.floor(Math.log(e.value) / Math.log(1000)), 0);
	if(order > suffixes.length - 1)
		order = suffixes.length - 1;
 
	var suffix = suffixes[order];
	return CanvasJS.formatNumber(e.value / Math.pow(1000, order)) + suffix;
}
 
function toggleDataSeries(e){
	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	}
	else{
		e.dataSeries.visible = true;
	}
	chart.render();
}

	document.getElementById("hideAll").addEventListener("click", function(){
	for(i=0;i<chart.data.length;i++){
		chart.data[i].set("visible", false, false);
	}
	chart.render();
    	}); 
	
	document.getElementById("showAll").addEventListener("click", function(){
	for(i=0;i<chart.data.length;i++){
		chart.data[i].set("visible", true, false);
	}
	chart.render();
    	}); 
 
 
}
</script>
</head>
<body>
<div><a href="?display=all">All countries</a></div><div><a href="?display=europe">Europe</a></div><div><a href="?display=ratio">Death Ratio</a></div>
<div><a href="#" id="hideAll">Unselect all</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" id="showAll">Select all</a></div>
<div id="chartContainer" style="height: auto; min-height: 600px; max-height: 800px; width: auto; max-width: 1200px; min-width: 800px"></div>
<div id="copyright"><em>&copy; <a href="https://github.com/CSSEGISandData/COVID-19/tree/master/csse_covid_19_data">CSSE COVID-19 dataset</a></em></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>                              
