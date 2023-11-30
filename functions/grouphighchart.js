$(document).ready(function() {
	chart = new Highcharts.Chart({
	chart: {
			plotBackgroundColor:'#ecf5f5',
			renderTo: 'graph2'
	},
	title: {
		text: 'Distribution of Customer group wise',
		align: 'middle'
	},

	tooltip: {
		formatter: function() {
			return '<b>'+ this.point.name +'</b>: '+ this.y +' %';
		}
	},
	plotOptions: {
		pie: {
		allowPointSelect: true,
		cursor: 'pointer',
		dataLabels: {
		enabled: true,
		formatter: function() {
			if (this.y >50) return this.point.name;
			},
			color: 'white',
			style: {
				fontSize: '13px'
				}
			}
		}
	},
	legend: {
		enabled: true
	},
	series: [{
		type: 'pie',
		dataURL: '../ajax/productgroup.php'
	}]
	});
});
