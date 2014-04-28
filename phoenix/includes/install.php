<?php

if (isset($_POST['phoenix-install-step'])) {
	
	completeInstallationStep( $_POST['phoenix-install-step'] );
	
}

function getConfigTemplate() {
	
	//curl thirtyandseven.com/phoenix/config_template.php.zip
	
	//ignore if config_template already exists
	//return true if file has been downloaded
	
}

function completeInstallationStep( $step ) {
	
	/* 
		
		Execute a specific step for welcome.php
	
	*/
	
	$err = "";
	
	switch($step) {
		
		case "db_info":
			
			/*
				
				Creates config_draft.php
				
			*/
			
			$db_host = (isset($_POST['db_host'])) ? $_POST['db_host'] : false;
			$db_name = (isset($_POST['db_name'])) ? $_POST['db_name'] : false;
			$db_user = (isset($_POST['db_user'])) ? $_POST['db_user'] : false;
			$db_pw = (isset($_POST['db_pw'])) ? $_POST['db_pw'] : false;
			
			$db_create_new = (isset($_POST['db_create_new'])) ? $_POST['db_create_new'] : false;
			
			$debug_mode = (isset($_POST['enable_debug_mode'])) ? "YES" : "NO";
			$timezone = (isset($_POST['timezone_set'])) ? $_POST['timezone_set'] : false;
			
			$definitions = array(
			
				array("def"=>"DB_HOST", 	         "val"=> $db_host),
				array("def"=>"DB_NAME", 	         "val"=> $db_name),
				array("def"=>"DB_USER", 	         "val"=> $db_user),
				array("def"=>"DB_PASS", 			 "val"=> $db_pw),
				array("def"=>"DEBUG_MODE_ENABLED",   "val"=> $debug_mode),
				array("def"=>"TIMEZONE_SET",         "val"=> $timezone)
			
			);
			
			editDefinitions( ROOT . "/config_template.php", $definitions, ROOT . "/config_draft.php");
			
			$db_info = array("host"=> $db_host, "user"=> $db_user, "pw"=> $db_pw, "name"=> null);
			
			//test connection WITHOUT DB_NAME!
			$mysqli = connectedToDb($db_info);
			
			if ( !mysqli_connect_errno($mysqli) ) {
				
				if ($db_create_new) {
						
					$query = $mysqli->query("CREATE DATABASE $db_name");
					
					$query1 = $mysqli->query("GRANT ALL PRIVILEGES ON $db_name.* TO '{$db_user}'@'{$db_host}'; FLUSH PRIVILEGES");
					
					Console::tell($mysqli->error);
					
				}
				
			}
			else {
				
				$err .= "MYSQL Error: " . mysql_error(); 
				
			}
		
		break;
		
		case "load_db":
		
			$user_un = (isset($_POST['user_un'])) ? $_POST['user_un'] : false;
			$user_pw = (isset($_POST['user_pw'])) ? $_POST['user_pw'] : false;
			$user_un = (isset($_POST['user_pw'])) ? $_POST['user_pw'] : false;
			$user_email = (isset($_POST['user_email'])) ? $_POST['user_email'] : false;
			
			$site_name = (isset($_POST['site_name'])) ? $_POST['site_name'] : false;
			$se_indexing = (isset($_POST['se_indexing'])) ? $_POST['se_indexing'] : false;
			
			/*
			$admin = new Admin();
			
			$admin->addNewUser(array("login"=>$user_un, "password"=>$user_pw, $user));

			
			
			$prefs = new Preferences();

			$prefs->set( "site_title", $site_name);
			$prefs->set( "se_indexing", $se_indexing);
			
			//loadSQLFile(ROOT . "/includes/phoenix.sql");
			
			*/
		
		break;
		
		case "finish_install":
			
			/* Rename config_draft.php to config.php, signifying finshed installation. */
			
			if (file_exists(ROOT . "/config_draft.php")) {
				$rename = rename(ROOT . "/config_draft.php", ROOT . "/config.php");
				if (!$rename) Console::tell("Failed to rename config_draft.php. Permissions issue?");
			}
		
			header("location: /?p=login");
		
		break;
	}
	
	$step_num = isset($_GET["step"]) ? $_GET['step'] : "0";
	
	$next_step_num = $step_num + 1;
	
	$next_path = "location: /?p=welcome&step=$next_step_num";
	
	if (empty($err))
		header($next_path);
	
}

function editDefinitions( $path, array $definitions, $output_file ) {
	
	/*
		
		replaces all define("DEFINITION", "SOME_VAL") with define("DEFINITION", "OTHER_VAL") in a given php file
		
		Notes: does not change given file, but creates new file with replaced definitions
		
	*/
	
	//check the file
	if (file_exists($path)):
		
		//get the file
		$file = file_get_contents($path);
		
		//go through given array of definitions
		foreach($definitions as $definition) {
			
			$def = $definition['def'];
			$val = $definition['val'];
			
			//replace definitions
			$file = str_replace("%$def%", "$val", $file);
			
		}
		
		//delete output file 
		if (file_exists($output_file)) unlink($output_file);
		
		//get output file
		$fp2 = fopen($output_file, "c+");
		
		//write new content to output file
		fwrite( $fp2, $file );
		fclose( $fp2 );
		
	else:
		
		die("Missing input file <b>$path</b>");
		
	endif;
	
}