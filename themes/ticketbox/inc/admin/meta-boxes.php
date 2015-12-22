<?php
if (! class_exists ( 'DTMetaboxes' )) :
	class DTMetaboxes {
		public function __construct() {
			add_action ( 'add_meta_boxes', array (&$this, 'add_meta_boxes' ), 30 );
			add_action ( 'save_post', array (&$this,'save_meta_boxes' ), 1, 2 );
			
			//add_action( 'admin_enqueue_scripts', array( &$this, 'assets' ) );
			add_action( 'admin_print_scripts-post.php', array( &$this, 'enqueue_scripts' ) );
			add_action( 'admin_print_scripts-post-new.php', array( &$this, 'enqueue_scripts' ) );
			
		}
		
		public function add_meta_boxes() {
			global $wp_version;
			// Post Gallery
			$meta_box = array (
					'id' => 'dt-metabox-post-gallery',
					'title' => __ ( 'Gallery Settings', 'ticketbox' ),
					'description' =>'',
					'post_type' => 'post',
					'context' => 'normal',
					'priority' => 'high',
					'fields' => array (
							array (
									'label' => __ ( 'Gallery', 'ticketbox' ),
									'name' => '_dt_gallery',
									'type' => 'gallery',
							),
					)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dt_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
			
			//Post Quote
			$meta_box = array(
					'id' => 'dt-metabox-post-quote',
					'title' =>  __('Quote Settings', 'ticketbox'),
					'description' => '',
					'post_type' => 'post',
					'context' => 'normal',
					'priority' => 'high',
					'fields' => array(
							array(
									'label' =>  __('Quote Content', 'ticketbox'),
									'description' => __('Please type the text for your quote here.', 'ticketbox'),
									'name' => '_dt_quote',
									'type' => 'textarea',
							)
					)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dt_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
			
			//Post Link
			$meta_box = array(
				'id' => 'dt-metabox-post-link',
				'title' =>  __('Link Settings', 'ticketbox'),
				'description' => '',
				'post_type' => 'post',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array(
					array(
						'label' =>  __('Link URL', 'ticketbox'),
						'description' => __('Please input the URL for your link. I.e. http://www.example.com', 'ticketbox'),
						'name' => '_dt_link',
						'type' => 'text',
					)
				)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dt_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
			
			//Post  Video
			$meta_box = array(
					'id' => 'dt-metabox-post-video',
					'title' => __('Video Settings', 'ticketbox'),
					'description' => '',
					'post_type' => 'post',
					'context' => 'normal',
					'priority' => 'high',
					'fields' => array(
							array(
								'type' => 'heading',
								'heading'=>__('Use service video','ticketbox'),
							),
							array(
									'label' => __('Embedded Code', 'ticketbox'),
									'description' => __('Used when you select Video format. Enter a Youtube, Vimeo, Soundcloud, etc URL. See supported services at <a href="http://codex.wordpress.org/Embeds">http://codex.wordpress.org/Embeds</a>.', 'ticketbox'),
									'name' => '_dt_video_embed',
									'type' => 'text',
									'hidden'=>true,
							),
							array(
								'type' => 'heading',
								'heading'=>__('Use hosted video','ticketbox'),
							),
							array(
									'label' => __('MP4 File URL', 'ticketbox'),
									'description' => __('Please enter in the URL to the .m4v video file.', 'ticketbox'),
									'name' => '_dt_video_mp4',
									'type' => 'media',
							),
							array(
									'label' => __('OGV/OGG File URL', 'ticketbox'),
									'description' => __('Please enter in the URL to the .ogv or .ogg video file.', 'ticketbox'),
									'name' => '_dt_video_ogv',
									'type' => 'media',
							),
							array(
									'label' => __('WEBM File URL', 'ticketbox'),
									'description' => __('Please enter in the URL to the .webm video file.', 'ticketbox'),
									'name' => '_dt_video_webm',
									'type' => 'media',
							),
					)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dt_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
			
			//Post  Audio
			$meta_box = array(
				'id' => 'dt-metabox-post-audio',
				'title' =>  __('Audio Settings', 'ticketbox'),
				'description' => '',
				'post_type' => 'post',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array(
					array( 
							'label' => __('MP3 File URL', 'ticketbox'),
							'description' => __('Please enter in the URL to the .mp3 file', 'ticketbox'),
							'name' => '_dt_audio_mp3',
							'type' => 'media',
					),
					array( 
							'label' => __('OGA File URL', 'ticketbox'),
							'description' => __('Please enter in the URL to the .ogg or .oga file', 'ticketbox'),
							'name' => '_dt_audio_ogg',
							'type' => 'media',
						)
				)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dt_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
			
			//Post Settings
			$meta_box = array (
					'id' => 'dt-metabox-setting',
					'title' => __ ( 'Post Settings', 'ticketbox' ),
					'description' =>'',
					'post_type' => 'post',
					'context' => 'normal',
					'priority' => 'high',
					'fields' => array (
							array (
									'label' => __ ( 'Featured', 'ticketbox' ),
									'description' => __ ( 'If checked. this post will show in highlighted block shortcode in Visual Composer', 'ticketbox' ),
									'name' => 'featured',
									'cbvalue'=>'1',
									'type' => 'checkbox',
							),
							array (
									'label' => __ ( 'Masonry Item Sizing', 'ticketbox' ),
									'description' => __ ( 'This will only be used if you choose to display your blog in the masonry/grid format.', 'ticketbox' ),
									'name' => '_dt_masonry_size',
									'type' => 'select',
									'options'=>array(
											'normal'=>__('Normal','ticketbox'),
											'double'=>__('Double','ticketbox')
									)
							),
					)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dt_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );

			//Page Settings
			$revsliders = array();
			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once ( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			if ( is_plugin_active( 'revslider/revslider.php' ) ) {
				global $wpdb;
				$rs = $wpdb->get_results(
						"
					  SELECT id, title, alias
					  FROM " . $wpdb->prefix . "revslider_sliders
					  ORDER BY id ASC LIMIT 999
					  "
				);
				if ( $rs ) {
					foreach ( $rs as $slider ) {
						$revsliders[$slider->alias] = $slider->title;
					}
				} else {
					$revsliders[0] = __( 'No sliders found', 'ticketbox' );
				}
			}
			$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
			$menu_options[''] = __('Default Menu...','ticketbox');
			foreach ( $menus as $menu ) {
				$menu_options[$menu->term_id] = $menu->name;
			}
			$meta_box = array (
					'id' => 'dt-metabox-page-settings',
					'title' => __ ( 'Page Settings', 'ticketbox' ),
					'description' =>'',
					'post_type' => 'page',
					'context' => 'normal',
					'priority' => 'high',
					'fields' => array (
							array (
									'label' => __ ( 'Content Page no Padding', 'ticketbox' ),
									'description' => __ ( 'If checked. content of page  with no padding top and padding bottom', 'ticketbox' ),
									'name' => '_dt_no_padding',
									'type' => 'checkbox',
							),
							array (
								'label' => __ ( 'Main Navigation Menu', 'ticketbox' ),
								'description' => __ ( 'Select which main menu displays on this page.', 'ticketbox' ),
								'name' => 'main_menu',
								'type' => 'select',
								'value'=>'',
								'options'=>$menu_options,
							),
							array (
									'label' => __ ( 'Main Sidebar', 'ticketbox' ),
									'description' => __ ( 'Select sidebar for page with 2 or 3 colums.', 'ticketbox' ),
									'name' => 'main_sidebar',
									'type' => 'widgetised_sidebars',
							),
							array (
									'label' => __ ( 'Header Style', 'ticketbox' ),
									'description' => __ ( 'Please select your header style here.', 'ticketbox' ),
									'name' => 'header_style',
									'type' => 'select',
									'options'=>array(
											'-1'=>__('Global','ticketbox'),
											'center'=>__('Center','ticketbox'),
											'below'=>__('Below','ticketbox')
									)
							),
							array (
									'label' => __ ( 'Topbar', 'ticketbox' ),
									'description' => __ ( 'Enable or disable the top bar.', 'ticketbox' ),
									'name' => 'show_topbar',
									'type' => 'select',
									'options'=>array(
											'-1'=>__('Global','ticketbox'),
											'1'=>__('Show','ticketbox'),
											'0'=>__('Hide','ticketbox')
									)
							),
							array (
									'label' => __ ( 'Transparent Main Menu', 'ticketbox' ),
									'description' => __ ( 'Enable or disable main menu background transparency.', 'ticketbox' ),
									'name' => 'menu_transparent',
									'type' => 'select',
									'options'=>array(
											'-1'=>__('Global','ticketbox'),
											'1'=>__('On','ticketbox'),
											'0'=>__('Off','ticketbox')
									)
							),
							array (
									'label' => __ ( 'Page Heading', 'ticketbox' ),
									'description' => __ ( 'Enable/disable page heading or custom page heading', 'ticketbox' ),
									'name' => 'page_heading',
									'type' => 'select',
									'value'=>'heading',
									'options'=>array(
											'heading'=>__('Heading','ticketbox'),
// 											'landingHero'=>__('Landing Hero','ticketbox'),
											'rev'=>__('Use Revolution Slider','ticketbox'),
											'0'=>__('Hide','ticketbox')
									)
							),
							array (
								'label' => __ ( 'Heading Menu Anchor ', 'ticketbox' ),
								'description' => __ ( 'Add menu anchor for heading. You can use in One Page', 'ticketbox' ),
								'name' => 'heading_menu_anchor',
								'type' => 'text',
							),
// 							array (
// 									'label' => __ ( 'Highlighted Slider Category', 'ticketbox' ),
// 									'description' => __ ( 'Select category for highlighted block slider', 'ticketbox' ),
// 									'name' => 'highligh_cat',
// 									'type' => 'categories',
// 							),
// 							array (
// 									'label' => __ ( 'Highlighted Intro Category', 'ticketbox' ),
// 									'description' => __ ( 'Select category for highlighted block intro', 'ticketbox' ),
// 									'name' => 'highligh_intro_cat',
// 									'type' => 'categories',
// 							),
							array (
									'label' => __ ( 'Revolution Slider', 'ticketbox' ),
									'description' => __ ( 'Select your Revolution Slider.', 'ticketbox' ),
									'name' => 'rev_alias',
									'type' => 'select',
									'options'=>$revsliders,
							),
							array (
									'label' => __ ( 'Page Heading Background Image', 'ticketbox' ),
									'description' => __ ( 'Custom heading background image.', 'ticketbox' ),
									'name' => 'page_heading_background_image',
									'type' => 'image',
							),
							array (
									'label' => __ ( 'Page Heading Title', 'ticketbox' ),
									'description' => __ ( 'Custom heading title.', 'ticketbox' ),
									'name' => 'page_heading_title',
									'type' => 'text',
							),
							array (
									'label' => __ ( 'Page Heading Sub-title', 'ticketbox' ),
									'description' => __ ( 'Custom heading sub-title.', 'ticketbox' ),
									'name' => 'page_heading_sub_title',
									'type' => 'text',
							),
							array (
									'label' => __ ( 'Footer Widget Area', 'ticketbox' ),
									'description' => __ ( 'Do you want use the main footer that contains all the widgets areas.', 'ticketbox' ),
									'name' => 'footer_area',
									'type' => 'select',
									'options'=>array(
											'-1'=>__('Global','ticketbox'),
											'1'=>__('Show','ticketbox'),
											'0'=>__('Hide','ticketbox')
									)
							),
							array (
									'label' => __ ( 'Footer', 'ticketbox' ),
									'description' => __ ( 'Do you want show/hide footer.', 'ticketbox' ),
									'name' => 'footer_info',
									'type' => 'select',
									'options'=>array(
											'-1'=>__('Global','ticketbox'),
											'1'=>__('Show','ticketbox'),
											'0'=>__('Hide','ticketbox')
									)
							),
							array (
								'label' => __ ( 'Footer Menu', 'ticketbox' ),
								'description' => __ ( 'Do you want use menu in main footer.', 'ticketbox' ),
								'name' => 'footer_menu',
								'type' => 'select',
								'options'=>array(
									'-1'=>__('Global','ticketbox'),
									'1'=>__('Show','ticketbox'),
									'0'=>__('Hide','ticketbox')
								)
							),
					)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dt_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
				
			
		}
		
		public function add_video_featured_image($att_id){
			$p = get_post($att_id);
			update_post_meta($p->post_parent,'_thumbnail_id',$att_id);
		}
		
		
		public function save_meta_boxes($post_id, $post) {
			// $post_id and $post are required
			if (empty ( $post_id ) || empty ( $post )) {
				return;
			}
			// Dont' save meta boxes for revisions or autosaves
			if (defined ( 'DOING_AUTOSAVE' ) || is_int ( wp_is_post_revision ( $post ) ) || is_int ( wp_is_post_autosave ( $post ) )) {
				return;
			}
			// Check the nonce
			if (empty ( $_POST ['dt_meta_box_nonce'] ) || ! wp_verify_nonce ( $_POST ['dt_meta_box_nonce'], 'dt_meta_box_nonce' )) {
				return;
			}
			
			// Check the post being saved == the $post_id to prevent triggering this call for other save_post events
			if (empty ( $_POST ['post_ID'] ) || $_POST ['post_ID'] != $post_id) {
				return;
			}
			
			// Check user has permission to edit
			if (! current_user_can ( 'edit_post', $post_id )) {
				return;
			}
			if(isset( $_POST['dt_meta'] )){
				$dt_meta = $_POST['dt_meta'];
				if(get_post_format() == 'video' ){
					$_dt_video_embed = $dt_meta['_dt_video_embed'];
					if(dt_is_video_support($_dt_video_embed) && ($_dt_video_embed != dt_get_post_meta('video_embed_hidden'))){
						$videoThumbUrl = dt_get_video_thumb_url($_dt_video_embed);
						if (!empty($videoThumbUrl)) {
							 // add the function above to catch the attachments creation
							add_action('add_attachment',array(&$this,'add_video_featured_image'));
							// load the attachment from the URL
							media_sideload_image($videoThumbUrl, $post_id, $post_id);
							// we have the Image now, and the function above will have fired too setting the thumbnail ID in the process, so lets remove the hook so we don't cause any more trouble
							remove_action('add_attachment',array(&$this,'add_video_featured_image'));
						}
					}
				}
				// Process
				foreach( (array)$_POST['dt_meta'] as $key=>$val ){
					$val = wp_unslash($val);
					if(is_array($val)){
						$option_value = array_filter( array_map( 'sanitize_text_field', (array) $val ) );
						update_post_meta( $post_id, $key, $option_value );
					}else{
						update_post_meta( $post_id, $key, wp_kses_post($val) );
					}
				}
			}
			do_action('dt_metabox_save',$post_id);
		}
		
		public function enqueue_scripts(){
			wp_enqueue_style('dt-meta-box',DTINC_ASSETS_URL.'/css/meta-box.css',null,DTINC_VERSION);
			wp_enqueue_script('dt-meta-box',DTINC_ASSETS_URL.'/js/meta-box.js',array('jquery','jquery-ui-sortable'),DTINC_VERSION,true);
		}
		
	}
	new DTMetaboxes ();

endif;