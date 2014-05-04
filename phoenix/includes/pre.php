<?php

function phoenixShutdown() {
	
	$error = error_get_last();
	
	$error_page = file_get_contents( $_SERVER['DOCUMENT_ROOT'] . "/phoenix/includes/frontend/phoenix_error.html" );
	
	$error['code'] = "";
	
	$line_num = $error['line'];
	$bad_file = file($error['file']);
	
	$x = 1; foreach ($bad_file as $line) {
		
		if ( ( $x >= ($line_num - 2) ) && ( $x <= ($line_num + 2) ) ) {
			
			$class = ( $x == $error['line'] ) ? "error-line" : "line";
			
			$line = str_replace("<?php", "", $line);
			$line = str_replace("\n", "", $line);
			
			//Remove all DEFINITION values for security.
			$line = preg_replace("/\DEFINE\(.*?\)/i", "DEFINE(\"****\", \"****\")", $line);
			$line = preg_replace("/\define\(.*?\)/i", "DEFINE(\"****\", \"****\")", $line);
			
			$error['code'] .= "<span class=\"$class\"><span class=\"num\">$x</span>$line</span>";
			
		}
		
		$x++;
		
	}
	
	foreach($error as $key => $array) {
		
		$error_page = str_replace("%$key%", $error["$key"], $error_page);
		
	}
	
	ob_clean();
	die($error_page);	
	
}


$shutdown = new Shutdown("phoenixShutdown");

class Shutdown {
	
	private $callback;
	
	function __construct( $callback ) {
		
		$this->callback = false;
		
		if (is_callable($callback))
			$this->callback = $callback;
		
	}
	
	public function callback() {
		
		if ( $this->callback != false ) {
			
			$callback = $this->callback;
			$callback();
			
		}
		
	}
	
	public function cancel() {
		
		$this->callback = false;
		
	}
	
}

register_shutdown_function(array($shutdown, "callback"));