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
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    {{ Form::open(array('url' => 'admin/save-transaction-log', 'id' => 'save-transaction-form', 'class' => 'form-inline')) }}
                    
                        <div class="x_title">
                            <div class = 'col-md-3'>
                                <h2>Transaction Log <small></small></h2>
                            </div>
                            
                            <div class = 'col-md-9'>
                                <div class = 'pull-right'>
                                    <input class = 'form-control datepicker' type = 'text' name = 'from_date' placeholder = 'From date' value = '{{ date("Y-m-d", strtotime("-30 Days")) }}' />
                                    <input class = 'form-control datepicker' type = 'text' name = 'to_date' placeholder  = 'To date' value = '{{ date("Y-m-d") }}' />
                                    <button id = 'search-transaction-btn' class = 'btn-primary'> SEARCH </button>
                                </div>
                            </div>
                            
                            <div class="clearfix"></div>
                        </div>
                        
                        <div id="transcation-log" style = 'overflow-x: scroll; overflow-y: hidden;'>
                            <div class = 'col-md-12'>
                                <div class="alert alert-danger" role="alert" style = 'display: none;' id = 'upd-log-error'></div>
                            </div>
                            
                            <div class="x_content" id = 'transcation-list-cntr'>
                                

                            </div>
                        </div>
                        
                        <div class = 'pull-right' style = 'margin: 10px;'>
                            {{ Form::submit('SAVE', array('class' => 'btn btn-large btn-primary upd-transaction-btn', 'id' => 'upd-transaction-btn')) }}
                        </div>
                        
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')

<script>
    $(document).ready(function() {
        $('#search-transaction-btn').click(function() {
            $('#transcation-list-cntr').load(BaseURL+'/admin/logs-table', { 'from_date':$('input[name="from_date"]').val(), 'to_date': $('input[name="to_date"]').val() }, function() {
                
            });
            
            return false;
        });
        
        $('#search-transaction-btn').click();
        
        $('#save-transaction-form').submit(function() {
            var srv_message = '';
            var error_cntr  = $('#upd-log-error');
            
            error_cntr.hide();
            $('#upd-transaction-btn').prop('disabled', false).text('SAVE');
            
            $.post($(this).attr('action'), $(this).serialize(), function(srv_resp) {
                
                if (1 == srv_resp.sts) {
                    
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
                
                $('#upd-transaction-btn').prop('disabled', false).text('SAVE');
                
            }, 'json');
            
            return false;
        });
        
        $('body').on('change', '.dd_change', function() {
            var bullseye   = $(this).data('target');
            var target      = $('#'+bullseye);
            
            if ('' == target.val()) {
                target.val($(this).val());
            }
        });
        
    });
    
</script>

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
    
@stop