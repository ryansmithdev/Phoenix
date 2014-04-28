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
	
}