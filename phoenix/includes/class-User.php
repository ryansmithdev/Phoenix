<?php

class User  {

	use Phoenix;
    
    public $id;
    
    public $formError;
    
    public $login;
    public $password;
    
    public $loggedin;
    
    public $secure;
    
    function __construct( $id = null ) {
    	
    	$this->getPDO("users");
        
        Console::tell("Constructing user...");
        
        $this->loggedin = $this->loggedIn();
        
        $this->login = $this->currentUserLogin();
        
        $this->id = ( !$id ) ? $this->loggedin : $id;
        
        $this->data = $this->db->getRowData( $this->id, array("id"), array("password") );
        
    }
    
    public function loggedIn() {
    
    	Console::tell("Validating session...");
    	
    	$this->secure = new Secure( "phoenix-secure-session" );
	
		return ( $this->secure->isValid( true ) );
        
        
    }
    
    public function currentUserLogin() {
	    
	    if ($this->loggedIn() ) {
		    
		    return $this->secure->sessiondata['id'];
		    
	    }
	    
    }
    
    public function authorize() {
    
        $data = array("login" => $this->login);
        
        //expire session in one hour (3600)
        $session = $this->secure->registerSession("phoenix-secure-session", $data, 3600);
        
		return $session;
        
    }
    
    public function doLogin() {
    
    	Console::tell("Attempting to log user in...");
        
        //form values
        $this->login = isset( $_POST['login'] ) ? $_POST['login'] : null;
        $this->password = isset( $_POST['password'] ) ? $_POST['password'] : null;
        
        //check if login and password fields both hold values
        if ( $this->login && $this->password ):
            
            if ( $this->exists() ): //check user existence
            
                if ( $this->verifyPassword() ): //check the given password
                    
                    $this->authorize();
                    
                    header("location: /?p=home");
                
                else:
                
                    $this->formError .= "Password incorrect for " . $this->login;
                
                endif;
            
            else:
            
                $this->formError .= "Entered user does not exist.";
            
            endif;
        
        else:
        
            $this->formError .= "Please enter a username and password.". $this->login;
        
        endif;
        
        if ( !empty( $this->formError )) Console::tell("Form error: " . $this->formError );
        
    }
    
    public function doLogout( $header = true ) {
        
        if ( $this->loggedin ):
        
            $session = new Secure( "phoenix-secure-session" );
            
            $destroyed = $session->destroyRegisteredSession();
			
            if ($true) header("location:/?p=login");
            
            return $destroyed;
        
        else:
        	
        	//return true since the user never was logged in.
        	
        	return true;
        
        endif;
        
    }
    
    public function getData( array $fields ) {
	    
	    $this->db->getRowData( $this->id, array("id") );
	    
	    return $this->data;
	    
    }
    
    public function editData( array $fields, array $values ) {
	    
	    $this->db->updateTable( $fields, $values, $this->session->userid, "id");
	    
    }
    
    public function getPermissions() {
	    
	    return array("group" => $this->getData( $this->login, null, array("group", "permissions")));
	    
    }
    
    public function exists( $identifier = null ) {
        
        //get current login name if not passed one.
        if ( !$identifier ) $identifier = $this->login;
        
        //return boolean return value of rowExists, pass id and tell method either id or login may be used.
        return $this->db->rowExists( $identifier, array( "id", "login" ) );
        
    }
    
    public function verifyPassword( $password = null ) {
        
        //get current password if not passed one.
        if ( !$password ) $password = $this->password;
        
        //returns associative array for any rows such that id='$this->login' OR login='$this->login'
        $data = $this->db->getRowData( $this->login, array( "id", "login" ) );
        
        //get user's actual password
        $db_password = $data['password'];
        
        if ( $password != $db_password) Console::tell("Checking password failed: $password != $db_password");
        
        //compare user's actual password with the one given to compare, return true or false
        return ( $password == $db_password);
        
        
    }
    
    public function greeting() {
        
        if ( $this->loggedin ):
        
            echo "Welcome, " . $this->getDataFields( array("name") );
        
        endif;
        
    }
    
}