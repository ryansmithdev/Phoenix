<?php

function __autoload( $class ) {

	if ( file_exists(ROOT . "/includes/class-$class.php") )
	
    	require_once( ROOT . "/includes/class-$class.php" );
    	
    else
    
    	Console::tell("Failed to find class file includes/class-$class.php");
    	
}

function phoenixIsInstalled() {

	/*
		
		Checks for config.php file	
		
	*/

	return (file_exists(ROOT . "/config.php"));
	
}

function phoenix_done() {
	
	//DISABLED!
	
	$format = new DateTime('u');
	
	$start = $format->format(START_TIME);
	$now = $format->format(time());
	
	$time = $now - $start;
	
	Console::tell("Phoenix finished in $start $now $time seconds." );
	
	
	
}

function connectedToDb( array $db = null, $suppress_console_msg = false ) {

	/*
		
		Checks for a successful database connection. 
		Returns mysqli resource if successful 
		
		
	*/
	
	if (!$suppress_console_msg) Console::tell("Checking db connection...");
	
	//get db info from config
	$conf = array("host" => DB_HOST, "user" => DB_USER, "pw" => DB_PASS, "name" => DB_NAME);
	
	//use $db param if required array keys are set.
	$db = ( is_array($db) && isset($db['host']) && isset($db['user']) && isset($db['pw']) ) ? $db : $conf; 
	
	//die(var_dump($db['name']));
	
	if ( $db['name'] == null ){
		
		//don't pass $db['name'] to mysqli_connect if null.
		
		//suppress mysqli warning on fail
		$mysqli = @mysqli_connect($db['host'], $db['user'], $db['pw']);
		
		Console::tell("DB_NAME for mysql connection is null. Ignoring...");
		
	}
	else {
		
		//suppress mysqli warning on fail
		$mysqli = @mysqli_connect($db['host'], $db['user'], $db['pw'], $db['name']);	
		
	}
	
	if (mysqli_connect_errno($mysqli)) {
		
		if (!$suppress_console_msg) Console::tell("Failed to connect to MySQL: " . mysqli_connect_error());
		return false;
		
	}
	else {
		return $mysqli;
	}
	
}

function phoenixUserIsLoggedIn() {

	$session = new Secure( "phoenix-secure-session" );
	
	if ($session) Console::tell("User is not logged in. Returning false...");
	
	return ( $session->isValid() );

}

function getCurrentPhoenixUserData() {
	
	$user = new User();	
	
	return array( "id" => $user->id, "login" => $user->login->userid );

}

function pageRequest( $request = null ) {

	$request = ($request == null) ? getRequest() : $request;
	
	Console::tell("Requesting page... REQ_STR: $request");
	
	$plugins = new PluginLoader();
	$plugins->loadAll();
	
	$plugins->registerPages();
	
	$page = new Page($request);
	
	//die(var_dump($page));
	
	if ( $page->exists ):
		
		if ( pageIsAccessibleByCurrentUser( $page, $request ) ):
			
			getPage( $request, $page );
			
		else:
		
			getThemeTemplate("login", $request, "You must login to view this page.");
		
		endif;
	
	else:
		
		if ( $page->count > 0 ):
		
			Console::tell("404 ERROR: No registered pages with slug '{$page->slug}' were found.");
	
			getThemeTemplate( "404" );
		
		else:
		
			getThemeTemplate( "welcome" );
			
			Console::tell("No pages exist. Current page count: $count");
		
		endif;
	
	endif;//exists
    
}

function getRequest() {
	
	//get page request
	$request = isset( $_GET['p'] ) ? $_GET['p'] : "index";
	
	return $request;
	
}

function getPage( $slug, $page = null, $plugins = null ) {

	/*
		
		
		
		
	*/
	
	$form = new Form();

	Console::tell("Fetching page with slug $slug");

	getThemeFile("header");
	
	if( $exists = pageExists( $slug, $plugins->pages ) ) {
		
		$plugin = $plugins->pages["$slug"]["plugin"];
	
		if ( is_object($page) ) {
			
			if ( $page->requires_template ){
				
				getThemeFile( "page", $slug, null, $page );
			
			}
			else {
				
				getThemeFile( "page-$slug", $slug, null, $page );
				
			}
			
			
		}
		
	}
	else {
		
		Console::tell("404: Page not found in page index.");
		
		//send the request string as the message.
		getThemeFile( "404", $slug, $requeststr );
		
	}
	
	getThemeFile("footer");
	
}

function goToPage( $request ) {
	
	header("location: /?p=$request");
	
}

