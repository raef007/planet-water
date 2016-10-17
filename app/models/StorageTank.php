<?php

class StorageTank extends Eloquent {
    
    /*-------------------------------------------------------------------------
	|	Class Attributes
	|--------------------------------------------------------------------------
	|	table		--- Database Table
	|	primaryKey	--- Primary key of the Projects Table
	|	timestamps	--- Prevents created_at and deleted_at fields from being required
	|------------------------------------------------------------------------*/
	protected $table        = 'storage_tanks';
    protected $primaryKey   = 'tank_id';
    public $timestamps      = false;
    
    /*-------------------------------------------------------------------------
	|	Get Storage Tank by ID
	|--------------------------------------------------------------------------
	|	@param [in] 	tank_id     --- Tank ID
	|	@param [out] 	NONE
	|	@return 		tank_data   --- Database Record
	|------------------------------------------------------------------------*/
    public function getStorageTankById($tank_id)
    {
        $tank_data  = self::find($tank_id);
        
        /*	関数終亁E   */
        return $tank_data;
    }
    
    /*-------------------------------------------------------------------------
	|	Get Storage Tank by User
	|--------------------------------------------------------------------------
	|	@param [in] 	user_id     --- User ID
	|	@param [out] 	NONE
	|	@return 		tank_data   --- Database Record
	|------------------------------------------------------------------------*/
    public function getStorageTankByUser($user_id)
    {
        $tank_data  = self::where('user_id', $user_id)
            ->where('status', STS_OK)
            ->first();
        
        /*	関数終亁E   */
        return $tank_data;
    }
    
    /*-------------------------------------------------------------------------
	|	Gets all the active tanks
	|--------------------------------------------------------------------------
	|	@param [in] 	NONE
	|	@param [out] 	NONE
	|	@return 		tank_data   --- Database Record
	|------------------------------------------------------------------------*/
    public function getAllActiveTanks()
    {
        $tank_data  = self::where('status', STS_OK)
            ->get();
        
        /*	関数終亁E   */
        return $tank_data;
    }
}
