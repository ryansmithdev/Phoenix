<h1>Admin</h1>
<?php

if (isset($_GET["a"])) {
	
	switch($_GET["a"]) {
		
		default:
		
		break;
		
		case "prefs":
		
			require_once("prefs.php");
		
		break;
		
	}
	
}