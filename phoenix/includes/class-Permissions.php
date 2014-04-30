<?php

class Permissions {
	
	use Phoenix;
	
    public $user;
    
    function __construct() {
    
    	$this->getPDO("groups");
        $this->user = $user = new User();
        
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
    
    public function getUserPermissions( $uid = null ) {
	    
	    if ( $this->user->loggedin ) {
		    
		    $group = $this->user->data['group'];
		    
		    $perms = $this->getGroupPermissions($group);
		    
		    return $perms;
		    
	    }
	    else {
		    
		    $perms = $this->getGroupPermissions("default");
		    
		    return $perms;
		    
	    }
	    
    }
    
    public function getGroupPermissions($group) {
	    
	    //get perms from the user's group, and the users own permissions. Merge all into one array
	    
	    $data = $this->db->getRowData( $group, array("name"), null, "groups" );
	    $data2 = $this->user->data;
		
	    $nodes = explode("%", $data['perms']);
	    $nodes2 = explode("%", $data2['permissions']);
		
	    $perms = array_merge($nodes, $nodes2);
	    
	    die(var_dump($perms));
	    
	    return $perms;
	    
    }
    
    public function userHasPermission( $perm_node, $uid = null ) {
    	 
    	//phoenix.page.access.*
    	//phoenix.page.access.index
    
    	$node = explode(".", $perm_node);
    	
    	$node[0] = isset($node[0]) ? $node[0] : "";
    	$node[1] = isset($node[1]) ? $node[1] : "";
    	$node[2] = isset($node[2]) ? $node[2] : "";
    	$node[3] = isset($node[3]) ? $node[3] : "";
        
        $perms = $this->getUserPermissions( $uid );
        
        if ($perms[0] == "*") {
	        
	        // has * perms
	        
	        Console::tell("User has * permissions.");
	        
	        return true;
	        
        }
        else if ( in_array("{$node[0]}.*", $perms) ){  
	        
	        //has phoenix.* perms
	        
	        Console::tell("User has {$node[0]}.* permissions.");
	        
	        return true;
	        
        }
        else if ( in_array("{$node[0]}.{$node[1]}.*", $perms) ){  
	    	
	    	//has phoenix.page.* perms
	    	
	    	Console::tell("User has {$node[0]}.{$node[1]}.* permissions.");
	    	
	        return true;     
	        
        }
        else if ( in_array("{$node[0]}.{$node[1]}.{$node[2]}.*", $perms) ){   
	    	
	    	//has phoenix.page.access.* perms
	    	
	    	Console::tell("User has {$node[0]}.{$node[1]}.{$node[2]} permissions.");
	    	
	        return true;  
	        
        }
        else if ( in_array("{$node[0]}.{$node[1]}.{$node[2]}.{$node[3]}", $perms) ){  
	    	
	    	//has phoenix.page.access.index perms
	    	
	    	Console::tell("User has {$node[0]}.{$node[1]}.{$node[2]}.{$node[3]} permissions.");
	    	
	        return true;  
	        
        }
        else {
        
        	Console::tell("User does not have the required permission $perm_node");
	        
	        return false;
	        
        }
       
        
        
    }
    
    public function set( array $new_permissions ) {
        
        $curr_permissions = $this->get(); // returns array of permissions
        
        $permissions = array_replace( $curr_permissions, $new_permissions );
        
        $this->user->updateData( array( [0] => array("field"=>"permissions", "value"=>$permissions) ) );
        
    }
    
}