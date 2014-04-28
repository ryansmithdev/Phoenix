<?php

class Secure  {

	use Phoenix;

    public $userid;
    public $session;
    public $sessiondata;
    
    function __construct( $session = null ) {
        
        $this->session = $session;
        
        $this->sessiondata = unserialize( $_SESSION["{$this->session}"] );
        
    }
    
    public function registerSession( $id, $data, $expire = false ) {
	    
	    Console::tell("Registering secure session...");
	    
	    $this->session = $id;
	    
	    if ( isset( $_SESSION["{$this->session}"] ) )
	    	Console::tell("Session \"$id\" is already set. Overwriting...");
	    
		//convert expire value to string, even if empty.
	    $expire = ( $expire ) ? $expire : "";
	    
	    //die(print_r($this->sessiondata, true));
	    
	    $this->sessiondata = array("data" => $data, "registered" => time(), "expire" => $expire );
		
	    //set session with data array.
	    $session_set = $_SESSION["{$this->session}"] = serialize($this->sessiondata);
	    
	    if ( !$session_set )
	    	Console::tell("<b>Warning:</b> Session $id failed to set.");
	    	
	    return $session_set;
	    
    }
    
    public function destroyRegisteredSession() {
	    
	    $_SESSION["{$this->session}"] = array();
	    unset($_SESSION["{$this->session}"]);
	    
	    //return true if the session has been successfully unset (or destroyed)
	    return( !$this->isValid() );
	    
    }
    
    public function sessionTimedOut() {
	    
	    $timeout = $this->sessiondata['expire'];
	    
	    if ( !empty($timeout)):
	
		    $registered = $this->sessiondata['registered'];
	    
			if ( ( $registered + $timeout ) < time() )
	    	return true;
	    
	    endif;
	    
    }
    
    public function resetSessionTimeout() {
	    
	    if ( isset( $_SESSION["{$this->session}"] ) ):
	    
	    	Console::tell("Resetting session timeout...");
	    	
	    	//update time in session data array
	    	$this->sessionsdata['registered'] = time();
	    	
	    	//load data back into session
	    	$_SESSION["{$this->session}"] = serialize($this->sessiondata);
	    	
	    	//Console::tell("Successfully reset session with data " . $this->data['data'] . " and expiration " . date("m-d-y g:iA", $session['expire']);
			
	    endif;
    }
    
    public function isValid( $resetTimeoutOnSuccess = false ) {
    
    	//Console::tell(print_r($_SESSION["{$this->session}"], true));
    	
        if ( isset( $_SESSION["{$this->session}"] ) ):
            
            if ( !$this->sessionTimedOut() ):
            
                //force enter password
                
                if ( $resetTimeoutOnSuccess === true ) 
                	$this->resetSessionTimeout();
                
                if ( !empty($this->sessiondata['expire']) ):
                
	                $timeout = time() + $this->sessiondata['expire'];
	                
	                Console::tell("Session validated. Timeout: " . date("m-d-y g:i A", $timeout));
	                
	                Console::tell( print_r($this->sessiondata, true));
	                
	            else:
	            
	            	Console::tell("Session validated. Session will not time out. ");
	            
	            endif;
                
                return $_SESSION["{$this->session}"];
                
            else:
            	
            	Console::tell("Session timed out. Destroying Session...");   
            	         	
            	$this->destroyRegisteredSession();
            	
                return true;
            
            endif;
        
        else:
        
        	Console::tell("Session {$this->session} not set. Returning false..");
        
            return false;
        
        endif;
        
    }
    
    
    
}