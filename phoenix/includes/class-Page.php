<?php

class Page {

	use Phoenix;
	
	public $slug;
	public $data;
	
	public $exists;
	public $count;
	
	public $is_protected;
	public $requires_template;
	
	function __construct( $slug = null ) {
		
		$this->getPDO("pages");
		
		$this->slug = $slug;
		
		$this->data = $this->db->getRowData( $this->slug, array("slug") );
		
		$this->exists = $this->db->rowExists( $this->slug, array("slug") );
		
		$this->count = $this->db->tableRowCount();
		
		$this->is_protected = ($this->data['protected'] == 1) ? true : false;
		
		$this->requires_template = ($this->data['requires_template'] == 1) ? true : false;
		
		
	}
	
	public function getTitle() {
		
		return $this->data['title'];
		
	}
	
	public function register( $slug, array $args, $plugin = null ) {
		
		
		
		
	}
	
}