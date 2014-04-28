<?php

class Permissions {

    public $user;
    
    function __construct( $userid ) {
    
        $this->user = $user = new User( $userid ):
        
    }
    
    public function hasAbilityTo( $ability ) {
        
        $permissions = $this->get(); // returns array of permissions
        
        if ( array_key_exists( $ability, $permissions)://check for that ability in the array
            
            return ( $permissions[$ability] == "true" );//check if the ability is "true" for that user
            
        endif;
        
    }
    
    public function get() {
        
        $permissions = $this->user->getData( 'permissions' );
        
        return unserialize( $permissions );
        
    }
    
    public function set( array $new_permissions ) {
        
        $curr_permissions = $this->get(); // returns array of permissions
        
        $permissions = array_replace( $curr_permissions, $new_permissions );
        
        $this->user->updateData( array( [0] => array("field"=>"permissions", "value"=>$permissions) ) );
        
    }
    
}