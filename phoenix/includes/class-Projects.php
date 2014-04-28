<?php

class Projects {
    
    public $project_id;
    
    
    function __construct( $project_id = null ) {
        
        $this->db = (new Db)->db();
        
        $this->project_id = $project_id;
        
    }
    
    function getData() {
    
        $projects = array();
            
        $query1 = "SELECT * FROM projects WHERE id='" . $this->project_id . "'";
        $query2 = "SELECT * FROM projects";
            
        $query = ( $this->project_id ) ? $query1 : $query2;
        
        $res = $this->db->query( $query );
        
        while ( $project = $res->fetch_assoc() ):
        
            if ( $project['loc_same_as_contact'] == "1" ):
                
                //get instance of contact associated with project
                $contact = new Contacts( $project['loc_same_as_contact'] );
                $user = new User( $project['sold_by'] );
                
                //get address of associated contact
                $project['address'] = $contact->getDataFields( array("address") );
                
                $project['sold_by'] = $user->getDataFields( array("login") );
                
            endif;
            
            $projects[] = $project;
        
        endwhile;
        
        return $projects;
        
    }
    
}