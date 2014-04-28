<?php

trait Phoenix {
	
	private $db;
	
	public function getPDO($table = null) {
		
		$this->db = Mysql::getInstance();
		
		$this->db->table = $table;
		
		return $this->db;

		
	}
	
}