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
	
	public function add_user_to_group( $uid, $group ) {
		
		/*
			
			Sets a given user's group to a group slug. Overwrites the current.
			
		*/
		
		$user = new User();
		
		$user->editData(array("group"), array( $group ));
		
	}
	
	public function permit_user( $uid, $permission ) {
		
		
	}
	
}