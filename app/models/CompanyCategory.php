<?php

class CompanyCategory extends Eloquent {
    
    /*-------------------------------------------------------------------------
	|	Class Attributes
	|--------------------------------------------------------------------------
	|	table		--- Database Table
	|	primaryKey	--- Primary key of the Projects Table
	|	timestamps	--- Prevents created_at and deleted_at fields from being required
	|------------------------------------------------------------------------*/
	protected $table        = 'company_categories';
    protected $primaryKey   = 'company_category_id';
    public $timestamps      = false;
    
    /*-------------------------------------------------------------------------
	|	Get Company Category by ID
	|--------------------------------------------------------------------------
	|	@param [in] 	company_category_id  --- CompanyCategory ID
	|	@param [out] 	NONE
	|	@return 		category_data       --- Database Record
	|------------------------------------------------------------------------*/
    public function getCompanyCategoryById($company_category_id)
    {
        $category_data  = self::find($company_category_id);
        
        /*	関数終亁E   */
        return $category_data;
    }
    
    /*-------------------------------------------------------------------------
	|	Get Company Category by Category ID
	|--------------------------------------------------------------------------
	|	@param [in] 	category_id     --- Category ID
	|	@param [out] 	NONE
	|	@return 		category_data   --- Database Record
	|------------------------------------------------------------------------*/
    public function getCompanyCategoryByCategory($category_id)
    {
        $category_data  = self::where('category_id', $category_id)
            ->where('status', STS_OK)
            ->get();
        
        /*	関数終亁E   */
        return $category_data;
    }
    
    /*-------------------------------------------------------------------------
	|	Get Un-categorized Company by Vehicle
	|--------------------------------------------------------------------------
	|	@param [in] 	category_id     --- Category ID
	|	@param [out] 	NONE
	|	@return 		category_data   --- Database Record
	|------------------------------------------------------------------------*/
    public function getUnCategorizedByVehicle($vehicle_id)
    {
        $uncat_companies    = array();
        $tank_ids           = array();
        
        $vehicle_db     = new Vehicle;
        $category_db    = new VehicleCategory;
        $tank_db        = new StorageTank;
        
        $tanks      = $tank_db->getAllActiveTanks();
        $categories = $category_db->getVehicleCategoryByVehicle($vehicle_id);
        
        foreach ($categories as $category) {
            $companies  = self::getCompanyCategoryByCategory($category->category_id);
            
            foreach ($companies as $company) {
                $tank_ids[] = $company->tank_id;
            }
        }
        
        foreach ($tanks as $tank) {
            if (FALSE == in_array($tank->tank_id, $tank_ids)) {
                $uncat_companies[]  = $tank;
            }
        }
        
        /*	関数終亁E   */
        return $uncat_companies;
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
        $delivery_db    = new VehicleDelivery;
        
        $categories = $category_db->getVehicleCategoryByVehicle($vehicle_id);
        
        foreach ($categories as $category) {
            $companies  = self::getCompanyCategoryByCategory($category->category_id);
            
            foreach ($companies as $company) {
                $tank['specifications'] = $tank_db->getStorageTankById($company->tank_id);
                
                if (1 == $tank['specifications']->status) {
                    $tank['company']        = $tank_info_db->getTankInformationByTankId($company->tank_id);
                    $tank['forecast']       = $storage_db->getPlannerForecastData($company->tank_id, 60);
                    $tank['delivery']       = $delivery_db->getVehicleDeliveryByTank($vehicle_id, $company->tank_id);
                    $tank['deliveries']     = $delivery_db->getTankScheduledDelivery($company->tank_id);
                    $tank['ccat_id']        = $company->company_category_id;
                    
                    $tanks[$category->category_label][] = $tank;
                }
            }
        }

        /*	関数終亁E   */
        return $tanks;
    }
}
