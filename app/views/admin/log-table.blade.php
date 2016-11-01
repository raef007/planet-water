                                <table class="table table-bordered" id = 'transaction-cntr-tb'>
                                    <thead>
                                        <tr>
                                            <th> &nbsp; </th>
                                            <th>Customer</th>
                                            <th>Purchase Order No.</th>
                                            <th>Product</th>
                                            <th>Batch</th>
                                            <th>Quantity</th>
                                            <th>Delivery Docket</th>
                                            <th>Planned Litres</th>
                                            <th>Invoice No.</th>
                                            <th>Actual Litres</th>
                                            <th>Delivery Date</th>
                                            <th>Comments</th>
                                            <th>Order Date</th>
                                            <th>Litres at Order</th>
                                            <th>Before Fill</th>
                                            <th>After Fill</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        @foreach ($deliveries as $delivery)
                                        <tr id = 'trans_row_{{ $delivery->transaction_id }}'>
                                            {{ Form::hidden('transaction_id[]', $delivery->transaction_id) }}
                                            {{ Form::hidden('tank_id[]', $delivery->tank_id) }}
                                            {{ Form::hidden('delivery_date[]', date('Y-m-d', strtotime($delivery->delivery_date))) }}
                                            
                                            <td>
                                                <a class = 'delete-transaction' href = '{{ URL::To("/admin/delete/log/".$delivery->transaction_id) }}' data-target = '#trans_row_{{ $delivery->transaction_id }}'>
                                                    <i class = 'fa fa-times' style = 'color: red;'> </i>
                                                </a>
                                            </td>
                                            <td> {{ $delivery->company_name }} </td>
                                            <td> {{ $delivery->purchase_number }} </td>
                                            <td> <input type = 'text' name = 'product[]' value = '{{ $delivery->product }}'/> </td>
                                            <td> <input type = 'text' name = 'batch[]' value = '{{ $delivery->batch }}'/> </td>
                                            <td> <input type = 'text' name = 'quantity[]' value = '@if (STR_EMPTY != $delivery->quantity) {{ number_format((float)$delivery->quantity, 2) }} @endif'/> </td>
                                            <td> <input data-target = 'dd_{{ $delivery->transaction_id }}' class = 'dd_change' type = 'text' name = 'delivery_docket[]' value = '{{ $delivery->delivery_docket }}'/> </td>
                                            <td style = 'text-align: right;'> {{ number_format($delivery->planned_volume, 2) }} </td>
                                            <td> <input id = 'dd_{{ $delivery->transaction_id }}' type = 'text' name = 'invoice_number[]' value = '{{ $delivery->invoice_number }}'/> </td>
                                            <td> <input type = 'text' name = 'actual_volume[]' value = '@if (STR_EMPTY != $delivery->actual_volume) {{ number_format((float)$delivery->actual_volume, 2) }} @endif'/> </td>
                                            <td> {{ date("D d M", strtotime($delivery->delivery_date)) }} </td>
                                            <td> <input type = 'text' name = 'remarks[]' value = '{{ $delivery->remarks }}'/> </td>
                                            <td> <input type = 'text' name = 'order_date[]' class = 'datepicker-log' value = '@if ("0000-00-00 00:00:00" != $delivery->order_date) {{ date("Y-m-d", strtotime($delivery->order_date)) }} @endif'/> </td>
                                            <td> <input type = 'text' name = 'remaining_litres[]' value = '@if (STR_EMPTY != $delivery->remaining_litres) {{ number_format((float)$delivery->remaining_litres, 2) }} @endif'/> </td>
                                            <td> <input type = 'text' name = 'before_fill[]' value = '@if (STR_EMPTY != $delivery->before_fill) {{ number_format((float)$delivery->before_fill, 2) }} @endif'/> </td>
                                            <td> <input type = 'text' name = 'after_fill[]' value = '@if (STR_EMPTY != $delivery->after_fill) {{ number_format((float)$delivery->after_fill, 2) }} @endif'/> </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
<script>
    $(document).ready(function() {
        $(".datepicker-log").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: '-50:+10',
            onChangeMonthYear: function(y, m, i) {
                var d = i.selectedDay;
                $(this).datepicker('setDate', new Date(y, m - 1, d));
            }
        });
        
        $('body').on('click', '.delete-transaction', function() {
            var link_clicked    = $(this);
            
            var conf_delete     = $("<div> Are you sure you want to delete this record? </div>").dialog({
                closeOnEscape: false,
                draggable: false,
                resizable: false,
                modal: true,
                open: function(event, ui) { 
                    /** Remove the close button */
                    $(".ui-dialog-titlebar-close").hide();
                    $(".ui-dialog-title").append('<span id = "delete_hdr"> <i class = "fa fa-exclamation-circle"> </i> DELETE RECORD </span>');
                },
                buttons: [
                    {
                        text: 'Cancel',
                        click: function() {
                            $('#delete_hdr').remove();
                            $(this).dialog('destroy');
                        }
                    },{
                        text: 'Yes',
                        click: function() {
                            $(this).dialog('close');
                            $(this).dialog('destroy');
                            
                            $.get(link_clicked.attr('href'), function() {
                                $(link_clicked.data('target')).remove();
                            });
                        }
                    }
                ]
            });
            
            return false;
        });
    });
</script>