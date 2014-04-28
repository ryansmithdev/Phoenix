<?php

class Phoenix {

	public static $db;
	
	public static function db($table) {
		
		if ( $this->db == null ) {
		
			$this->db = Mysql::getInstance();
			
			$this->db->table = $table;
			
			Console::tell("MYSQL NULL. CREATING NEW INSTANCE OF MYSQL.");
			
		}
		
		return $this->$db;
		
	}
	
}