@extends('layouts.master')

@section('header')
	@parent
	<title>Tank Level Tracker</title>
@stop

@section('style')
<style>
    .affix {
        top: 0px;
        background-color: #FFFFFF;
    }
</style>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Scheduled Delivery <small></small></h2>
                    
                    <div class="clearfix"></div>
                </div>
                
                <div id="scheduled-deliver"></div>
            </div>
        </div>
        
        <div class="col-md-6 col-sm-6 col-xs-6">
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
    
    <div class = 'row'>
        <div class = 'col-md-3'>
            <div class="x_panel" style = 'display:inline-block; width: 260px;'>
                <div class = 'cat-header'><h3>Days</h3></div>
                <div class = 'pull-right' style = 'display:inline; width: 170px; float: left; padding: 5px; margin: 5px;'>
                    <div class = 'pull-right' style = 'display:inline; width: 170px;' data-spy="affix" data-offset-top="300" >
                        <div class = 'pull-right' style = 'text-align: right; width: 150px'> <b>Date reach Sump </b></div> 
                        <div class = 'pull-right' style = 'text-align: right; width: 150px'> <b>Company Name </b></div>
                        <div class = 'pull-right' style = 'text-align: right; width: 150px'> <b>Safe Fill </b></div>
                        <div class = 'pull-right' style = 'text-align: right; width: 150px'> <b>Delivery Date </b></div>
                        <div class = 'pull-right' style = 'text-align: right; width: 150px'> <b>Purchase No. </b></div>
                        <div class = 'pull-right' style = 'text-align: right; width: 150px'> <b>Volume (Actual) </b></div>
                        <div class = 'pull-right' style = 'text-align: right; width: 150px'> <b>Volume (Round down) </b></div>
                    </div>
                    
                    <div class = 'pull-right' style = 'text-align: right; width: 150px'> <hr/> </div>
                    <div>
                        @for ($days = 0; 60 > $days; $days++)
                        <div class = 'pull-right' style = 'text-align: right; width: 150px; border-bottom: solid 1px #000000; padding: 4px; margin: 1px; height: 30px;'><b>{{ date('M d', strtotime('+ '.$days.' Days')) }}</b></div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
        
        <div class = 'col-md-9' style = 'white-space: nowrap; overflow: scroll;'>
            @foreach ($categories as $category)
            <?php $cat_width = count($companies[$category->category_label]) * 180; ?>
                <div class="x_panel" style = 'display:inline-block; width: {{ $cat_width }}px;'>
                    <div class = 'cat-header'><h3>{{ $category->category_label }}</h3></div>
                    
                    @foreach ($companies[$category->category_label] as $company)
                    <div style = 'display:inline; width: 150px; float: left; border: solid 1px #13b36b; padding: 5px; margin: 5px;'>
                        <div data-spy="affix" data-offset-top="300" >
                            <div style = 'width: 150px'> {{ $company['forecast']['date_sump'] }}</div>
                            <div style = 'width: 150px'> {{ $company['company']['company_name'] }}</div>
                            <div style = 'width: 150px'> {{ number_format($company['specifications']['safety_limit'], 2) }}</div>
                            @if ($company['delivery'])
                            <div style = 'width: 150px'> {{ date('M d', strtotime($company['delivery']['delivery_date'])) }}</div>
                            <div style = 'width: 150px'> {{ $company['delivery']['purchase_number'] }}</div>
                            <div style = 'width: 150px'> {{ number_format($company['delivery']['volume_manual'], 2) }}</div>
                            <div style = 'width: 150px'> {{ number_format(($company['specifications']['safety_limit'] - (round($company['delivery']['remaining_litres'] / 100, 0) * 100)), 2) }}</div>
                            @else
                            <div style = 'width: 150px'> --- </div>
                            <div style = 'width: 150px'> --- </div>
                            <div style = 'width: 150px'> --- </div>
                            <div style = 'width: 150px'> --- </div>
                            @endif
                        </div>
                        
                        <div style = 'width: 130px;'> <hr/> </div>
                        
                        <div>
                            @foreach ($company['forecast']['forecast_dump'] as $forecast)
                            @if (date('M d', strtotime($company['forecast']['date_sump'])) == date('M d', strtotime($forecast['date'])))
                            <div style = 'width: 130px; background-color: red; color: #FFFFFF; border-bottom: solid 1px #000000; padding: 4px; margin: 2px; height: 30px;'> {{ number_format($forecast['litres'], 2) }} </div>
                            @elseif (date('M d', strtotime($company['delivery']['delivery_date'])) == date('M d', strtotime($forecast['date'])))
                            <div style = 'width: 130px; background-color: green; color: #FFFFFF; border-bottom: solid 1px #000000; padding: 4px; margin: 2px; height: 30px;'> {{ number_format($forecast['litres'], 2) }} </div>
                            @else
                            <div style = 'width: 130px; border-bottom: solid 1px #000000; padding: 4px; margin: 2px; height: 30px;'> {{ number_format($forecast['litres'], 2) }} </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                    
                </div>
            @endforeach
        </div>
    </div>
@stop

@section('javascript')

@stop