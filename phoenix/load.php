<?php

/*
	
	(C) Copyright 2014 Ryan Smith Developer, LLC.
	
	Phoenix is not a registered trademark of Ryan Smith Developer or Thirty and Seven, LLC. 
	
	*******************
	
	This file loads the phoenix environment and fetches necessary dependencies. 
	
*/

session_start();

@require_once("includes/pre.php");

if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/phoenix/config.php")) 
	require_once("config.php");
elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . "/phoenix/config_draft.php")) 
	require_once("config_draft.php");

@require_once("includes/class-Console.php");

@require_once("includes/phoenix.php");
@require_once("includes/functions/page.php");
@require_once("includes/functions/functions.php");
@require_once("includes/install.php");

@require_once("includes/trait-Phoenix.php");
@require_once("includes/class-Command.php");
@require_once("includes/class-Commands.php");
@require_once("includes/class-Form.php");
@require_once("includes/class-Mysql.php");
@require_once("includes/class-Page.php");
@require_once("includes/class-Permissions.php");
@require_once("includes/class-Plugin.php");
@require_once("includes/class-PluginLoader.php");
@require_once("includes/class-Preferences.php");
@require_once("includes/class-Projects.php");
@require_once("includes/class-Secure.php");
@require_once("includes/class-Update.php");
@require_once("includes/class-User.php");

function phoenix_start(){
	
	if ( phoenixIsInstalled() ):
	
		if ( connectedToDb() ):
		    
		    pageRequest();
		
		else:
			
			getThemeTemplate( "mysql-error" );
			
			Console::tell("Mysql error...");
			
		endif;
	
	else:
	
		getThemeTemplate( "welcome" );
	
	endif;
	
}

$shutdown->cancel();