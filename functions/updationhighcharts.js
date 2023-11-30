$(document).ready(function() {
var chart = new Highcharts.Chart({
	chart: {
		plotBackgroundColor: '#FCFFC5',
		renderTo: 'container',
		defaultSeriesType: 'column'
	},
	title: {
		text: 'Total Customers in your Account'
	},

	xAxis: {
		categories: ['2004-05', '2005-06','2006-07', '2007-08', 
		'2008-09', '2009-10', '2010-11']
	},
	yAxis: {
		min: 0,
		title: {
			text: 'Customer ',
			align: 'middle'
		}
	},
	tooltip: {
		formatter: function() {
			return '<b>'+ this.x +'</b><br/>'+
			this.series.name +': '+ this.y ;
		}
	},
	plotOptions: {
		column: {
			lineWidth :0.3,
			pointPadding: 0.1,
			borderWidth:1,
			enabled: true
		}
	},
	legend: {
		width: 360,
		align: 'left',
		x: 80,
		itemWidth: 80,
		lineHeight: 32,
		style:{
			bottom:'1px'
		}
	},

	series: [{
		name: 'SARAL TDS',
		type: 'column',
		dataURL: '../ajax/datafortds.php' 
	}, {
		name: 'SARAL PAYPACK',
		type: 'column',
		dataURL: '../ajax/dataforspp.php' 
	},{
		name: 'SARAL TAXOFFICE',
		type: 'column',
		dataURL: '../ajax/dataforsto.php' 
	}
	,{
		name: 'SARAL VAT100',
		type: 'column',
		dataURL: '../ajax/dataforsvh.php'
	},{
		name: 'SARAL VATinfo',
		type: 'column',
		dataURL: '../ajax/dataforsvi.php'
	}]
	});
});



