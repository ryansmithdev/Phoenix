<?php

class Console {

	public static $msg;
	
	public static function tell( $newmsg ) {
		
		//get current array, unserialize it or create it
		$msg_arr = (!empty(self::$msg)) ? unserialize( self::$msg ) : array();
		
		array_push($msg_arr, array($newmsg, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)));
		
		//re-serialize the array
		self::$msg = serialize( $msg_arr );
	
		
	}
	
	public static function getall() {
		
		if ( self::$msg )
			return self::$msg;
		else
			//give empty array
			return serialize( array() );
		
	}
	
}