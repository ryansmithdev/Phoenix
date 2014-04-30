<?php

class Mysql {

	use Phoenix;
    
    private $dbh;
    private $fetch = array();
    
    public $querystr;
    public $query;
    public static $connected;
    
    public $table;
    
    public static $instance;
    
    function __construct( $table = "" ) {
        
        if ($this->dbh == null):
        
	        try {
	        	
	        	$db_dsn = "mysql:dbname=" . DB_NAME . ";host=" . DB_HOST;
	        
	            $this->dbh = new PDO( $db_dsn, DB_USER, DB_PASS );
	            
	            //die(var_dump($this->dbh));
	            
	            Console::tell("Connected to MYSQL database. '" . DB_NAME . "' @ '" . DB_HOST);
	            
	            self::$connected = true;
	            
	        } catch (PDOException $e) {
	        
	            self::$connected = false;
	            
	            Console::tell("Mysql failed to connect: " . $e);
	            
	        }
	    
       	endif;
        
    }
    
    public static function getInstance() {
	    
	    if ( self::$instance == null )
	    	self::$instance = new self;
	    	
	    return self::$instance;
	    
    }
    
    public function setTable( $table ) {
	    
	    $this->table = $table;
	    
    }
    
    function selectRow( $identifier, $identifier_fields, array $select_fields = null, $table = null ) {
    
    	//use the passed table name if not null.
    	$table = ($table == null) ? $this->table : $table;
        
        if ( $identifier_fields ): //if user specified the fields names in which to look for $identifier
            
            $fields = array(); //init
        
            foreach( $identifier_fields as $item => $field):
            
                $fields[] = "$field='$identifier'";
            
            endforeach;
            
            $where = implode( " OR ", $fields ); //splice all fields with " OR " keyword
        
        else:
            
            $where = "id='$identifier'"; //add default WHERE clause
        
        endif;
        
        if ( $select_fields ): //if 
            
            $select = array(); //init
        
            foreach( $select_fields as $item):
            
                $select_items[] = $item;
            
            endforeach;
            
            $select = implode( ",", $select_items ); //splice all items with " OR " keyword
        
        else:
            
            $select = "*"; //add default SELECT clause
        
        endif;
        
        $this->querystr = "SELECT $select FROM $table WHERE $where";
        
        Console::tell("QUERY STR: {$this->querystr}");
        
        //$this->dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        
        $sth = $this->dbh->prepare( $this->querystr );
        
        return $sth;
        
    }
    
    function deleteRow( $identifier, $identifier_fields, $table = null ) {
    
    	//use the passed table name if not null.
    	$table = ($table == null) ? $this->table : $table;
        
        if ( $identifier_fields ): //if user specified the fields names in which to look for $identifier
            
            $fields = array(); //init
        
            foreach( $identifier_fields as $item => $field):
            
                $fields[] = "$field='$identifier'";
            
            endforeach;
            
            $where = implode( " OR ", $fields ); //splice all fields with " OR " keyword
        
        else:
            
            $where = "id='$identifier'"; //add default WHERE clause
        
        endif;
        
        $this->querystr = "DELETE FROM $table WHERE $where";
        
        Console::tell("QUERY STR: {$this->querystr}");
        
        //$this->dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        
        $sth = $this->dbh->prepare( $this->querystr );
        
        $sth->execute();
        
        return $sth;
        
    }
    
    function createTable() {
	    
	    
    }
    
    public function tableRowCount() {
	    
	    Console::tell("Checking row count for table {$this->table}");
	    
	    $this->querystr = "SELECT COUNT(*) FROM {$this->table}";
        
        $sth = $this->dbh->prepare( $this->querystr );
        
        $sth->execute();
        
        $count = $sth->fetchColumn();
	    
	    return ($count);
	    
    }
    
    public function rowExists( $identifier, array $identifier_fields = null ) {
        
        $sth = $this->selectRow( $identifier, $identifier_fields, array("COUNT(*)") ); //get query resource
        
        $sth->execute();
        
        //die(var_dump($sth->fetchColumn()));
        
        return ( $sth->fetchColumn() > 0 ); //if there is one or more..
    
    }
    
    public function getRowData( $identifier, array $identifier_fields = null, array $mask_data = null, $table = null ) {
        
        //This method expects a value for the id (default) or one of given fields in $identifier_fields
        //identifer_fields ex: array("id","last_name") or array("id")
        
        $sth = $this->selectRow( $identifier, $identifier_fields, null, $table );
        
        $sth->execute();
        
        /*
        while ($row = $sth->fetch( PDO::FETCH_ASSOC )): //get associative array
        
            $this->fetch[] = $row;
        
        endwhile;
        
        */
        
        $this->fetch = $sth->fetch( PDO::FETCH_ASSOC );
        
        //$mask_data must be array of array keys corresponding to FETCH_ASSOC array. Ex: To mask the user's password, pass an array like so:
        //just password: array('password')
        if ( is_array( $mask_data ) ):
        
        	foreach( $mask_data as $data ):
      
        		$this->fetch["$data"] = str_repeat("*", strlen($this->fetch["$data"]));
        	
        	endforeach;
        
        endif;
        
        
        if($this->fetch == false) Console::tell("WARNING: QUERY Returned FALSE");
        
        return $this->fetch; //return the data array
        
    }
    
    function addToTable( array $columns, array $values ) {
	    
	    $columns_str = implode(",", $columns);
	    
	    foreach ($values as $value) {
		    
		    $values_str .= "'$value',";
		    
	    }
	    
	    $values_str = rtrim($values_str, ", ");
	    
	    $query = "INSERT INTO {$this->table} ($columns_str) VALUES ($values_str)";
	    
	    Console::tell($query);
	    
	    $sth = $this->dbh->prepare($query);
	    
	    $exec = $sth->execute();
	    
	    if (!$exec) Console::tell("Failed to update table. " . print_r($sth->errorInfo(), true) . " Query: $query");
	    
    }
    
    function updateTable( array $set, array $values, $identifier_val, $identifier_field = "id" ) {
    
    	//ex: updateTable( array( "first_name", "last_name" ), array( "Bob", "Leverett" ), "admin", "username" );
    	// would change first_name & last_name to Bob and Leverett for user with the username "admin"
        
        foreach($set as $field) {
	        
	        $x = 0;
	        
	        $set_str .= "$field='{$values[$x]}', ";
	        
	        $x++;
	        
        }
        
        //remove trailing ", "
        $set_str = rtrim($set_str, ", ");
        
        $query = "UPDATE {$this->table} SET $set_str WHERE $identifier_field='$identifier_val'";
        
        $sth = $this->dbh->prepare($query);
        $exec = $sth->execute();
        
        if (!$exec) Console::tell("Failed to update table. " . print_r($sth->errorInfo(), true) . " Query: $query");
        
    }
    
}