var chartLikesForFanpageLikes = null;
var chartLikesPorMes = null;
var chartCalificaciones = null;
$(document).ready(function() {
	loadDefaultDate();
	initBotones();
	initFansPerFanPage();
	initFansLastMonth();
	initCalificaciones();
	configurarDaterange();

});

function initFansPerFanPage() {	
    Highcharts.setOptions({
        global : {
            useUTC : true
        },
        lang: {
            rangeSelectorZoom: 'View',
            	decimalPoint: ',',
                thousandsSep: '.'
        }
    });
    
	chartLikesForFanpageLikes = new Highcharts.Chart({
        chart: {
            type: 'column',
            renderTo: ('fans-per-fanpage')
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            title: {
            	min: 0,
                text: 'Cantidad de Fans'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Fans: <b>{point.y:.0f}</b>',
            yDecimal: 0
        },
        series: [{
            name: 'Population',
            data: [	
                   
            ],
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                x: 4,
                y: 10,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif',
                    textShadow: '0 0 3px black'
                }
            }
        }],
        
        
    });
	
	cargarDatosDeLikesForFanpage();
}

function initFansLastMonth() {
	
    Highcharts.setOptions({
        global : {
            useUTC : true
        },
        lang: {
            rangeSelectorZoom: 'View'
        }
    });
    
	chartLikesPorMes = new Highcharts.Chart({
        chart: {
            renderTo: ('fans-per-month')
        },
        title: {
            text: '',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: []
        },
        yAxis: {
            title: {
                text: 'Cantidad de Fans'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ' fans',
            yDecimal: 0
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },

    });
	
	cargarDatosDeLikesPorMes();
}

function initCalificaciones() {
	
	  Highcharts.setOptions({
	        global : {
	            useUTC : true
	        },
	        lang: {
	            rangeSelectorZoom: 'View'
	        }
	    });
	    
	chartCalificaciones = new Highcharts.Chart({
	
        chart: {
        	
        	renderTo: ('calificaciones'),
            plotBackgroundColor: null,
            plotBorderWidth: 1,//null,
            plotShadow: false
        },
        title: {
            text: ''
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Browser share',
            data: [
                
            ]
        },
        ]
    });
	
	cargarCalificaciones();
}

function cargarCalificaciones() {
	var daterange = document.getElementById('daterange').value;
	
    $.ajax({
		type: "POST",
		url: "DB/contadorCalificacionesConRango",
		data: { daterange: daterange}
	 }).done (function(data){
		 var col = "black";
		 var cali = new Array();
		 $.each(data, function(key, value) {
			 if (key == "Positivos")
				 col = "#29CA7E";
			 else if (key == "Negativos")
				 col = "#E84B3C";
			 else if (key == "Neutrales")
				 col = "#3697DC";
			 
			 cali.push({name: key, y: parseInt(value), color: col});
		 });
		 
		 chartCalificaciones.series['0'].setData(cali);
		 
	 }) 
	dibujarChart(chartCalificaciones);
}


function cargarDatosDeLikesForFanpage() {
	
    $.ajax({
		type: "POST",
		url: "DB/likesForFanpage",
	 }).done (function(data){
		 var likes = new Array();
		 $.each(data, function(key, value) {
			 likes.push({name: key, y: parseInt(value)});
			 
			 
		 });

		 chartLikesForFanpageLikes.series['0'].setData(likes);
	 })
	 
	 dibujarChart(chartLikesForFanpageLikes);   
    
}


function cargarDatosDeLikesPorMes() {
    $.ajax({
		type: "POST",
		url: "DB/likesForFanpageWithRange",
	 }).done (function(data){
		 var likes = new Array();
		 var categorias = new Array();
		 var i = 0;
		 $.each(data['data'], function(key, value) {
			 chartLikesPorMes.addSeries({name: key, data: value}, false);
			 categorias[i] = data['categorias'][i];
			 i++;
		 });
		 
		 chartLikesPorMes.xAxis[0].setCategories(data['categorias']);
		 dibujarChart(chartLikesPorMes);
	 })
	 
	 
}

function dibujarChart(chart) {
	chart.showLoading();
    setTimeout(function(){chart.redraw()}, 1);
    chart.hideLoading();
}



function initBotones () {
	 $(document).on('click','.btn-actualizarRango',{}, function (e) {
		 cargarCalificaciones();
	 });	
}


function configurarDaterange() {
	 $("#daterange").on('apply.daterangepicker', function () {
		 cargarCalificaciones();}
	);
}

function loadDefaultDate() {

	var defaultDate = new Date();
	var startDate = defaultDate.getFullYear()+'-'+("0" + (defaultDate.getMonth() )).slice(-2)+'-'+defaultDate.getDateTwoDigits();
	var endDate = defaultDate.getFullYear()+'-'+("0" + (defaultDate.getMonth() + 1)).slice(-2)+'-'+defaultDate.getDateTwoDigits();
	$('input[name="daterange"]').daterangepicker(
	{
	format: 'YYYY-MM-DD',
	startDate: startDate,
	endDate: endDate,
	showDropdowns: true
	}
	);
	$('input[name="daterange"]').on('hide.daterangepicker', function(ev, picker) {
	//do something, like clearing an input
	$('input[name="daterange"]').val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'))
	});
	$('input[name="daterange"]').val(startDate + ' - ' + endDate);

	}

	 

	Date.prototype.getMonthTwoDigits = function()
	{
	  var retval = this.getMonth();
	  if (retval < 10)
	  {
	    return ("0" + retval.toString());
	  }
	  else
	  {
	    return retval.toString();
	  }
	}

	 

	Date.prototype.getDateTwoDigits = function()
	{
	  var retval = this.getDate();
	  if (retval < 10)
	  {
	    return ("0" + retval.toString());
	  }
	  else
	  {
	    return retval.toString();
	  }
	}
