<?php

class Command {

	public $command;
	public $commandstr;
	
	function __construct() {
		
		$this->commandstr = $_POST['command'];
		
		$regexp = '/\G(?:"[^"]*"|\'[^\']*\'|[^"\'\s]+)*\K\s+/';

		$this->command = preg_split($regexp, $this->commandstr);
		
		Console::tell(print_r($this->command, true));
		
		switch ($this->command[0]) {
			
			case "func":
			
				$this->executeFunction();
			
			break;
			
			case "cmd":
			
				$this->executeCommand();
			
			break;
			
			
			
		}
		
	}
	
	public function executeCommand() {
		
		$cmd = $this->command[1];
		$param = $this->command[2];
		
		$commands = new Commands();
		
		if ( method_exists($commands, $cmd) )
			$return = call_user_func(array($commands, $cmd), $param);
		else
			$return = "Unknown command $cmd";
		
		ob_start();
		var_dump($return);
		$return = ob_get_clean();
		
		Console::tell("<b>$cmd returned:</b> $return");
		
	}
	
	public function executeFunction() {
		
		$class = $this->command[1];
		$function = $this->command[2];
		$param = $this->command[3];
		
		if ( $class != "~" ):
		
			/*
			$instance = new $class();
			
			if ( class_exists($class) ):
				if ( method_exists( $instance, $function ) ):
					call_user_func(array($instance, $function), $param);
				endif;
			else:
			
				Console::tell("Unknown class $class");
				
			endif;
			*/
			
			Console::tell("calling $$class- >$function with param $param");
		
		else:
			
			Console::tell("calling $function() with param $param");
			
			$function($param);
			
		endif;
		
	}	
	
}