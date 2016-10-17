<?php

class VehicleCategory extends Eloquent {
    
    /*-------------------------------------------------------------------------
	|	Class Attributes
	|--------------------------------------------------------------------------
	|	table		--- Database Table
	|	primaryKey	--- Primary key of the Projects Table
	|	timestamps	--- Prevents created_at and deleted_at fields from being required
	|------------------------------------------------------------------------*/
	protected $table        = 'vehicle_categories';
    protected $primaryKey   = 'category_id';
    public $timestamps      = false;
    
    /*-------------------------------------------------------------------------
	|	Get Vehicle Category by ID
	|--------------------------------------------------------------------------
	|	@param [in] 	vehicle_id      --- Category ID
	|	@param [out] 	NONE
	|	@return 		category_data   --- Database Record
	|------------------------------------------------------------------------*/
    public function getVehicleCategoryById($category_id)
    {
        $category_data  = self::find($category_id);
        
        /*	関数終亁E   */
        return $category_data;
    }
    
    /*-------------------------------------------------------------------------
	|	Get Category by Vehicle ID
	|--------------------------------------------------------------------------
	|	@param [in] 	vehicle_id      --- Vehicle ID
	|	@param [out] 	NONE
	|	@return 		category_data   --- Database Record
	|------------------------------------------------------------------------*/
    public function getVehicleCategoryByVehicle($vehicle_id)
    {
        $category_data  = self::where('vehicle_id', $vehicle_id)
            ->where('status', STS_OK)
            ->get();
        
        /*	関数終亁E   */
        return $category_data;
    }
}
