<?php

class Admin {

	

	function __construct() {
		
		$this->db->table = ;
		
	}
	
	public function addNewUser( $info ) {
		
		$this->db->$this->getPDO("users");

	use Phoenix;
		
		if ($info['login'] && $info['password'] && $info['first_name'] && $info['user_role'])
			$db->addToTable(array("login", "password", "first_name", "user_role"), array($user_un, $user_pw, $user_fname, "0"));
		else
			Console::tell("Couldn't Add New User: missing required fields");
	}
	
	public function editUser() {
		
		
	}
	
	public function enablePlugin( $id ) {
		
		$this->db->updateTable(array("enabled"), array("true"), $id, "id");
		
		
	}
	
	
}