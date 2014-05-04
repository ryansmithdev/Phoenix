<?php

class Preferences {

    use Phoenix;
    
    function __construct() {
        
        $this->db->$this->getPDO("prefs");
        
    }
    
    public function update() {
        
        
        
    }
    
    public function get( $name ) {
	    
	    
    }
    
    public function set( $name, $value ) {
        
        if ( is_array( $value ) ):
        
            $value = serialize( $value );
            
        endif;
        
        $user = (new User)->igetd;
        
        //from class-Db.php:
        //function updateTable( array $set, array $values, $identifier_val, $identifier_field = "id" )
        
        $this->db->updateTable(array("value"), array($value), $name, "name");
        
    }
    
}