/**--------------------------------------------------------------------------
| Customer Dashboard Scripts
|----------------------------------------------------------------------------
|	@file	cust-dashboard.js
|	@brief	Scripts for Customer Dashboard
|	@details DESCRIPTION: Highcharts dependent
|
|       [PROPERTIES]
|           NONE
|
|       [FUNCTIONS]
|			initCustomerDashboard.................Initialization
|			bindLitresForm........................Binds the submit event to the Litres Today Form
|			bindDeleteLitres......................Binds the delete links to the click event
|			createTankLevelChart..................Creates the Bar Chart on the Dashboard
|			createForecastChart...................Creates the Area Graph on the Dashboard
|			createForecastChart...................Creates the Area Graph on the Dashboard
|			bindDatePickInput.....................Binds the Datepicker elements to the JQuery Datepicker
|			bindUpdateLink........................Binds the Update link elements to the click Event
|
|--------------------------------------------------------------------------*/

$(document).ready(function() {

/**------------------------------------------------------------------------
|	Script Initialization
|--------------------------------------------------------------------------
|	@param [in]		NONE
|	@param [out] 	NONE
|	@return 		NONE
|------------------------------------------------------------------------*/
function initCustomerDashboard()
{
    /** Binds events    */
	bindLitresForm();
    bindDeleteLitres();
    bindDatePickInput();
    bindUpdateLink();
    
    /** Create Charts   */
    createTankLevelChart();
    createForecastChart();
}

/**------------------------------------------------------------------------
|	Binds the submit event to the Litres Today Form
|--------------------------------------------------------------------------
|	@param [in]		NONE
|	@param [out] 	NONE
|	@return 		NONE
|------------------------------------------------------------------------*/
function bindLitresForm()
{
    /** Variable Definition    */
    var srv_message = '';
    var error_cntr  = $('.error-msg');
    var submit_btn  = $('#litres-upd-btn');
    var litres_form = $('#litres-update');
    
    /** Event handler when form is submitted    */
    litres_form.submit(function() {
        
        /** Initialize form related Elements    */
        srv_message = '';
        submit_btn.prop('disabled', true).text('Verifying...');
        
        /** Verify and save data to database    */
        $.post($(this).attr('action'), litres_form.serialize(), function(srv_resp) {
            
            /** Data saved and verified */
            if (1 == srv_resp.sts) {
                location.reload();
            }
            /** Data validation failed  */
            else {
                /**	Formats the warnings received from the Server	*/
                for (idx = 0; idx < srv_resp.messages.length; idx++) {
                    srv_message += srv_resp.messages[idx] +'<br/>';
                }
                
                /**	Display the warning								*/
                error_cntr.show();
                error_cntr.html(srv_message);
            }
            
            /** Resets the Save button                              */
            submit_btn.prop('disabled', false).text('Submit');
            
        }, 'json')
        
        return false;
    });
}

/**------------------------------------------------------------------------
|	Binds the delete links to the click event
|--------------------------------------------------------------------------
|	@param [in]		NONE
|	@param [out] 	NONE
|	@return 		NONE
|------------------------------------------------------------------------*/
function bindDeleteLitres()
{
    /** Event handler when delete is clicked    */
    $('.delete-litres').click(function() {
        /** Changes the text to display deletion    */
        $(this).text('Deleting, please wait...');
        
        /** Issue an AJAX call to delete record in the database */
        $.get($(this).attr('href'), function(sts) {
            
            /** Delete was successful   */
            if (1 == sts) {
                location.reload();
            }
            /** Delete failed           */
            else {
                alert('An error occured while deleting the entry. Please try again.');
            }
        });
        
        return false; 
    });
}

/**------------------------------------------------------------------------
|	Creates the Bar Chart on the Dashboard
|--------------------------------------------------------------------------
|	@param [in]		NONE
|	@param [out] 	NONE
|	@return 		NONE
|------------------------------------------------------------------------*/
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
            /** Sump Level dashed line  */
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
                name: 'Safe Fill Limit',
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

/**------------------------------------------------------------------------
|	Creates the Area Graph on the Dashboard
|--------------------------------------------------------------------------
|	@param [in]		NONE
|	@param [out] 	NONE
|	@return 		NONE
|------------------------------------------------------------------------*/
function createForecastChart()
{
    /** Variable Definition */
    var forecast_litres = [];   /** Storage of all Forecast Litres from HTML    */
    var forecast_dates  = [];   /** Storage of all Forecast Dates from HTML     */
    var idx             = 0;
    
    /** Gets all the forecast litres from the UI and store it in a variable */
    $('.forecast_litres').each(function() {
        forecast_litres[idx]   = parseInt($(this).val());
        idx++;
    });
    
    /** Gets all the forecast dates from the UI and store it in a variable */
    idx = 0;
    
    $('.forecast_dates').each(function() {
        forecast_dates[idx]   = $(this).val();
        idx++;
    });
    
    /** Configure and draw the Chart    */
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
            text: forecast_litres.length+' Day Forecast',
            style: {
                'fontSize': '12px',
                'padding': '10px'
            }
        },
        
        /** Forecast Dates */
        xAxis: {
            categories: forecast_dates, /** Stored dates from the UI    */
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
            max: parseInt($('#tank_max_capacity').val()),   /** Sets the maximum capacity of the Tank   */
            tickInterval: 1000,                             /** Maximum capacity only works when TickInterval is set*/
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
        
        /** Soted Forecast litres from the UI   */
        series: [{
            name: 'Tank Capacity',
            data: forecast_litres
        }]
    });
}

/**------------------------------------------------------------------------
|	Binds the Datepicker elements to the JQuery Datepicker
|--------------------------------------------------------------------------
|	@param [in]		NONE
|	@param [out] 	NONE
|	@return 		NONE
|------------------------------------------------------------------------*/
function bindDatePickInput()
{
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        yearRange: '-50:+10',
        onChangeMonthYear: function(y, m, i) {
            var d = i.selectedDay;
            $(this).datepicker('setDate', new Date(y, m - 1, d));
        }
    });
}

/**------------------------------------------------------------------------
|	Binds the Update link elements to the click Event
|--------------------------------------------------------------------------
|	@param [in]		NONE
|	@param [out] 	NONE
|	@return 		NONE
|------------------------------------------------------------------------*/
function bindUpdateLink()
{
    /** Variable Definition */
    var update_link     = $('.update-litres');
    var update_button   = $('#litres-upd-btn');
    
    /** Event handler when an update link is clicked    */
    update_link.click(function() {
        
        /** Issue an AJAX call to fetch the data of the clicked link    */
        $.get($(this).attr('href'), function(srv_resp) {
            /** Change button text  */
            update_button.val('Save Changes');
            
            /** There is a delivery on the date */
            if (1 == srv_resp.delivery_made) {
                $('input[name="delivery_received"]').prop('checked', true);
            }
            /** No delivery on the date chosen  */
            else {
                $('input[name="delivery_received"]').prop('checked', false);
            }
            
            /** Puts the data of the chosen link in the UI  */
            $('input[name="update_id"]').val(srv_resp.update_id);
            $('input[name="litres"]').val(srv_resp.remaining_litres);
            $('input[name="reading_date"]').val(srv_resp.reading_date);
            $('input[name="before_delivery"]').val(srv_resp.before_delivery);
        }, 'json');
        
        return false;
    });
}

/** Initialize Script   */
initCustomerDashboard();

});