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
}
