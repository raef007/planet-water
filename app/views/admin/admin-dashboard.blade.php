@extends('layouts.master')

@section('header')
	@parent
	<title>Tank Level Tracker</title>
@stop

@section('style')

@stop

@section('content')
    <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-8">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Map <small></small></h2>
                    
                    <div class="clearfix"></div>
                </div>
                
                <div id="map" style="width:100%;height:500px"></div>
            </div>
        </div>
        
        <div class="col-md-4 col-sm-4 col-xs-4">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Customers near sump within 15 days <small></small></h2>
                    
                    <div class="clearfix"></div>
                </div>
                
                <div class="x_content" id = 'historical-cntr'>

                    <table class="table table-bordered" id = 'historical-tb'>
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Est. Level</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($update_data as $update)
                            <tr>
                                <td> 
                                    <a href = '#'>
                                        {{ $update['company_name'] }}
                                    </a>
                                </td>
                                <td> {{ number_format($update['level'], 2) }} </td>
                                <td> {{ date('d M', strtotime($update['date_reach'])) }} </td>
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
<script>
function myMap() {
  var mapCanvas = document.getElementById("map");
  var mapOptions = {
    center: new google.maps.LatLng(14.632797, 120.971927), 
    zoom: 10
  }
  var map = new google.maps.Map(mapCanvas, mapOptions);
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?callback=myMap&key=AIzaSyAHMhxMhkz0yza4CFnfRAUwidDEh7ZMLNQ"></script>
@stop