<?php

class CronController extends BaseController
{
    public function recordTransactionFromPast()
    {
        $date_now   = date('Y-m-d');
        
        $delivery_db    = new VehicleDelivery;
        $deliveries     = $delivery_db->getDeliveriesByDate($date_now);
        
        foreach ($deliveries as $delivery) {
            $data_update    = array(
                'status'    => 2
            );
            
            if (NULL != $delivery->company_info->remarks) {
                $remarks    = $delivery->company_info->remarks;
            }
            else {
                $remarks    = STR_EMPTY;
            }
            
            $data_add   = array(
                'tank_id'           => $delivery->tank_id,
                'company_name'      => $delivery->company_info->company_name,
                'address'           => $delivery->company_info->address.', '.$delivery->company_info->city.', '.$delivery->company_info->state,
                'purchase_number'   => $delivery->purchase_number,
                'product'           => STR_EMPTY,
                'batch'             => $delivery->batch_number,
                'quantity'          => STR_EMPTY,
                'delivery_docket'   => STR_EMPTY,
                'planned_volume'    => $delivery->volume_manual,
                'invoice_number'    => STR_EMPTY,
                'actual_volume'     => STR_EMPTY,
                'delivery_date'     => $delivery->delivery_date,
                'remarks'           => $remarks,
                'order_date'        => STR_EMPTY,
                'remaining_litres'  => STR_EMPTY,
                'delivered_by'      => STR_EMPTY,
                'after_fill'        => STR_EMPTY,
                'status'            => STS_OK,
                'created_date'      => date('Y-m-d H:i:s'),
                'updated_date'      => date('Y-m-d H:i:s'),
            );
            
            $add_record     = DB::table('transaction_logs')
                ->insert($data_add);
                
            $update_record  = DB::table('vehicle_deliveries')
                ->where('vehicle_delivery_id', $delivery->vehicle_delivery_id)
                ->update($data_update);
                
            echo $delivery->purchase_number;
        }
    }
    
}