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
		
		$this->exists = $this->db->rowExists( $slug, array("slug") );
		
		$this->count = $this->db->tableRowCount();
		
		$this->slug = $slug;
		$this->is_protected = false;//$this->data['protected'] ? 1 : 0;
		$this->requires_template = $this->data['requires_template'] ? 1 : 0;
		
		$this->data = $this->db->getRowData( "slug", array( $this->slug ) );
		
		
	}
	
	public function register( $slug, array $args, $plugin = null ) {
		
		
		
		
	}
	
}