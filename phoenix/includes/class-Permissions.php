<?php

class Permissions {

    public $user;
    
    function __construct( $userid ) {
    
    	$this->getPDO("groups");
        $this->user = $user = new User( $userid ):
        
    }
    
    public function createPermissionNode( $plugin, $action, $ability) {
	    
	    $this->db->addToTable(array("slug", "perms", "inherit"), array($slug, $perms, $inherit )); 
	    
    }
    
    public function createGroup( $slug, array $perms, $inherit = null ) {
	    
	    /*
		    
		    Overwrites current group, or creates new
		    
		    
		    
		    FORMAT:
		    
		    
		    
	    */
	    
	    if ( !$this->db->rowExists("slug", array($slug)) ) {
		    
		    $this->db->addToTable(array("slug", "perms", "inherit"), array($slug, $perms, $inherit )); 
		    
	    }
	    else {
		    
		    
		    
		    
	    }
	    
	    
	    
	    
	    
    }
    
    public function 
    
    public function userHasPermission( $uid, $perm_slug ) {
        
        $user = new User($uid);
        
        return $user->getPermissions();
        
        
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