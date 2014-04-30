<?php

class AdminPanel {
	
	public $plugin;
	public $actions;
	public $properties;
	
	public $plugin_id = "admin_panel";
	
	function __construct() {
		
		$this->plugin = new Plugin();
		
		$this->setProperty( array("property" => "require_authentication", "value" => "true") );
		$this->setProperty( array("property" => "required_permissions", "value" => 0) );
		
		$this->registerAction( array("hook"=>"form-handler", "args"=> array("form_name"=>"admin_panel_form", "trigger_method"=>"processForm")) );
		$this->registerAction( array("hook"=>"add-page", "args"=> array("file"=>"admin", "protected"=>true) ) );
		//$this->registerAction( array("hook"=>"add-page", "args"=> array("file"=>"index", "protected"=>false) ) );
		
	}
	
	public function setProperty( $property ) {
		
		$this->properties[] = $property;
		
	}
	
	public static function processForm() {
		
		die("FORM PROCESSED!");
		
	}
	
	public function registeredActions() {
		
		return $this->actions;
		
	}
	
	public function registerAction( array $action ) {
		
		$this->actions[] = $action;
		
	}
	
}