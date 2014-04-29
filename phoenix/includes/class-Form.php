<?php

class Form  {

	use Phoenix;

    public $id;
    public $data;
    
    public $name;
    
    public $action;
    
    public $error;
    
    function __construct( $id = null ) {
        
        if ($id):
            $this->id = $id;
            $this->get();
        endif;
        
        $this->action = $this->getAction();
        
        $this->process();
        
    }
    
    private function process() {
        
        if ( isset($_POST['form']) ):
        
            if ( is_array( $this->action ) ):
            
                $this->saveData();
                
                $class  = $this->action[0];
                $method = $this->action[1];
                
                if ( $class ):
                	
                	
                	Console::tell("Instantiating class $class and calling method $method");
                    $instance = new $class();//instantiate given object
                    
                    if ( $method )
                    	$callback_return = call_user_func( array( $instance, $method ) );//expects array of instance and method callback
                    
                    $this->error = $instance->formError;
                
                else:
                
                    die("Class or method missing");
                
                endif;
            
            else:
                
                Console::tell("Form has no specified action.");
                
            endif;
        
        else:
        
            
        
        endif;
            
        
    }
    
    public function getAction() {
        
        $action = null;
        
        if ( isset( $_POST['form'] ) ):
            
            $this->name = isset( $_POST['form'] ) ? $_POST['form'] : false;
             
            if ( $this->name ):
            
                switch( $this->name ){
                    
                    case "login":
                    
                        $action = array("User", "doLogin");
                    
                    break;
                    
                    case "logout":
                    
                        $action = array("User", "doLogout");
                    
                    break;
                    
                    case "phoenix-command":
                    
                    	$action = array("Command", null);
                    
                    break;
                    
                    case "registered_form":
                    
                    	
                    
                    break;
                    
                }
            
            endif;
        
        elseif ( isset( $_GET['do'] )):
            
            $this->name = isset( $_GET['do'] ) ? $_GET['do'] : false;
        
            if ( $this->name ):
            
                switch( $this->name ){
                    
                    case "logout":
                    
                        $action = array("User", "doLogout");
                    
                    break;
                    
                }
            
            endif;
        
        else:
        
        	Console::tell("No form submitted.");
        
        endif;
        
        return $action;
        
    }
    
    public function pluginFormHandler() {
	    
	    
	    
    }
    
    public function get() {
        
        $query = $this->db->query( "SELECT * FROM preferences WHERE form='{$this->id}'" );
        
        $inputs = array();
        
        while( $form = $query->fetch_assoc() ):
        
            $name       = $form['name'];
            $label      = $form['label'];
            $options    = $form['options'];
            $value      = $form['value'];
            
            $opts = "";
            
            switch( $form['input_type']) {
                
                case "text":
                
                    $opts[] = "<input type='text' value='$value'/>";
                
                break;
                
                case "select":
                
                    if ( !empty( $options ) ): $options = unserialize($options);
                        
                        $opts = array();
                        
                        foreach( $options as $option):
                             
                            $value = $option['value'];
                            $text = $option['text'];
                            
                            $opts[] = "<option value='$value'>$text</option>";
                        
                        endforeach;
                        
                        $opts = implode( "", $opts );
                        
                    endif;
                    
                    $inputs[] = "<select name='$name'>$opts</select>";
                
                break;
                
                case "radio":
                
                break;
                
                case "checkbox":
                
                break;
                
                return $ops;
                
            }
            
        
        endwhile;
        
    }
    
    public function saveData( $expire = 3600 ) {
        
        $post_vars = $_POST;
        
        if ( array_key_exists("password", $post_vars) ) unset($post_vars["password"]);
        
        $data = serialize( $post_vars );
        
        $this->data = $data;
        
        setcookie( "phoenix-form-data", $data, time() + $expire );
        
    }
    
    public function getData() {
        
        $data = array();
    
        if ( $this->data ):
        
            $data = unserialize( $this->data );
        
        else:
        
            if (isset( $_COOKIE['phoenix-form-data'] ))
                $data = unserialize( $_COOKIE['phoenix-form-data'] );
            
        endif;
        
        return $data;
        
    }
    
}