<?php

class VehicleDelivery extends Eloquent {
    
    /*-------------------------------------------------------------------------
	|	Class Attributes
	|--------------------------------------------------------------------------
	|	table		--- Database Table
	|	primaryKey	--- Primary key of the Projects Table
	|	timestamps	--- Prevents created_at and deleted_at fields from being required
	|------------------------------------------------------------------------*/
	protected $table        = 'vehicle_deliveries';
    protected $primaryKey   = 'vehicle_delivery_id';
    public $timestamps      = false;
    
    /*-------------------------------------------------------------------------
	|	Get Vehicle Delivery by ID
	|--------------------------------------------------------------------------
	|	@param [in] 	vehicle_delivery_id --- Vehicle Delivery ID
	|	@param [out] 	NONE
	|	@return 		delivery_data       --- Database Record
	|------------------------------------------------------------------------*/
    public function getVehicleDeliverybyId($vehicle_delivery_id)
    {
        $delivery_data  = self::find($vehicle_delivery_id);
        
        /*	関数終亁E   */
        return $delivery_data;
    }
    
    /*-------------------------------------------------------------------------
	|	Get Vehicle Deliveries by Vehicle ID
	|--------------------------------------------------------------------------
	|	@param [in] 	vehicle_id      --- Vehicle ID
	|	@param [out] 	NONE
	|	@return 		delivery_data   --- Database Record
	|------------------------------------------------------------------------*/
    public function getVehicleDeliveryByVehicle($vehicle_id)
    {
        $delivery_data  = self::where('vehicle_id', $vehicle_id)
            ->where('status', STS_OK)
            ->get();
        
        /*	関数終亁E   */
        return $delivery_data;
    }
    
    /*-------------------------------------------------------------------------
	|	Get earliest Vehicle Delivery by Tank
	|--------------------------------------------------------------------------
	|	@param [in] 	category_id     --- Category ID
	|                   tank_id         --- Tank ID
    |
	|	@param [out] 	NONE
	|	@return 		delivery_data   --- Database Record
	|------------------------------------------------------------------------*/
    public function getVehicleDeliveryByTank($vehicle_id, $tank_id)
    {
        $delivery_data  = self::where('vehicle_id', $vehicle_id)
            ->where('tank_id', $tank_id)
            ->where('delivery_date', '>=', date('Y-m-d'))
            ->where('status', STS_OK)
            ->orderBy('delivery_date', 'asc')
            ->first();
        
        /*	関数終亁E   */
        return $delivery_data;
    }
    
    /*-------------------------------------------------------------------------
	|	Get Company Category by Vehicle
	|--------------------------------------------------------------------------
	|	@param [in] 	category_id     --- Category ID
	|	@param [out] 	NONE
	|	@return 		category_data   --- Database Record
	|------------------------------------------------------------------------*/
    public function getCompanyCategoryByVehicle($vehicle_id)
    {
        $tanks          = array();
        
        $vehicle_db     = new Vehicle;
        $category_db    = new VehicleCategory;
        $tank_db        = new StorageTank;
        $tank_info_db   = new TankInformation;
        $storage_db     = new StorageUpdate;
        
        $categories = $category_db->getVehicleCategoryByVehicle($vehicle_id);
        
        foreach ($categories as $category) {
            $companies  = self::getCompanyCategoryByCategory($category->category_id);
            
            foreach ($companies as $company) {
                $tank['specifications'] = $tank_db->getStorageTankById($company->tank_id);
                $tank['company']        = $tank_info_db->getTankInformationByTankId($company->tank_id);
                $tank['forecast']       = $storage_db->getPlannerForecastData($company->tank_id, 60);
                
                $tanks[$category->category_label][] = $tank;
            }
        }

        /*	関数終亁E   */
        return $tanks;
    }
    
