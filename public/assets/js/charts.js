/*
Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 4
Version: 4.4.0
Author: Sean Ngu
Website: http://www.seantheme.com/color-admin/admin/
*/

Chart.defaults.global.defaultFontColor = COLOR_DARK;
Chart.defaults.global.defaultFontFamily = FONT_FAMILY;
Chart.defaults.global.defaultFontStyle = FONT_WEIGHT;

var randomScalingFactor = function () {
	return Math.round(Math.random() * 100)
};
var datas = <?php echo json_encode($business_data); ?>;
for (var result in datas ) {
	alert(JSON.stringify(result));
	
}
var lineChartData = {

	labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
	datasets: [
		{
			label: 'Dataset 1',
			borderColor: COLOR_BLUE,
			pointBackgroundColor: COLOR_BLUE,
			pointRadius: 2,
			borderWidth: 2,
			backgroundColor: COLOR_BLUE_TRANSPARENT_3,
			data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
		}, {
			label: 'Dataset 2',
			borderColor: COLOR_DARK_LIGHTER,
			pointBackgroundColor: COLOR_DARK,
			pointRadius: 2,
			borderWidth: 2,
			backgroundColor: COLOR_DARK_TRANSPARENT_3,
			data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
		}
	]
};


var handleChartJs = function () {
	var ctx = document.getElementById('line-chart').getContext('2d');
	var lineChart = new Chart(ctx, {
		type: 'bar',
		data: lineChartData
	});
};

var ChartJs = function () {
	"use strict";
	return {
		//main function
		init: function () {
			handleChartJs();
		}
	};
}();

$(document).ready(function () {
	alert("hola");
	ChartJs.init();
});