@extends('layouts.master')

@section('header')
	@parent
	<title>Tank Level Tracker</title>
@stop

@section('style')

@stop

@section('content')
    <div class="row">
        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="x_panel tile" style = 'background: #2A3F54;'>
                <div class="x_title">
                    <h2> {{ $tank_info->company_name }} </h2>
                    
                    <div class="clearfix"></div>
                </div>
                
                <div class="x_content">
                    <div class="dashboard-widget-content">
                        
                        @if (2 == Auth::user()->user_type)
                        <div>
                            <div class = 'col-md-12'>
                                <div class="alert alert-danger error-msg" role="alert" style = 'display: none;'></div>
                            </div>
                            {{ Form::open(array('url' => 'admin/update/customer-litres', 'id' => 'litres-update')) }}
                            
                            <div class = 'form-group'>
                                {{ Form::text('litres', '', array('class' => 'form-control', 'placeholder' => "Remaining Litres")) }}
                            </div>
                            
                            <div class = 'form-group'>
                                {{ Form::text('reading_date', '', array('class' => 'form-control datepicker', 'placeholder' => "Reading Date")) }}
                            </div>
                            
                            <div class = 'form-group'>
                                <center>
                                    <input type = 'checkbox' value = '1' name = 'delivery_received'/>
                                    <span> Delivery Received Today </span>
                                </center>
                            </div>
                            
                            <div class = 'form-group'>
                                {{ Form::text('before_delivery', '', array('class' => 'form-control', 'placeholder' => "Before Fill")) }}
                            </div>
                            
                            <div class = 'form-group'>
                                <center>{{ Form::submit('Add Entry', array('class' => 'btn btn-large btn-primary', 'id' => 'litres-upd-btn')) }}</center>
                            </div>
                            
                            
                            {{ Form::hidden('update_id', '0', array('class' => 'form-control')) }}
                            {{ Form::hidden('tank_id', $tank->tank_id, array('class' => 'form-control')) }}
                            
                            {{ Form::close() }}
                        </div>
                        @else
                        <div>
                            <div class = 'col-md-12'>
                                <div class="alert alert-danger error-msg" role="alert" style = 'display: none;'></div>
                            </div>
                            @if (STS_NG == $input_today)
                            {{ Form::open(array('url' => 'add/litres-update', 'id' => 'litres-update')) }}
                            
                            <div class = 'form-group'>
                                {{ Form::text('litres', '', array('class' => 'form-control', 'placeholder' => "Current Level (Litres)")) }}
                            </div>
                            
                            <div class = 'form-group'>
                                {{ Form::text('initials', '', array('class' => 'form-control', 'placeholder' => "Your Initials")) }}
                            </div>
                            
                            <div class = 'form-group'>
                                <center>
                                    <input type = 'checkbox' value = '1' name = 'delivery_received'/>
                                    <span> Delivery Received Today </span>
                                </center>
                            </div>
                            
                            <div class = 'form-group'>
                                <center>{{ Form::submit('Submit', array('class' => 'btn btn-large btn-primary', 'id' => 'litres-upd-btn')) }}</center>
                            </div>
                            {{ Form::close() }}
                            @else
                                <div class = 'col-md-12'>
                                    <div>
                                        <p>Thank you for keeping your reading Updated! </p>
                                        
                                        <p> <br/>You may delete your entry on the Historical data table below if the litres you have entered is incorrect </p>
                                    </div>
                                </div>
                            @endif
                            
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="x_panel tile">
                
                <div class="x_content">
                    <div class="dashboard-widget-content">
                        
                        <div class="dashboard-widget-content">
                            <div id = 'tank-sts-chart'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-sm-4 col-xs-12">
            <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
            <div>
                <div id="forecast-chart"></div>
            </div>
        </div>
    </div>
    
    <div class = 'row tile_count'>
        <div class="col-md-6">
            <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-cart-arrow-down"></i> Date of Last Delivery</span>
                @if (STR_EMPTY != $delivery_date)
                <div class="count">{{ date('d M', strtotime($delivery_date)) }}</div>
                @else
                <div class="count">N/A</div>
                @endif
            </div>

            <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-tachometer"></i> Date of Last Reading</span>
                @if (STR_EMPTY != $reading_date)
                <div class="count">{{ date('d M', strtotime($reading_date)) }}</div>
                @else
                <div class="count">N/A</div>
                @endif
            </div>

            <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-line-chart"></i> Average Daily Usage</span>
                <div class="count">{{ number_format($average_usage, 2) }}</div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-calendar-minus-o"></i> Date reach sump</span>
                <div class="count">{{ $date_sump }}</div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-calendar-times-o"></i> Days until sump</span>
                <div class="count">{{ $days_sump }}</div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-calculator"></i> Litres until sump</span>
                <div class="count">{{ number_format($litres_before_sump, 0) }}</div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Historical Data <small></small></h2>
                    
                    <div class="clearfix"></div>
                </div>
                
                <div class="x_content" id = 'historical-cntr'>

                    <table class="table table-bordered" id = 'historical-tb'>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Litres</th>
                                <th>Delivery</th>
                                @if (2 == Auth::user()->user_type)
                                <th>Before Fill</th>
                                @else
                                <th>User Initials</th>
                                @endif
                                <th>% until Sump</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($storage_updates as $update)
                            <tr>
                                <th scope="row">{{ date('d M Y', strtotime($update->reading_date)) }}</th>
                                <td>{{ number_format($update->remaining_litres, 2) }}</td>
                                <td>
                                    @if (STS_OK == $update->delivery_made)
                                        <center><i class = 'fa fa-circle' style = 'color: #7cb5ec;'> </i></center>
                                    @endif
                                </td>
                                @if (2 == Auth::user()->user_type)
                                <td>{{ $update->before_delivery }}</td>
                                @else
                                <td>{{ $update->initials }}</td>
                                @endif
                                
                                <td>
                                    {{ number_format($update->before_sump, 2) }}%
                                    @if (date('Y-m-d') == date('Y-m-d', strtotime($update->reading_date)))
                                    <a class = 'delete-litres' href = '{{ URL::to("delete/litres/".$update->update_id) }}'>
                                        <span style = 'color:red;'>
                                            <i class = 'fa fa-times'> </i> DELETE 
                                        </span>
                                    </a>
                                    @endif
                                    
                                    @if (2 == Auth::user()->user_type)
                                        <a class = 'update-litres' href = '{{ URL::to("admin/edit/litres/".$update->update_id) }}'>
                                            <span style = 'color:#7cb5ec;'>
                                                <i class = 'fa fa-edit'> </i>
                                            </span>
                                        </a>
                                        <a class = 'delete-litres' href = '{{ URL::to("delete/litres/".$update->update_id) }}'>
                                            <span style = 'color:red;'>
                                                <i class = 'fa fa-times'> </i>
                                            </span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Forecast Data <small></small></h2>
                        
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="x_content" id = 'historical-cntr'>

                        <table class="table table-bordered" id = 'historical-tb'>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Litres</th>
                                    <th>% until Sump</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach ($forecast_dump as $forecast)
                                <tr>
                                    <th scope="row">{{ $forecast['date'] }}</th>
                                    <td>{{ number_format($forecast['litres'], 2) }}</td>
                                    <td>{{ number_format($forecast['sump'], 2) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @foreach ($forecast_chart_data as $forecast_data)
    <input type = 'hidden' class = 'forecast_litres' value = "{{ $forecast_data['litres'] }}" />
    <input type = 'hidden' class = 'forecast_dates' value = "{{ $forecast_data['date'] }}" />
    @endforeach
    
    <input type = 'hidden' id = 'tank_max_capacity' value = '{{ $tank->maximum_capacity }}' />
    <input type = 'hidden' id = 'bar_safety_fill' value = '{{ $bar_safety_fill }}' />
    <input type = 'hidden' id = 'sump_level' value = '{{ $tank->sump_level }}' />
    <input type = 'hidden' id = 'current_level' value = '{{ ($litres_today) }}' />
    
@stop

@section('javascript')
    <script src="{{ URL::asset('scripts/custom/cust-dashboard.js') }}"></script>
    <script>
        $(document).ready(function() {
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
        });
    </script>
    
    <script>
        $(document).ready(function() {
            var update_link     = $('.update-litres');
            var update_button   = $('#litres-upd-btn');
            
            update_link.click(function() {
                
                $.get($(this).attr('href'), function(srv_resp) {
                    update_button.val('Save Changes');
                    
                    if (1 == srv_resp.delivery_made) {
                        $('input[name="delivery_received"]').prop('checked', true);
                    }
                    else {
                        $('input[name="delivery_received"]').prop('checked', false);
                    }
                    
                    $('input[name="update_id"]').val(srv_resp.update_id);
                    $('input[name="litres"]').val(srv_resp.remaining_litres);
                    $('input[name="reading_date"]').val(srv_resp.reading_date);
                    $('input[name="before_delivery"]').val(srv_resp.before_delivery);
                }, 'json');
                
                return false;
            });
        });
    </script>
@stop