    /*-------------------------------------------------------------------------
	|	Get a scheduled delivery from a Vehicle by Tank
	|--------------------------------------------------------------------------
	|	@param [in] 	vehicle_id      --- Vehicle ID
	|                   tank_id         --- Tank ID
	|                   delivery_date   --- Delivery Date
    |
	|	@param [out] 	NONE
	|	@return 		delivery_data   --- Database Record
	|------------------------------------------------------------------------*/
    public function getScheduledDeliveryByTank($vehicle_id, $tank_id, $delivery_date)
    {
        $delivery_data  = self::where('vehicle_id', $vehicle_id)
            ->where('tank_id', $tank_id)
            ->where('delivery_date', $delivery_date)
            ->where('status', STS_OK)
            ->first();
        
        /*	関数終亁E   */
        return $delivery_data;
    }
    
    /*-------------------------------------------------------------------------
	|	Get a scheduled delivery from a Vehicle
	|--------------------------------------------------------------------------
	|	@param [in] 	vehicle_id      --- Vehicle ID
	|                   tank_id         --- Tank ID
	|                   delivery_date   --- Delivery Date
    |
	|	@param [out] 	NONE
	|	@return 		delivery_data   --- Database Record
	|------------------------------------------------------------------------*/
    public function getScheduledDelivery($vehicle_id)
    {
        $ctr            = 0;
        $delivery_total = 0;
        $date_cmp       = date('Y-m-d');
        $deliveries     = array();
        $totals         = array();
        
        $tank_db        = new TankInformation;
        
        $delivery_data  = self::where('vehicle_id', $vehicle_id)
            ->where('status', STS_OK)
            ->where('delivery_date', '>=', date('Y-m-d'))
            ->orderBy('delivery_date', 'asc')
            ->get();
        
        if ($delivery_data) {
            $date_cmp   = $delivery_data[0]->delivery_date;
        }
        
        foreach ($delivery_data as $delivery) {
            
            if (date('Y-m-d', strtotime($delivery->delivery_date)) != date('Y-m-d', strtotime($date_cmp))) {
                $ctr++;
                $delivery_total = 0;
            }
            
            $delivery->tank =   $tank_db->getTankInformationByTankId($delivery->tank_id);
            
            $delivery_total     = $delivery_total + $delivery->volume_manual;
            $totals[$ctr]       = $delivery_total;
            $deliveries[$ctr][] = $delivery;
            
            $date_cmp = $delivery->delivery_date;
        }
        
        /*	関数終亁E   */
        return array($deliveries, $totals);
    }
    
    /*-------------------------------------------------------------------------
	|	Get all scheduled delivery for a Tank
	|--------------------------------------------------------------------------
	|	@param [in] 	tank_id         --- Tank ID
    |
	|	@param [out] 	NONE
	|	@return 		delivery_data   --- Database Record
	|------------------------------------------------------------------------*/
    public function getTankScheduledDelivery($tank_id)
    {
        $deliveries         = array();
        $delivery_dates     = array();
        $vehicles           = array();
        
        $delivery_data  = self::where('tank_id', $tank_id)
            ->where('status', STS_OK)
            ->orderBy('delivery_date', 'asc')
            ->get();
        
        foreach ($delivery_data as $delivery) {
            $delivery_dates[]   = date('Y-m-d', strtotime($delivery->delivery_date));
            $vehicles[]         = $delivery->vehicle_id;
        }
        
        $deliveries['delivery_dates']   = $delivery_dates;
        $deliveries['vehicles']         = $vehicles;
        
        /*	関数終亁E   */
        return $deliveries;
    }
    
