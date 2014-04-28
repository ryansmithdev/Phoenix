<?php

class Navbar extends {

	public $items;
	
	function __construct() {
		
		
	}
	
	public function registerItem( array $item ) {
		
		$this->items[] = $item;
		
	}
	
	public function printItems( $syntax ) { //$syntax example: <li><a class='{class}' id='{id}' title='{title}' href='{link}'>{text}</a></li>
		
		foreach( $this->items as $item ):
		
			foreach( $item as $key=>$value )
				$syntax = str_replace("{$key}", "$value", $syntax);
		
			endforeach;
		
		endforeach;
		
	}
	
}