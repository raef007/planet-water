@extends('layouts.master')

@section('header')
	@parent
	<title>Tank Level Tracker</title>
@stop

@section('style')

@stop

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Customer List <small></small></h2>
                    
                    <div class="clearfix"></div>
                </div>
                
                <div class="x_content" id = 'historical-cntr'>

                    <table class="table table-bordered" id = 'historical-tb'>
                        <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>City</th>
                                <th>Contact #</th>
                                <th>Tank Size</th>
                                <th>Safety Fill</th>
                                <th>Sump Level</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($tanks as $tank)
                            <tr>
                                <td> {{ $tank->information->company_name }} </td>
                                <td> {{ $tank->information->city }} </td>
                                <td> {{ $tank->information->contact_number }} </td>
                                <td> {{ number_format($tank->maximum_capacity, 2) }} </td>
                                <td> {{ number_format($tank->safety_limit, 2) }} </td>
                                <td> {{ number_format($tank->sump_level, 2) }} </td>
                                <td>
                                    <a href = '{{ URL::To("/admin/customer/".$tank->tank_id) }}'>
                                        <i class = 'fa fa-bar-chart' style = 'color: green;'> </i>
                                    </a>
                                    &nbsp;
                                    <a href = '{{ URL::To("/admin/edit/customer/".$tank->tank_id) }}'>
                                        <i class = 'fa fa-pencil-square-o' style = 'color: blue;'> </i>
                                    </a>
                                    &nbsp;
                                    <a href = '{{ URL::To("/admin/archive/customer/".$tank->tank_id) }}'>
                                        <i class = 'fa fa-archive' style = 'color: orange;'> </i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Archived List <small></small></h2>
                    
                    <div class="clearfix"></div>
                </div>
                
                <div class="x_content" id = 'historical-cntr'>

                    <table class="table table-bordered" id = 'historical-tb'>
                        <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>City</th>
                                <th>Contact #</th>
                                <th>Tank Size</th>
                                <th>Safety Fill</th>
                                <th>Sump Level</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($archives as $tank)
                            <tr>
                                <td> {{ $tank->information->company_name }} </td>
                                <td> {{ $tank->information->city }} </td>
                                <td> {{ $tank->information->contact_number }} </td>
                                <td> {{ number_format($tank->maximum_capacity, 2) }} </td>
                                <td> {{ number_format($tank->safety_limit, 2) }} </td>
                                <td> {{ number_format($tank->sump_level, 2) }} </td>
                                <td>
                                    <a href = '{{ URL::To("/admin/customer/".$tank->tank_id) }}'>
                                        <i class = 'fa fa-bar-chart' style = 'color: green;'> </i>
                                    </a>
                                    &nbsp;
                                    <a href = '{{ URL::To("/admin/edit/customer/".$tank->tank_id) }}'>
                                        <i class = 'fa fa-pencil-square-o' style = 'color: blue;'> </i>
                                    </a>
                                    &nbsp;
                                    <a href = '{{ URL::To("/admin/archive/customer/".$tank->tank_id) }}'>
                                        <i class = 'fa fa-archive' style = 'color: orange;'> </i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')

@stop