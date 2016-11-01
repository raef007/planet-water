<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
        <style>
            .fifty-perc {
                width: 48%;
                display: inline-block;
            }
        </style>
	</head>
	<body>
        <?php $date_proc    = '0000-00-00'; $ctr = 0;?>
        <div>
        @foreach ($deliveries as $delivery)
            @if (strtotime($date_proc) != strtotime($delivery->delivery_date))
            <?php $date_proc    = $delivery->delivery_date; ?>
            </div>
            <div style = 'width: 500px;'>
                <div><hr/></div>
                <div class = 'fifty-perc'> <b> Subject </b> </div>
                <div class = 'fifty-perc'> <b> Deliveries: {{ date('D d m', strtotime($delivery->delivery_date)) }} </b> </div>
                <div><br/></div>
                
                <div> Hello, </div>
                <div><br/></div>
                
                <div> Please process below orders. </div>
                
                <div><br/></div>
                
                <div class = 'fifty-perc'> <b> Batch No. </b> </div>
                <div class = 'fifty-perc'> <b> {{ $delivery->company_info->batch_number }} </b> </div>
                
                <div><hr/></div>
            @endif

                <div class = 'fifty-perc'><b>Customer:</b> </div>
                <div class = 'fifty-perc'>{{ $delivery->company_info->company_name }}</div>
                
                <div class = 'fifty-perc'><b>Delivery Date:</b> </div>
                <div class = 'fifty-perc'>{{ date('d M', strtotime($delivery->delivery_date)) }}</div>
                
                <div class = 'fifty-perc'><b>Purchase Number:</b> </div>
                <div class = 'fifty-perc'>{{ $delivery->purchase_number }}</div>
                
                <div class = 'fifty-perc'><b>Order Quantity:</b> </div>
                <div class = 'fifty-perc'>{{ number_format($delivery->volume_manual, 2) }}</div>
                
                <div class = 'fifty-perc'><b>Address:</b> </div>
                <div class = 'fifty-perc'>{{ $delivery->company_info->address.', '.$delivery->company_info->city.', '.$delivery->company_info->state }}</div>
                
                <div class = 'fifty-perc'>
                    <b>
                        Contact Person:<br/>
                        Contact No.:<br/>
                    </b>
                </div>
                
                <div class = 'fifty-perc'>
                    {{ $delivery->company_info->contact_person }}<br/>
                    {{ $delivery->company_info->contact_number }}<br/>
                </div>
                
                <div class = 'fifty-perc'><b>Delivery Times:</b> </div>
                <div class = 'fifty-perc'>{{ $delivery->company_info->delivery_time }} </div>
                
                <div class = 'fifty-perc'><b>Comments:</b> </div>
                <div class = 'fifty-perc'>{{ $comments[$ctr] }}</div>
                
                <div><br/><br/></div>
            
            <?php $ctr++; ?>
        @endforeach
	</body>
</html>