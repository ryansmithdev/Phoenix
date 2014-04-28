<?php

class Update {

	public $update;
	
	function __construct() {
		
		$url = "http://localhost5/phoenix/update.php?v=" . VERSION . "&b=" . BUILD;
		
		$file = file_get_contents($url);
		
		$this->update = json_decode($file);
		
	}
	
	public function availible() {
		
		
		return ( $this->update->availible == "YES" );
				
	}
	
	public function getPackage() {
		
		if ( !file_exists( ROOT . "/update/" ) ) {
			
			$zip_file_path = ROOT . "/update.zip";
			
			$zip_owner = fileowner($zip_file_path);
			
			$zip = new ZipArchive;
			
			if ($zip->open( $zip_file_path ) === TRUE) {
			
			    $zip->extractTo( ROOT );
			    
			    $zip->close();
			    
				chmod( ROOT . "/update/", 0756);
			    
			} else {
			
			    Console::tell("FAILED to get update package.");
			    
			}
			
		}
		
	}
	
	public function start() {
		
		if ( $this->availible() ) {	
			
			$this->getPackage();
			
			if ( file_exists( ROOT . "/update/update.json") ) {
				
				$json = file_get_contents( ROOT . "/update/update.json" );
				
				$update = json_decode($json);
				
				foreach( $update->files as $file ){
					
					//check if the original file exists to determine if it's being replaced or added
					$exists = file_exists( ROOT . $file->path );
					
					//make sure the new file in update package exists
					if( file_exists( ROOT . "/update/contents/" . $file->path ) ):
						
						$operation = ($exists) ? "Replaced" : "Added"; 
						$copy = copy( ROOT . "/update/contents/" . $file->path, ROOT . $file->path );
					
					//if not, then maybe it's a removal
					elseif( ( $exists ) && ( $file->remove == "YES" ) ):
						
						$operation = "Removed";
						
						$unlink = unlink( ROOT . $file->path );
					
					else:
					
						Console::tell("UPDATE PACKAGE ERROR: Update File Missing. " . $file->path );
					
					endif;
							
						
					if ( $copy || $unlink ) {
						
						Console::tell("$operation file : <b>{$file->path}</b>");
						
					}
					else {
						
						Console::tell("Failed to copy update file: <b>{$file->path}</b>");
						
					}
					
					
				}//foreach;
				
				
			}//file_exists json
			else {
				
				Console::tell("Failed to find update package.");
				
			}
			
			
		}///av
	}
	
	public function revert() {
		
		
	}
	
}