    /*-------------------------------------------------------------------------
	|	Get remaining vehicle capacity
	|--------------------------------------------------------------------------
	|	@param [in] 	tank_id         --- Tank ID
    |                   until_date      --- Until Date
    |
	|	@param [out] 	NONE
	|	@return 		delivery_data   --- Database Record
	|------------------------------------------------------------------------*/
    public function getVehicleRemainingLitres($vehicle_id, $until_date)
    {
        $used_volume    = 0;
        $free_volume    = 0;
        $total_volume   = 0;
        
        $vehicle_db = new Vehicle;
        
        $vehicle    = $vehicle_db->getVehicleById($vehicle_id);
        
        $refill_start   = self::where('status', 3)
            ->where('vehicle_id', $vehicle_id)
            ->where('delivery_date', '<=', $until_date)
            ->orderBy('delivery_date', 'desc')
            ->first();
        
        $refill_end     = self::where('status', 3)
            ->where('vehicle_id', $vehicle_id)
            ->where('delivery_date', '>', $until_date)
            ->orderBy('delivery_date', 'desc')
            ->first();
        
        $delivery_obj   = self::where('vehicle_id', $vehicle_id)
            ->where('status', 1);
            
        if ($refill_start) {
            $from_date  = $refill_start->delivery_date;
            $delivery_obj->where('delivery_date', '>=', $from_date);
        }
        
        if ($refill_end) {
            $to_date    = $refill_end->delivery_date;
            $delivery_obj->where('delivery_date', '<=', $to_date);
        }
        
        $delivery_data  = $delivery_obj->orderBy('delivery_date', 'asc')
            ->get();
            
        $total_volume   = $vehicle->tank_capacity;
        
        foreach ($delivery_data as $delivery) {
            $used_volume    += $delivery->volume_manual;
        }
        
        $free_volume    = $total_volume - $used_volume;
        
        /*	関数終亁E   */
        return $free_volume;
    }
    
    /*-------------------------------------------------------------------------
	|	Get all Refill Days
	|--------------------------------------------------------------------------
	|	@param [in] 	tank_id         --- Tank ID
    |                   until_date      --- Until Date
    |
	|	@param [out] 	NONE
	|	@return 		delivery_data   --- Database Record
	|------------------------------------------------------------------------*/
    public function getRefillDays($vehicle_id)
    {
        $refill_days    = array();
        
        $refills    = self::where('status', 3)
            ->where('vehicle_id', $vehicle_id)
            ->orderBy('delivery_date', 'desc')
            ->get();
            
        foreach ($refills as $refill) {
            $refill_days[]  = date('Y-m-d', strtotime($refill->delivery_date));
        }
        
        /*	関数終亁E   */
        return $refill_days;
    }
    
    public function getVehicleDeliverySummary($vehicle_id)
    {
        $info_db    = new TankInformation;
        
        $deliveries = self::where('vehicle_id', $vehicle_id)
            ->where('delivery_date', '>=', date('Y-m-d'))
            ->where('status', 1)
            ->orWhere(
                function ($query) {
                    $query->where('status', 3)
                        ->where('delivery_date', '>=', date('Y-m-d'));
                })
            ->orderBy('delivery_date', 'asc')
            ->get();
        
        foreach ($deliveries as $delivery) {
            if (3 == $delivery->status) {
                $delivery->company_info = new stdClass;
                $delivery->company_info->company_name   = 'GoBlue Inglehorn';
                $delivery->company_info->address        = 'Gotham';
                $delivery->company_info->city           = 'Sydney';
                $delivery->company_info->state          = 'Sydney State';
                $delivery->company_info->contact_person = 'Mike';
                $delivery->company_info->contact_email  = 'mike@gmail.com';
                $delivery->company_info->contact_number = '333 3333';
                $delivery->company_info->delivery_time  = 'N/A';
                $delivery->company_info->batch_number   = $delivery->purchase_number;
            }
            else {
                $delivery->company_info                 = $info_db->getTankInformationByTankId($delivery->tank_id);
                $delivery->company_info->batch_number   = self::getDeliveryBatchNumber($delivery->delivery_date);
            }
        }
        
        /*	関数終亁E   */
        return $deliveries;
    }
    
    public function getDeliveriesByDate($date)
    {
        $info_db    = new TankInformation;
        
        $deliveries = self::where('delivery_date', '<', $date)
            ->where('status', 1)
            ->get();
        
        foreach ($deliveries as $delivery) {
            $delivery->company_info = $info_db->getTankInformationByTankId($delivery->tank_id);
            $delivery->batch_number = self::getDeliveryBatchNumber($delivery->delivery_date);
        }
        
        return $deliveries;
    }
    
    public function getDeliveryBatchNumber($delivery_date)
    {
        $batch_number   = 'N/A';
        
        $refill_day = self::where('delivery_date', '<=', $delivery_date)
            ->where('status', 3)
            ->orderBy('delivery_date', 'desc')
            ->first();
        
        if ($refill_day) {
            $batch_number = $refill_day->purchase_number;
        }
        
        return $batch_number;
    }

}