/**--------------------------------------------------------------------------
| Customer Dashboard Scripts
|----------------------------------------------------------------------------
|	@file	cust-dashboard.js
|	@brief	Scripts for Customer Dashboard
|	@details DESCRIPTION: Highcharts dependent
|
|--------------------------------------------------------------------------*/

$(document).ready(function() {

function initCustomerDashboard()
{
	bindLitresForm();
    bindDeleteLitres();
    createTankLevelChart();
    createForecastChart();
}

function bindLitresForm()
{
    var srv_message = '';
    var error_cntr  = $('.error-msg');
    var submit_btn  = $('#litres-upd-btn');
    var litres_form = $('#litres-update');
    
    litres_form.submit(function() {
        
        srv_message = '';
        submit_btn.prop('disabled', true).text('Verifying...');
        
        $.post($(this).attr('action'), litres_form.serialize(), function(srv_resp) {
            
            if (1 == srv_resp.sts) {
                location.reload();
            }
            else {
                /**	Formats the warnings received from the Server	*/
                for (idx = 0; idx < srv_resp.messages.length; idx++) {
                    srv_message += srv_resp.messages[idx] +'<br/>';
                }
                
                /**	Display the warning								*/
                error_cntr.show();
                error_cntr.html(srv_message);
            }
            
            /** Resets the Save button when there are errors    */
            submit_btn.prop('disabled', false).text('Submit');
            
        }, 'json')
        
        return false;
    });
}

function bindDeleteLitres()
{
    $('.delete-litres').click(function() {
        $(this).text('Deleting, please wait...');
        
        $.get($(this).attr('href'), function(sts) {
            if (1 == sts) {
                location.reload();
            }
            else {
                alert('An error occured while deleting the entry. Please try again.');
            }
        });
        
        return false; 
    });
}

function createTankLevelChart()
{
    $('#tank-sts-chart').highcharts({
        chart: {
            type: 'column'
            
        },
        
        credits: {
            enabled: false
        },
        
        title: {
            text: 'Current Level: '+ parseInt($('#current_level').val())
        },

        xAxis: {
            categories: ['Tank']
        },

        yAxis: {
            allowDecimals: false,
            min: 0,
            max: parseInt($('#tank_max_capacity').val()),
            title: {
                text: 'Litres'
            },
            tickInterval: 1000,
            plotLines: [{
                value: $('#sump_level').val(),
                color: 'red',
                width: 1,
                dashStyle: 'dash',
                zIndex: 5,
                label: {
                    text: 'Sump Level: '+$('#sump_level').val(),
                    align: 'center',
                    size: '9px',
                    style: {
                        color: '#000000',
                    }
                }
            }]
        },

        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name+ '<br/>' +
                    'Maxium Capacity: ' + $('#tank_max_capacity').val();
            }
        },

        plotOptions: {
            column: {
                stacking: 'normal'
            }
        },

        series: [
            {
                name: 'Safety Fill',
                data: [parseInt($('#bar_safety_fill').val())],
                color: '#90ed7d'
            },
            {
                name: 'Current Level',
                data: [parseInt($('#current_level').val())],
                color: '#7cb5ec',
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.0f}',
                }
            }
        ]
    });
}

function createForecastChart()
{
    var forecast_litres = [];
    var forecast_dates  = [];
    var idx             = 0;
    
    $('.forecast_litres').each(function() {
        forecast_litres[idx]   = parseInt($(this).val());
        idx++;
    });
    
    idx = 0;
    
    $('.forecast_dates').each(function() {
        forecast_dates[idx]   = $(this).val();
        idx++;
    });
    
    $('#forecast-chart').highcharts({
        chart: {
            type: 'area',
            height: 470
        },
        
        credits: {
            enabled: false
        },
    
        title: {
            text: 'Forecast Usage',
            style: {
                'fontSize': '24px'
            }
        },
        
        subtitle: {
            text: '15 Day Forecast',
            style: {
                'fontSize': '12px',
                'padding': '10px'
            }
        },
        
        xAxis: {
            categories: forecast_dates,
            tickmarkPlacement: 'on',
            title: {
                enabled: false
            },
            labels: {
                formatter: function () {
                    return this.value;
                }
            }
        },
        
        yAxis: {
            min: 0,
            max: parseInt($('#tank_max_capacity').val()),
            tickInterval: 1000,
            title: {
                text: 'Litres'
            },
            labels: {
                formatter: function () {
                    return this.value;
                }
            }
        },
        
        tooltip: {
            shared: true,
        },
        
        plotOptions: {
            area: {
                stacking: 'normal',
                lineColor: '#666666',
                lineWidth: 1,
                marker: {
                    lineWidth: 1,
                    lineColor: '#666666'
                }
            }
        },
        
        series: [{
            name: 'Tank Capacity',
            data: forecast_litres
        }]
    });
}

initCustomerDashboard();

});