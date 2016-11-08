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
    
    #map {
        width: 100%;
        height: 500px;
    }
    
    .numeric-data {
        text-align: right;
    }
    
    .left-fix-container {
        display: inline-block;
        width: 260px;
    }
    
    .left-fix-hr {
        width: 200px;
    }
    
    .left-fix-hdr {
        display: inline;
        width: 220px;
        float: left;
        padding: 3px;
        margin: 10px;
    }
    
    .left-hdr-cntr {
        display: inline;
        width: 200px;
    }
    
    .left-hdr-txt {
        text-align: right;
        width: 200px
    }
    
    .left-date-slot {
        width: 200px;
        border-bottom: solid 1px #000000;
        padding: 4px;
        margin: 2px;
        height: 30px;
    }
    
    .refill-day-active {
        color: #337ab7;
    }
    
    .left-day-total {
        float: right;
    }
    
    .company-delivery-cntr {
        white-space: nowrap;
        overflow-x: scroll;
        overflow-y: hidden;
    }
    
    .company-delivery-panel {
        display: inline-block;
    }
    
    .company-delivery-hdr {
        display: inline;
        width: 150px;
        float: left;
        border: solid 1px #13b36b;
        padding: 5px;
        margin: 5px;
    }
    
    .company-hdr-txt {
        width: 140px;
    }
    
    .company-hdr-txt2 {
        width: 140px;\
        text-align: right;
        padding-right: 12px;
    }
    
    .company-delivery-hr {
        width: 130px;
    }
    
    .delivery-slot {
        text-align: right;
        cursor: pointer;
        width: 130px;
        border-bottom: solid 1px #000000;
        padding: 4px;
        margin: 2px;
        height: 30px;
    }
</style>
@stop

