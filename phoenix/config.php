<?php

/*
	
	PHOENIX SECURITY_KEY *REQUIRED*
	
	This string of characters is used to generate hashes, passwords, and other security sprites used 
	throughout the system. A new key is generated on installation. It is recommended that you change
	this key frequently. This key must contain numbers, mixed-case letters, and multiple other
	characters. (!@#$%^&*)
	
*/
define("SECURITY_KEY", "1234");


//don't remove this
define("PHOENIX_INSTALLED", "YES");


/*
	
	ADMIN EMAIL *RECOMMENDED*
	
	Although this is not used in current versions of Phoneix (as of 1.0), we ask that you provide your email in the DEFINE() below. 
	This email could be used for resetting your account incase of a database or FTP lockout. Make sure that only you have access to this email.
	
*/

define("ADMIN_EMAIL", "ryanesmith@me.com");

/*
	
	DATABASE INFORMATION *REQUIRED*
	
	This information is placed here for you on installation. Do not modify this info if your installation
	is connected successfully to your database.
	
	
*/

define( "DB_HOST", "127.0.0.1" );
define( "DB_NAME", "phoenix" );
define( "DB_USER", "root" );
define( "DB_PASS", "root" );

/*
	
	TIMEZONE SET *RECOMMENDED*
	
	This setting is used to generate timestamps and the current time in the entire Phoneix system. (including plugins)
	Plugins and features like calendars or clocks will not work accurately if your time zone is not set correctly.
	
*/

define( "TIMEZONE_SET", "America/New_York" );

define( "DEBUG_MODE_ENABLED", "YES" );