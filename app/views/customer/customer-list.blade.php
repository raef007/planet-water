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
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($tanks as $tank)
                            <tr>
                                <td> 
                                    <a href = '{{ URL::To("/admin/customer/".$tank->tank_id) }}'>
                                        {{ $tank->information->company_name }}
                                    </a>
                                </td>
                                <td> {{ $tank->information->city }} </td>
                                <td> {{ $tank->information->contact_number }} </td>
                                <td> {{ number_format($tank->maximum_capacity, 2) }} </td>
                                <td> {{ number_format($tank->safety_limit, 2) }} </td>
                                <td> {{ number_format($tank->sump_level, 2) }} </td>
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