function templateFileExists( $file ) {
	
	if ( file_exists( ROOT . "/p/$file.php" ) ) {
		
		return "p";
		
	}
	elseif ( file_exists( ROOT . "/p/theme/$file.php") ) {
		
		return "p/theme";
		
	}
	else {
		
		Console::tell("File not found in template.");
		return false;
		
	}
	
	return $file;
	
}

function getThemeTemplate( $slug, $error = null ) {
	
	/*
		
		Includes theme file with header & footer, treats slug as a page.
		
	*/
	
	$form = new Form();
	
	Console::tell("Fetching theme template $slug");
	
	//dont allow these because they are already being included.
	$blacklist_files = array("header", "footer");
	
	if ( !in_array($slug, $blacklist_files) ) {
		
		getThemeFile( "header" );
		getThemeFile( $slug, null, $error );
		getThemeFile( "footer" );	
		
	}
	else {
		
		Console::tell("File with slug $slug cannot be required as a template file. Use getThemeFile( str $slug ) instead.");
		
	}
	
}

function getPageTemplate( $slug ) {
	
	if ( templateFileExists( "page-$slug" ) )
		
		require_once(ROOT . "/p/page-$slug.php");
	
}

function pageTemplateExists() {
	
	return templateFileExists( "page-$slug" );
	
}

function getThemeFile( $file, $request = null, $message = null, Page $page = null ) {

	/*
		
		
		
		
	*/

	Console::tell("Fetching Theme File: $file");
	
	if ( $dir = templateFileExists($file) ):
		
		Console::tell("Requiring file: ". ROOT . "/$dir/$file.php");
		$require = require_once(ROOT . "/$dir/$file.php");
	
	else:
	
		Console::tell("Could not get theme file. Slug: $file Request String: $request");
	
	endif;
	
}

function pageIsAccessibleByCurrentUser( $page, $slug ) {
	
	/*
		
		
		
		
	*/
	
	
	//die(var_dump($page));
	
	
	if ($page->is_protected) {
		
		if (phoenixUserIsLoggedIn()) {
			
			$permissions = new Permissions();
		
			return $permissions->userHasPermission("phoenix.page.access.$slug");
			
			
		}
		else {
			
			return false;
			
		}
		
	}
	else {
		
		return true;
		
	}
	
	
	
}

function pageExists( $page, $plugin_pages ) {
	
	/*
		
		
		
		
	*/
	
	$file = $page;
	
	
	$theme_file = ( file_exists( ROOT . "/p/page-$file.php") || file_exists( ROOT . "/p/theme/page-$file.php") || file_exists( ROOT . "/p/theme/$file.php") || file_exists( ROOT . "/p/$file.php") );
	
	$plugin_page = is_array($plugin_pages) ? array_key_exists( $page, $plugin_pages) : false;
	
	return ($theme_file || $plugin_page);
	
}

function redirect( $url ) {
    
    header("location: $url");
    
}

function dbug_gui() {


	//phoenix_done();
    
    //if (DBUG_GUI_ENABLED):
    
	    //echo "<p>Request: " . $request . "</p>";
	    //echo "<p>Query Str:" . $db->querystr . "</p>";
	    
	    $msgs = unserialize( Console::getall() );
	    
	    echo "<p>Messages: </p>";
	    	
	    	$x = 1;
	    
	    	foreach( $msgs as $msg):
	    	
	    		$backtrace = $msg[1];
	    		$backtrace = basename($backtrace[0]['file']) . ":" . $backtrace[0]['line'];
			
	    		echo "<span class='gui-line-num'>[$x]</span> <b>" . $msg[0] . "</b> $backtrace<br/>";
	    		
	    		$x++;
	    	
	    	endforeach;
	    
	    echo "</p>";
    
    //endif;
    
}

function loadSQLFile( $path ) {
	
	$sql_file = file($path);
				
	$queries = "";
	
	foreach( $sql_file as $line ){
		
		if (strpos($line, "#") !== 0 && strpos($line, "/*!") !== 0) {
			
			$queries .= $line;
			
		}
		
	}
	
	$queries = explode(";", $queries);
	
	foreach($queries as $query) {
		
		rtrim($query);//remove trailing ";"
		
		$query = $mysqli->query($query);
		
		Console::tell(print_r($query, true));
		
	}
	
	
}

function fatalError( $code ) {
	
	
	switch ($code) {
		
		case 0:
			
			//phoenix system
		
		break;
		
		case 1:
			
			$error = "Plugin fault";
		
		break;
		
		default:
		
			//unkown
		
		break;
		
	}
	
	getThemeTemplate("phoenix-error", $error);
	
}

function stylesheets() {
	
	
	
}