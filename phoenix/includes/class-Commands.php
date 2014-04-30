<?php

class Commands {

	public function get_user_password( $uid ) {
		
		$user = new User( $uid );
		
		return $user->getData();
		
		
	}
	
	public function log_user_in( $uid ) {
		
		$user = new User();
		
		$authorize = $user->authorize( $uid );
		
		return $authorize;
		
	}
	
	public function logout() {
		
		$user = new User();
		
		$logout = $user->doLogout(false);
		
		return ($logout) ? "User logged out." : "User NOT logged out!";
		
	}
	
	public function create_permission_group( $name, $inherit = null ) {
	
		Console::tell("Creating perm group $name with inheritance $inherit");
		
		$permissions = new Permissions();
		$return = $permissions->createGroup( $name, null, $inherit );
		
		return ($return) ? "Created permission group $name successfully." : "Failed to create inheritance group $group. Try using delete_permission_group <group_name> first.";
		
	}
	
	public function delete_permission_group( $name ) {
		
		$permissions = new Permissions();
		$return = $permissions->deleteGroup( $name );
		
		return ($return) ? "Deleted permission group $name successfully." : "Failed to delete inheritance group $group.";
		
	}
	
	public function add_inheritance_to_permission_group( $group, $inherit ) {
		
		$permissions = new Permissions();
		$return = $permissions->addInheritaceToGroup( $group, $inherit );
		
		return ($return) ? "Inheritance for group $group changed to $inherit successfully." : "Inheritance change for group $group failed.";
		
	}
	
}