@section('content')
    <input type = 'hidden' id = 'current-vehicle' value = '{{ $vehicle_id }}'>
    
    <div class="row">
        @if (0)
        <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Map <small></small></h2>
                    
                    <div class="clearfix"></div>
                </div>
                
                <div id="map"></div>
            </div>
        </div>
        @endif
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Scheduled Delivery <small></small></h2>
                        
                        <div class="clearfix"></div>
                    </div>
                    
                    <div id="scheduled-delivery">
                        <div class="x_content" id = 'delivery-list-cntr'>
                            <table class="table table-bordered" id = 'delivery-list-cntr-tb'>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Company</th>
                                        <th>Volume</th>
                                        <th>Total Volume</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php $ctr = 0; ?>
                                    @foreach ($deliveries as $delivery_date)
                                    @for ($idx = 0; count($delivery_date) > $idx; $idx++)
                                    <tr>
                                        @if ($idx == 0)
                                        <td rowspan = "{{ count($delivery_date) }}">
                                            {{ date ('M d', strtotime($delivery_date[$idx]->delivery_date)) }}
                                        </td>
                                        @endif
                                        <td> {{ $delivery_date[$idx]->tank->company_name }} </td>
                                        <td class = 'numeric-data'> @if (STR_EMPTY != $delivery_date[$idx]->volume_manual) {{ number_format($delivery_date[$idx]->volume_manual, 2) }} @endif </td>
                                        @if ($idx == 0)
                                        <td  class = 'numeric-data' rowspan = "{{ count($delivery_date) }}"> @if (STR_EMPTY != $totals[$ctr]) {{ number_format($totals[$ctr], 2) }} @endif</td>
                                        @endif
                                    </tr>
                                    @endfor
                                    <?php $ctr++; ?>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
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
                                    <th>Sump Level</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach ($update_data as $update)
                                <tr>
                                    <td> 
                                        {{ $update['company_name'] }}
                                    </td>
                                    <td class = 'numeric-data'> {{ number_format($update['level'], 2) }} </td>
                                    <td class = 'numeric-data'> {{ number_format($update['sump_level'], 2) }} </td>
                                    <td> {{ date('d M', strtotime($update['date_reach'])) }} </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class = 'row'>
        <div class = 'col-md-3'>
            <div class="x_panel" class = 'left-fix-container'>
                <div class = 'cat-header'><h3>Days</h3></div>
                <div class = 'left-fix-hdr'>
                    <div class = 'left-hdr-cntr'>
                        <div class = 'left-hdr-txt'> <b>Date reach Sump </b></div> 
                        <div class = 'left-hdr-txt'> <b>Company Name </b></div>
                        <div class = 'left-hdr-txt'> <b>Delivery Date </b></div>
                        <div class = 'left-hdr-txt'> <b>Purchase No. </b></div>
                        <div class = 'left-hdr-txt'> <b>Volume (Actual) </b></div>
                        <div class = 'left-hdr-txt'> <b>Volume (Round down) </b></div>
                        <div class = 'left-hdr-txt'> <b>Safe Fill </b></div>
                    </div>
                    
                    <div class = 'left-fix-hr'> <hr/> </div>
                    
                    <div id = 'date-list'>
                        <div id = 'reload-date-list'>
                            <?php $ctr = 0; ?>
                            @for ($days = 0; 60 > $days; $days++)
                            @if ((0 == date('w', strtotime('+ '.$days.' Days'))) || (6 == date('w', strtotime('+ '.$days.' Days'))))
                                <?php $bg_color = '#EFEFEF'; ?>
                            @else
                                <?php $bg_color = '#FFFFFF'; ?>
                            @endif
                            <div class = 'left-date-slot' style = 'background-color: {{ $bg_color }};'>
                                <span>
                                    <a class = 'add-refill-days' href = "{{ URL::To('admin/add-refill-day/'.$vehicle_id) }}" data-refill-date = "{{ date('Y-m-d', strtotime('+ '.$days.' Days')) }}">
                                        @if (in_array(date('Y-m-d', strtotime('+ '.$days.' Days')), $refills))
                                        <i data-refill = '1' class = 'fa fa-tachometer refill-day-active'></i>
                                        @else
                                        <i data-refill = '0' class = 'fa fa-tachometer'></i>
                                        @endif
                                    </a>
                                    
                                    <b>{{ date('D d M', strtotime('+ '.$days.' Days')) }} </b>
                                </span>
                                
                                <span class = 'left-day-total'>
                                    @if (isset($deliveries[$ctr]))
                                        @if (date('Y-m-d', strtotime($deliveries[$ctr][0]->delivery_date)) == date('Y-m-d', strtotime('+ '.$days.' Days')))
                                            {{ number_format($totals[$ctr], 2) }} 
                                            <?php $ctr++; ?>
                                        @endif
                                    @endif
                                </span>

                                &nbsp;
                                
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class = 'col-md-9 company-delivery-cntr'>
            @foreach ($categories as $category)
            @if (isset($companies[$category->category_label]))
                <?php $cat_width = count($companies[$category->category_label]) * 180; ?>
                <div class="x_panel company-delivery-panel" style = 'width: {{ $cat_width }}px;'>
                    <div class = 'cat-header'><h3>{{ $category->category_label }}</h3></div>
                    
                    @foreach ($companies[$category->category_label] as $company)
                    <div class = 'company-delivery-hdr'>
                        <div id = "company-hdr-{{ $company['company']['tank_id'] }}">
                            <div class = "company-hdr-txt reload-hdr-{{ $company['company']['tank_id'] }}"> {{ $company['forecast']['date_sump'] }}</div>
                            <div class = "company-hdr-txt reload-hdr-{{ $company['company']['tank_id'] }}"> {{ $company['company']['company_name'] }}</div>
                            @if ($company['delivery'])
                            <div class = "company-hdr-txt reload-hdr-{{ $company['company']['tank_id'] }}"> {{ date('M d', strtotime($company['delivery']['delivery_date'])) }}</div>
                            <div class = "company-hdr-txt reload-hdr-{{ $company['company']['tank_id'] }}"> @if (STR_EMPTY != $company['delivery']['volume_manual']) {{ $company['delivery']['purchase_number'] }} @else --- @endif</div>
                            <div class = "company-hdr-txt2 reload-hdr-{{ $company['company']['tank_id'] }}"> @if (STR_EMPTY != $company['delivery']['volume_manual']) {{ number_format($company['delivery']['volume_manual'], 2) }} @else --- @endif</div>
                            <div class = "company-hdr-txt2 reload-hdr-{{ $company['company']['tank_id'] }}"> {{ number_format(((int)(($company['specifications']['safety_limit'] - $company['delivery']['remaining_litres']) / 100)) * 100, 2) }}</div>
                            @else
                            <div class = "company-hdr-txt reload-hdr-{{ $company['company']['tank_id'] }}"> --- </div>
                            <div class = "company-hdr-txt reload-hdr-{{ $company['company']['tank_id'] }}"> --- </div>
                            <div class = "company-hdr-txt reload-hdr-{{ $company['company']['tank_id'] }}"> --- </div>
                            <div class = "company-hdr-txt reload-hdr-{{ $company['company']['tank_id'] }}"> --- </div>
                            @endif
                            <div class = "company-hdr-txt2 reload-hdr-{{ $company['company']['tank_id'] }}"> {{ number_format($company['specifications']['safety_limit'], 2) }}</div>
                        </div>
                        
                        <div class = 'company-delivery-hr'> <hr/> </div>
                        
                        <div class = 'delivery-slot-list'>
                            <?php $days = 0; ?>
                            @foreach ($company['forecast']['forecast_dump'] as $forecast)
                            
                            @if (date('Y-m-d', strtotime($company['forecast']['date_sump'])) == date('Y-m-d', strtotime($forecast['date'])))
                            <div class = 'delivery-slot' style = 'background-color: red; color: #FFFFFF;' data-tank-id = "{{ $company['company']['tank_id'] }}" data-litres = "{{ $forecast['litres'] }}" data-delivery = "{{ date('Y-m-d', strtotime('+ '.$days.' Days')) }}"> {{ number_format($forecast['litres'], 2) }} </div>
                            @elseif (FALSE !== array_search(date('Y-m-d', strtotime($forecast['date'])), $company['deliveries']['delivery_dates']))
                            
                                @if ($vehicle_id == $company['deliveries']['vehicles'][array_search(date('Y-m-d', strtotime($forecast['date'])), $company['deliveries']['delivery_dates'])])
                                <div class = 'delivery-slot' style = 'background-color: green; color: #FFFFFF;' data-tank-id = "{{ $company['company']['tank_id'] }}" data-litres = "{{ $forecast['litres'] }}" data-delivery = "{{ date('Y-m-d', strtotime('+ '.$days.' Days')) }}"> {{ number_format($forecast['litres'], 2) }} </div>
                                @else
                                <div class = 'delivery-slot' style = 'background-color: purple; color: #FFFFFF;' data-tank-id = "{{ $company['company']['tank_id'] }}" data-litres = "{{ $forecast['litres'] }}" data-delivery = "{{ date('Y-m-d', strtotime('+ '.$days.' Days')) }}"> {{ number_format($forecast['litres'], 2) }} </div>
                                @endif
                            @else
                            <div class = 'delivery-slot' data-tank-id = "{{ $company['company']['tank_id'] }}" data-litres = "{{ $forecast['litres'] }}" data-delivery = "{{ date('Y-m-d', strtotime('+ '.$days.' Days')) }}" data-container="body" data-toggle="tooltip" data-placement="bottom" title="{{ $category->category_label }}: {{ $company['company']['company_name'] }}, Safety Fill: {{ number_format($company['specifications']['safety_limit'], 2) }}"> {{ number_format($forecast['litres'], 2) }} </div>
                            @endif
                            <?php $days++ ?>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                    
                </div>
            @endif
            @endforeach
        </div>
    </div>
    
    @include('modal.modal-create-delivery')
    @include('modal.modal-create-refill')
@stop

@section('javascript')
<script src="{{ URL::asset('scripts/custom/delivery-planner.js') }}"></script>
@stop