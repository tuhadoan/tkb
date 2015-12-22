<?php

if(!class_exists('DTAdmin')):
class DTAdmin {
	public function __construct(){
		
		include_once (dirname( __FILE__ ) . '/meta-boxes.php');
		include_once (dirname( __FILE__ ) . '/mega-menu.php');
		include_once (dirname( __FILE__ ) . '/theme-options.php');
		// Import Demo
		include_once (dirname( __FILE__ ) . '/import-demo.php');
			
		
		add_action( 'admin_init', array(&$this,'admin_init'));
		add_action('admin_enqueue_scripts',array(&$this,'enqueue_scripts'));
		//Disnable auto save
		add_action( 'admin_print_scripts', array( &$this, 'disable_autosave' ) );
	}
	
	public function disable_autosave(){
		global $post;
	
		//if ( $post && get_post_type( $post->ID ) === 'page-section' ) {
			wp_dequeue_script( 'autosave' );
		//}
	}
	
	public function admin_init(){
		
		if(post_type_exists('page-section')){
			$pt_array = ( $pt_array = get_option( 'wpb_js_content_types' ) ) ? ( $pt_array ) :  array( 'page' );
			if(!in_array('page-section', $pt_array)){
				array_push($pt_array,'page-section');
				update_option('wpb_js_content_types', $pt_array);
			}
		}
		
		if (get_user_option('rich_editing') == 'true') {
			add_filter('mce_external_plugins', array($this, 'mce_external_plugins'));
			add_filter('mce_buttons', array($this, 'mce_buttons'));
		}
	}
	
	public function mce_external_plugins($plugins){
		$plugins['dt_tooltip'] = DTINC_ASSETS_URL. '/js/tooltip_plugin.js';
		return $plugins;
	}
	public function mce_buttons($buttons){
		$buttons[] = 'dt_tooltip_button';
		return $buttons;
	}
	
	public function enqueue_scripts(){
		
		wp_enqueue_style('dt-admin',DTINC_ASSETS_URL.'/css/admin.css',false,DTINC_VERSION);
		
		wp_register_script('dt-admin',DTINC_ASSETS_URL.'/js/admin.js',array('jquery'),DTINC_VERSION,true);
		$dtAdminL10n = array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'theme_url'=>get_template_directory_uri(),
			'framework_assets_url'=>DTINC_ASSETS_URL,
			'i18n_tooltip_mce_button'=>esc_js(__('Tooltip Shortcode','ticketbox')),
			'tooltip_form'=>$this->_tooltip_form()
		);
		wp_localize_script('dt-admin', 'dtAdminL10n', $dtAdminL10n);
		wp_enqueue_script('dt-admin');
	}
	
	protected function _tooltip_form(){
		ob_start();
		?>
		<div class="dt-tooltip-form">
			<div class="dt-tooltip-options">
				<div>
					<label>
						<span><?php _e('Text','ticketbox')?></span>
						<input data-id="text" type="text" placeholder="<?php _e('Text','ticketbox')?>">
					</label>
				</div>
				<div>
					<label>
						<span><?php _e('URL','ticketbox')?></span>
						<input data-id="url" type="text" placeholder="<?php _e('http://','ticketbox')?>">
					</label>
				</div>
				<div>
					<label>
						<span><?php _e('Type','ticketbox')?></span>
						<select data-id="type">
							<option value="tooltip"><?php _e('Tooltip','ticketbox') ?></option>
							<option value="popover"><?php _e('Popover','ticketbox') ?></option>
						</select>
					</label>
				</div>
				<div>
					<label>
						<span><?php _e('Tip position','ticketbox')?></span>
						<select data-id="position">
							<option value="top"><?php _e('Top','ticketbox') ?></option>
							<option value="bottom"><?php _e('Bottom','ticketbox') ?></option>
							<option value="left"><?php _e('Left','ticketbox') ?></option>
							<option value="right"><?php _e('Right','ticketbox') ?></option>
						</select>
					</label>
				</div>
				<div style="display: none;">
					<label>
						<span><?php _e('Title','ticketbox')?></span>
						<input data-id="title" type="text" placeholder="<?php _e('Title','ticketbox')?>">
					</label>
				</div>
				<div>
					<label>
						<span><?php _e('Content','ticketbox')?></span>
						<textarea data-id="content" placeholder="<?php _e('Content','ticketbox')?>"></textarea>
					</label>
				</div>
				<div>
					<label>
						<span><?php _e('Action to trigger','ticketbox')?></span>
						<select data-id="trigger">
							<option value="hover"><?php _e('Hover','ticketbox') ?></option>
							<option value="click"><?php _e('Click','ticketbox') ?></option>
						</select>
					</label>
				</div>
			</div>
			<div class="submitbox">
				<div id="dt-tooltip-cancel">
					<a href="#"><?php _e('Cancel','ticketbox')?></a>
				</div>
				<div id="dt-tooltip-update">
					<input type="button" class="button button-primary" value="<?php _e('Add Tooltip','ticketbox')?>">
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
new DTAdmin();
endif;
