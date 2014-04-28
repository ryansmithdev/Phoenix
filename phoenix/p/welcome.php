<?
	
	$welcome = isset($_GET["step"]) ? $_GET['step'] : "0";

	if (!phoenixIsInstalled() || $welcome == 4):
	
?>

<p>DB Status: <? echo (connectedToDb( null, true )) ? "<span style='color:green;'>connected</span>" : "<span style='color:red;'>disconnected</span>";?></p>

<? switch($welcome): case "0":?>
<h1 class="center">Welcome To Phoenix</h1>
<p>Let's install this awesomeness!</p>

<a href="?p=welcome&step=1">Let's Go ></a>

<p>Already Installed Phoenix? This may be an error.</p>
<p>Please make sure <b>config.php</b> exists in your installation directory.</p>

<? break; case "1":?>
<p>Install Phoenix</p>
<h1>Configuration</h1>

<form method="post" action="">
	
	<input type="hidden" name="phoenix-install-step" value="db_info"/>
	
	<p>DB Host: <input type="text" name="db_host" value="127.0.0.1"/></p>
	<p>DB Name: <input type="text" name="db_name" value="phoenix"/><input type="checkbox" name="db_create_new"/> New Database</p>
	<p>DB User: <input type="text" name="db_user" value="root"/>
	<p>DB Password: <input type="text" name="db_pw" value="root"/></p>
	
	<h2>General</h2>
	
	<p><input type="checkbox" name="enable_debug_mode" value="YES"/> Enable Debug Mode</p>
	
	<p>Timezone:
	
		<select name="timezone_set">
		
			<option value="America/New_York">America/New_York</option>
		
		</select>
		
	</p>
	
	<p><a href="?p=welcome&step=0">&lt; Back</a> | <input type="submit" value="Next &gt;"/></p>
	
</form>

<? break; case "2":?>

<p>Install Phoenix</p>
<h1>Site Information</h1>
<form method="post" action="">
	
	<input type="hidden" name="phoenix-install-step" value="load_db"/>
	
	<p>Site Name: <input type="text" value="My Site Name" name="site_name"/></p>
	
	<p>Create New User</p>
	<p>Username: <input type="text" value="admin" name="user_un"/></p>
	
	<p>Password: <input type="password" value="admin" name="user_pw"/></p>
	<p>Password (confirm): <input type="password" value="admin" name="user_pw2"/></p>
	
	<p>Email: <input type="text" value="ryanesmith@me.com" name="user_email"/></p>
	
	<p><input type="checkbox" name="se_indexing"/> Allow Search Engine Indexing</p>	
	
	<p><a href="?p=welcome&step=1">&lt; Back</a> | <input type="submit" value="Next &gt;"/></p>
	
</form>


<? break; case "3":?>
<p>Install Phoenix</p>
<h1>Finish</h1>

<form method="post" action="">
	
	<input type="hidden" name="phoenix-install-step" value="finish_install"/>

	<p>Ready to complete the install?</p>

	<p><a href="?p=welcome&step=2">&lt; Back</a> | <input type="submit" value="Finish &gt;"/></p>

</form>

<? break; case "4":?>
<p>Install Phoenix</p>
<h1>Installation Complete.</h1>

	<p>Phoenix successfully installed with 0 errors.</p>

	<p><a href="?p=login">Login</a></p>
	
<? break; endswitch;?>

<? else: ?>
	
	<h1>Phoenix Is Installed! But...</h1>
	<p>It looks like you haven't added any content yet :( Fix that by <a href="">Logging in</a></p>

<? endif; //isInstalled() ?>