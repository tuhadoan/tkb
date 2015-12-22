<?php

if (! class_exists ( 'DTInit' )) :
	class DTInit {
		public $version = '1.0.0';
		
		public function __construct() {
			$this->_define_constants ();
			$this->_includes ();
			add_action('init', array(&$this,'init'));
		}
		
		public function init(){
			
		}
		private function _define_constants() {

			if(!defined('SITESAO_CORE_VERSION'))
				define('SITESAO_CORE_VERSION', '1.1.5');


			if(!defined('SITESAO_CORE_URL'))
				define('SITESAO_CORE_URL', get_template_directory_uri());

			if(!defined('SITESAO_CORE_DIR'))
				define('SITESAO_CORE_DIR', get_template_directory());

			if(!defined('DTINC_VERSION'))
				define( 'DTINC_VERSION', $this->version );
			if(!defined('DTINC_DIR'))
				define( 'DTINC_DIR', get_template_directory() . '/inc');
			if(!defined('DTINC_URL'))
				define( 'DTINC_URL', get_template_directory_uri() . '/inc' );
			if(!defined('DTINC_ASSETS_URL'))
				define( 'DTINC_ASSETS_URL', get_template_directory_uri() . '/assets' );
		}
		private function _includes() {
			
			// Utils
			include_once (DTINC_DIR . '/functions.php');
			
			// Register
			include_once (DTINC_DIR . '/register.php');
			// Hook
			include_once (DTINC_DIR . '/hook.php');
			
			// Widget
			include_once (DTINC_DIR . '/widget.php');
			// Breadcrumb
			include_once (DTINC_DIR . '/breadcrumb.php');
			
			// Admin
			if (is_admin ()) {
				include_once (DTINC_DIR . '/admin/functions.php');
				include_once (DTINC_DIR . '/admin/admin.php');
			}
			
			//Controller
			include_once (DTINC_DIR . '/controller.php');
		}
		
	}
	new DTInit ();
endif;
