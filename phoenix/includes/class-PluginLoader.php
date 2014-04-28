<?php

class PluginLoader  {

	use Phoenix;
	
	public $registered;
	public $pages;
	public $forms;
	
	public $navbaritems;
	
	public $id;
	public $name;
	public $path;
	
	function __construct() {
		
		$this->getPDO("plugins");
		
	}
	
	public function enabled() {
		
		$plugin = $this->db->getRowData( "/{$this->id}/", array("path") );
		
		return ( $plugin['enabled'] == "true" );
		
	}
	
	public function loadAll() {
		
		if (phoenixIsInstalled() ):
		
			if (connectedToDb()):
			
				Console::tell("Loading all plugins...");
				
				$plugins = glob(ROOT . "/plugins/*");
				
				//die(var_dump($plugins));
				
				foreach( $plugins as $path ):
					
					//var_dump($plugin);
					
					$this->path = $path;
					
					if ($this->isPlugin())
						$this->load();						
					
				endforeach;
				
				$pages = array();
				
			else:
			
				Console::tell("Cancelled plugin loader due to MYSQL connection issue...");
				
			endif;
			
		endif;
	}
	
	public function isPlugin() {
		
		if (!strstr($this->path, ".")):
			
			$files_exist = true;
		
			$plugin = glob( $this->path . "/*" );
			
			$required = array("main.php", "readme.txt");
			
			foreach($required as $file) {
				
				if (!file_exists($this->path . "/$file")) {
					
					$files_exist = false;
					Console::tell("Plugin missing required file $file. Load canceled. Plugin Directory:" . basename($this->path));
					
				}
				
			}
			
			return $files_exist;
		
		endif;
		
	}
	
	public function getId() {
		
		//convert test_string or test-string to TestString
		$plugin = str_replace("_", " ", $this->path);
		$plugin = str_replace("-", " ", $this->path);
		$plugin = ucwords($this->path);
		$plugin = str_replace(" ", "", $this->path);
		
		$this->id = $plugin;
		
		return $this->id;
		
	}
	
	public function load() {
		
		$this->id = str_replace( "/", "", basename($this->path));
		
		if (!strstr($this->id, ".")) {
					
			if ( $this->enabled() ) {
			
				Console::tell("Loading Plugin {$this->id}");
				
				$main_path = $this->path . "/main.php";
				
				if (file_exists( $main_path )):
				
					Console::tell("Loading AdminPanel main.php");
					
					$exec = array();
					
					exec('php -l "'.$main_path.'"', $exec['out'], $exec['return']);
				
					if ($exec == 0) {
						
						require_once(ROOT . "/plugins/{$this->id}/main.php");
					
						//convert test_string or test-string to TestString
						$name = str_replace("_", " ", $this->name);
						$name = str_replace("-", " ", $name);
						$name = ucwords($name);
						$this->name = str_replace(" ", "", $name);	
							
						if ( class_exists($this->name) ):
							
							$instance_name = $this->name;
							$instance = new $instance_name();
							
							if ( !empty($instance->plugin_id) ):
							
								if ( $instance->actions ):
									
									foreach( $instance->actions as $action ):
									
										$this->registerAction( $instance->plugin_id, $action['hook'], $action['args'] );
									
									endforeach;
									
								endif;
							
							else:
							
								Console::tell("Fatal error: Invalid plugin id for {$this->path}.");
								
							endif;
							
							
						else://class_exists
						
							Console::tell("Class for plugin {$this->name} not found. Check your syntax.");
						
						endif;
						
					}
					else {
						
						Console::tell("FATAL PLUGIN ERROR: {$exec['out'][1]}");
						Console::tell("Failed to load plugin due to fatal errors.");
						
					}
					
				else:
				
					Console::tell("main.php not found for plugin {$this->name}");
				
				endif;
			
				
			}//plugin enabled?
			else {
				
				Console::tell("Plugin located in {$this->path} is not enabled by admin.");
				
			}
			
		}//strstr

		
	}
	
	public function install() {
		
		if ( $this->enabled ) {
			
			
			
		}
		
	}
	
	public function pageExists( $request ) {
		
		
		return array_key_exists("$request", $this->pages);
		
	}
	
	public function getPages( $request ) {
		
		if ($this->pageExists($request)):
		
			$plugin = $this->pages["$request"]['plugin'];
	    	
	    	
			Console::tell(ROOT . "/plugins/$plugin/$request.php");
			
			if (file_exists(ROOT . "/plugins/$plugin/$request.php")){
				
				Console::tell("Fetching page \"$request\" for plugin.");
			
				require_once(ROOT . "/plugins/$plugin/$request.php");
			}
			else {
				Console::tell("<b>Plugin page file $request.php not found in $plugin</b>");
			}
			
		endif;
		
	}
	
	public function registerPages() {
		
		
		
		
	}
	
	public function registerAction( $plugin, $hook, $args ) {
	
		///die(var_dump($hook));
		
		Console::tell("Registering Action Hook $hook");
		
		switch( $hook ) {
			
			case "add-page":
				
				$this->registerPage( $plugin, $args['file'], $args['protected'] );
			
			break;
			
			case "form-handler":
			
			/*
				
				Connect a form to a specific method.
				
			*/
				
			
				$form   = $args['form_name'];
				$method = $args['trigger_method'];
				
				$this->registerForm( $form, $method );
			
			break;
			
			case "add-stylesheet":
				
				/* check to make sure file extension is .css!!! other extensions could be malicious!*/
			
			break;
			
			case "add-navbar-item":
				
				$this->registerNavbarItem( $args );
			
			break;
			
			default:
			
				Console::tell("Plugin registered invalid or depreciated action hook \"$hook\".");
			
			break;
			
			
		}
		
	}
	
	public function registerForm( $form_name, $trigger_method ) {
		
		Console::tell("Registering form $form_name for method $trigger_method");
		$this->form[] = array("form_name"=>$form_name, "trigger_method" => $trigger_method );
		
	}
	
	public function registerNavbarItem( array $item ) {
		
		$this->navbaritems[] = $item;
		
	}
	
	public function registerPage( $plugin, $page_file, $protected ) {
	
		$protected_str = $protected ? "YES" : "NO";
		Console::tell("Registering page $page_file for $plugin... Protected: $protected_str");
		
		$this->pages["$page_file"] = array("plugin"=>$plugin, "protected" => $protected);
		
	}
	
	public function getRegisteredPages() {
		
		return $this->pages;
		
	}
	
}