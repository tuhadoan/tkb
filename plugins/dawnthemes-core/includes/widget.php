<?php
class DT_Widget extends WP_Widget {
	public $widget_cssclass;
	public $widget_description;
	public $widget_id;
	public $widget_name;
	public $settings;
	public $cached = true;
	/**
	 * Constructor
	 */
	public function __construct() {
	
		$widget_ops = array(
				'classname'   => $this->widget_cssclass,
				'description' => $this->widget_description
		);
		
		parent::__construct(
			$this->widget_id,
			$this->widget_name,
			$widget_ops
		);
		
		if($this->cached){
			add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
			add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
			add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
		}
	}
	
	/**
	 * get_cached_widget function.
	 */
	function get_cached_widget( $args ) {
	
		$cache = wp_cache_get( apply_filters( 'dt_cached_widget_id', $this->widget_id ), 'widget' );
	
		if ( ! is_array( $cache ) ) {
			$cache = array();
		}
	
		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return true;
		}
	
		return false;
	}
	
	/**
	 * Cache the widget
	 * @param string $content
	 */
	public function cache_widget( $args, $content ) {
		$cache[ $args['widget_id'] ] = $content;
	
		wp_cache_set( apply_filters( 'dt_cached_widget_id', $this->widget_id ), $cache, 'widget' );
	}
	
	/**
	 * Flush the cache
	 *
	 * @return void
	 */
	public function flush_widget_cache() {
		wp_cache_delete( apply_filters( 'dt_cached_widget_id', $this->widget_id ), 'widget' );
	}
	
	/**
	 * update function.
	 *
	 * @see WP_Widget->update
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
	
		$instance = $old_instance;
	
		if ( ! $this->settings ) {
			return $instance;
		}
	
		foreach ( $this->settings as $key => $setting ) {
			
			if(isset($setting['multiple'])):
				$instance[ $key ] = implode ( ',', $new_instance [$key] );
			else:
				if ( isset( $new_instance[ $key ] ) ) {
					$instance[ $key ] = sanitize_text_field( $new_instance[ $key ] );
				} elseif ( 'checkbox' === $setting['type'] ) {
					$instance[ $key ] = 0;
				}
			endif;
		}
		if($this->cached){
			$this->flush_widget_cache();
		}
	
		return $instance;
	}
	
	/**
	 * form function.
	 *
	 * @see WP_Widget->form
	 * @param array $instance
	 */
	public function form( $instance ) {
	
		if ( ! $this->settings ) {
			return;
		}
		foreach ( $this->settings as $key => $setting ) {
			$value   = isset( $instance[ $key ] ) ? $instance[ $key ] : $setting['std'];
			switch ( $setting['type'] ) {
			case "text" :
			?>
				<p>
					<label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo $setting['label']; ?></label>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>" />
				</p>
				<?php
			break;

			case "number" :
				?>
				<p>
					<label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo $setting['label']; ?></label>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" type="number" step="<?php echo esc_attr( $setting['step'] ); ?>" min="<?php echo esc_attr( $setting['min'] ); ?>" max="<?php echo esc_attr( $setting['max'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
				</p>
				<?php
			break;
			case "select" :
				if(isset($setting['multiple'])):
				$value = explode(',', $value);
				endif;
				?>
				<p>
					<label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo $setting['label']; ?></label>
					<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" <?php if(isset($setting['multiple'])):?> multiple="multiple"<?php endif;?> name="<?php echo $this->get_field_name( $key ); ?><?php if(isset($setting['multiple'])):?>[]<?php endif;?>">
						<?php foreach ( $setting['options'] as $option_key => $option_value ) : ?>
							<option value="<?php echo esc_attr( $option_key ); ?>" <?php if(isset($setting['multiple'])): selected( in_array ( $option_key, $value ) , true ); else: selected( $option_key, $value ); endif; ?>><?php echo esc_html( $option_value ); ?></option>
						<?php endforeach; ?>
					</select>
				</p>
				<?php
			break;

			case "checkbox" :
				?>
				<p>
					<input id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="checkbox" value="1" <?php checked( $value, 1 ); ?> />
					<label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo $setting['label']; ?></label>
				</p>
				<?php
			break;
			}
		}
	}
}

class DT_Social_Widget extends DT_Widget {
	public function __construct(){
		$this->widget_cssclass    = 'social-widget';
		$this->widget_description = esc_html__( "Display Social Icon.", 'dawnthemes' );
		$this->widget_id          = 'DT_Social_Widget';
		$this->widget_name        = esc_html__( 'Social', 'dawnthemes' );
		
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'	=>'',
				'label' => esc_html__( 'Title', 'dawnthemes' )
			),
			'social' => array(
					'type'  => 'select',
					'std'   => '',
					'multiple'=>true,
					'label'=>esc_html__('Social','dawnthemes'),
					'desc' => esc_html__( 'Select socials', 'dawnthemes' ),
					'options' => array(
						'facebook'=>'Facebook',
						'twitter'=>'Twitter',
						'google-plus'=>'Google Plus',
						'pinterest'=>'Pinterest',
						'linkedin'=>'Linkedin',
						'rss'=>'Rss',
						'instagram'=>'Instagram',
						'github'=>'Github',
						'behance'=>'Behance',
						'stack-exchange'=>'Stack Exchange',
						'tumblr'=>'Tumblr',
						'soundcloud'=>'SoundCloud',
						'dribbble'=>'Dribbble'
					),
			),
			'style' => array(
				'type'  => 'select',
				'std'   => '',
				'label' => esc_html__( 'Style', 'dawnthemes' ),
				'options' => array(
					'square' =>  esc_html__('Square', 'dawnthemes' ),
					'round' =>  esc_html__('Round', 'dawnthemes' ),
					'outlined' =>  esc_html__('Outlined', 'dawnthemes' ),
				)
			),
		);
		parent::__construct();
	}
	
	public function widget($args, $instance){
		ob_start();
		extract( $args );
		$title       = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$social = isset($instance['social']) ? explode(',',$instance['social']) : array();
		$style = isset($instance['style']) ? $instance['style'] : 'square';
		if(!empty($social)){
			echo $before_widget;
			if ( $title )
				echo $before_title . $title . $after_title;
			echo '<div class="social-widget-wrap social-widget-'.$style.'">';
			$hover = false;
			$soild_bg = true;
			$outlined = false;
			if($style == 'outlined'){
				$hover = true;
				$soild_bg = false;
				$outlined = true;
			}
			dt_social($social,$hover,$soild_bg,$outlined);
			echo '</div>';
			echo $after_widget;
			$content = ob_get_clean();
			echo $content;
		}
	}
	
}

class DT_Tweets extends WP_Widget {
	public function __construct() {
		parent::__construct (
			'dt_tweets', 		// Base ID
			'Recent Tweets', 		// Name
			array ('classname'=>'tweets-widget','description' => __ ( 'Display recent tweets', 'dawnthemes' ) )
		);
	}

	public function widget($args, $instance) {
		extract($args);
		if(!empty($instance['title'])){ $title = apply_filters( 'widget_title', $instance['title'] ); }
		echo $before_widget;
		if ( ! empty( $title ) ){ echo $before_title . $title . $after_title; }

		//check settings and die if not set
		if(empty($instance['consumerkey']) || empty($instance['consumersecret']) || empty($instance['accesstoken']) || empty($instance['accesstokensecret']) || empty($instance['cachetime']) || empty($instance['username'])){
			echo '<strong>'.esc_html__('Please fill all widget settings!' , 'dawnthemes').'</strong>' . $after_widget;
			return;
		}

		$dt_widget_recent_tweets_cache_time = get_option('dt_widget_recent_tweets_cache_time');
		$diff = time() - $dt_widget_recent_tweets_cache_time;

		$crt = (int) $instance['cachetime'] * 3600;

		if($diff >= $crt || empty($dt_widget_recent_tweets_cache_time)){
			
			if(!require_once(DTINC_DIR . '/lib/twitteroauth.php')){
				echo '<strong>'.esc_html__('Couldn\'t find twitteroauth.php!','dawnthemes').'</strong>' . $after_widget;
				return;
			}
				
			function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
				$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
				return $connection;
			}
				
			$connection = getConnectionWithAccessToken($instance['consumerkey'], $instance['consumersecret'], $instance['accesstoken'], $instance['accesstokensecret']);
			$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$instance['username']."&count=10&exclude_replies=".$instance['excludereplies']);

			if(!empty($tweets->errors)){
				if($tweets->errors[0]->message == 'Invalid or expired token'){
					echo '<strong>'.$tweets->errors[0]->message.'!</strong><br/>'.esc_html__('You\'ll need to regenerate it <a href="https://dev.twitter.com/apps" target="_blank">here</a>!', 'dawnthemes' ) . $after_widget;
				}else{
					echo '<strong>'.$tweets->errors[0]->message.'</strong>' . $after_widget;
				}
				return;
			}
				
			$tweets_array = array();
			for($i = 0;$i <= count($tweets); $i++){
				if(!empty($tweets[$i])){
					$tweets_array[$i]['created_at'] = $tweets[$i]->created_at;

					//clean tweet text
					$tweets_array[$i]['text'] = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $tweets[$i]->text);

					if(!empty($tweets[$i]->id_str)){
						$tweets_array[$i]['status_id'] = $tweets[$i]->id_str;
					}
				}
			}
			update_option('dt_widget_recent_tweets',serialize($tweets_array));
			update_option('dt_widget_recent_tweets_cache_time',time());
		}

		$dt_widget_recent_tweets = maybe_unserialize(get_option('dt_widget_recent_tweets'));
		if(!empty($dt_widget_recent_tweets)){
			echo '<div class="recent-tweets"><ul>';
			$i = '1';
			foreach($dt_widget_recent_tweets as $tweet){
				if(!empty($tweet['text'])){
					if(empty($tweet['status_id'])){ $tweet['status_id'] = ''; }
					if(empty($tweet['created_at'])){ $tweet['created_at'] = ''; }
						
					echo '<li><span>'.$this->_convert_links($tweet['text']).'</span><a class="twitter_time" target="_blank" href="http://twitter.com/'.$instance['username'].'/statuses/'.$tweet['status_id'].'">'.ucfirst($this->_relative_time($tweet['created_at'])).'</a></li>';
					if($i == $instance['tweetstoshow']){ break; }
					$i++;
				}
			}
				
			echo '</ul></div>';
		}

		echo $after_widget;
	}

	protected function _convert_links($status, $targetBlank = true, $linkMaxLen=50){
		// the target
		$target=$targetBlank ? " target=\"_blank\" " : "";

		// convert link to url
		$status = preg_replace("/((http:\/\/|https:\/\/)[^ )]+)/i", "<a href=\"$1\" title=\"$1\" $target >$1</a>", $status);

		// convert @ to follow
		$status = preg_replace("/(@([_a-z0-9\-]+))/i","<a href=\"http://twitter.com/$2\" title=\"Follow $2\" $target >$1</a>",$status);

		// convert # to search
		$status = preg_replace("/(#([_a-z0-9\-]+))/i","<a href=\"https://twitter.com/search?q=$2\" title=\"Search $1\" $target >$1</a>",$status);

		// return the status
		return $status;
	}

	protected function _relative_time($a=''){
		//get current timestampt
		$b = strtotime("now");
		//get timestamp when tweet created
		$c = strtotime($a);
		//get difference
		$d = $b - $c;
		//calculate different time values
		$minute = 60;
		$hour = $minute * 60;
		$day = $hour * 24;
		$week = $day * 7;

		if(is_numeric($d) && $d > 0) {
			//if less then 3 seconds
			if($d < 3) return "right now";
			//if less then minute
			if($d < $minute) return sprintf(esc_html__("%s seconds ago",'dawnthemes'),floor($d));
			//if less then 2 minutes
			if($d < $minute * 2) return esc_html__("about 1 minute ago",'dawnthemes');
			//if less then hour
			if($d < $hour) return sprintf(esc_html__('%s minutes ago','dawnthemes'), floor($d / $minute));
			//if less then 2 hours
			if($d < $hour * 2) return esc_html__("about 1 hour ago",'dawnthemes');
			//if less then day
			if($d < $day) return sprintf(esc_html__("%s hours ago", 'dawnthemes'),floor($d / $hour));
			//if more then day, but less then 2 days
			if($d > $day && $d < $day * 2) return esc_html__("yesterday",'dawnthemes');
			//if less then year
			if($d < $day * 365) return sprintf(esc_html__('%s days ago','dawnthemes'),floor($d / $day));
			//else return more than a year
			return esc_html__("over a year ago",'dawnthemes');
		}
	}

	public function form($instance) {
		$defaults = array (
			'title' => '',
			'consumerkey' => '',
			'consumersecret' => '',
			'accesstoken' => '',
			'accesstokensecret' => '',
			'cachetime' => '',
			'username' => '',
			'tweetstoshow' => ''
		);
		$instance = wp_parse_args ( ( array ) $instance, $defaults );

		echo '
		<p>
			<label>' . __ ( 'Title' , 'dawnthemes' ) . ':</label>
			<input type="text" name="' . $this->get_field_name ( 'title' ) . '" id="' . $this->get_field_id ( 'title' ) . '" value="' . esc_attr ( $instance ['title'] ) . '" class="widefat" />
		</p>
		<p>
			<label>' . __ ( 'Consumer Key' , 'dawnthemes' ) . ':</label>
			<input type="text" name="' . $this->get_field_name ( 'consumerkey' ) . '" id="' . $this->get_field_id ( 'consumerkey' ) . '" value="' . esc_attr ( $instance ['consumerkey'] ) . '" class="widefat" />
		</p>
		<p>
			<label>' . __ ( 'Consumer Secret' , 'dawnthemes' ) . ':</label>
			<input type="text" name="' . $this->get_field_name ( 'consumersecret' ) . '" id="' . $this->get_field_id ( 'consumersecret' ) . '" value="' . esc_attr ( $instance ['consumersecret'] ) . '" class="widefat" />
		</p>
		<p>
			<label>' . __ ( 'Access Token' , 'dawnthemes' ) . ':</label>
			<input type="text" name="' . $this->get_field_name ( 'accesstoken' ) . '" id="' . $this->get_field_id ( 'accesstoken' ) . '" value="' . esc_attr ( $instance ['accesstoken'] ) . '" class="widefat" />
		</p>
		<p>
			<label>' . __ ( 'Access Token Secret' , 'dawnthemes' ) . ':</label>
			<input type="text" name="' . $this->get_field_name ( 'accesstokensecret' ) . '" id="' . $this->get_field_id ( 'accesstokensecret' ) . '" value="' . esc_attr ( $instance ['accesstokensecret'] ) . '" class="widefat" />
		</p>
		<p>
			<label>' . __ ( 'Cache Tweets in every' , 'dawnthemes' ) . ':</label>
			<input type="text" name="' . $this->get_field_name ( 'cachetime' ) . '" id="' . $this->get_field_id ( 'cachetime' ) . '" value="' . esc_attr ( $instance ['cachetime'] ) . '" class="small-text" />'.esc_html__('hours','dawnthemes').'
		</p>
		<p>
			<label>' . __ ( 'Twitter Username' , 'dawnthemes' ) . ':</label>
			<input type="text" name="' . $this->get_field_name ( 'username' ) . '" id="' . $this->get_field_id ( 'username' ) . '" value="' . esc_attr ( $instance ['username'] ) . '" class="widefat" />
		</p>
		<p>
			<label>' . __ ( 'Tweets to display' , 'dawnthemes' ) . ':</label>
			<select type="text" name="' . $this->get_field_name ( 'tweetstoshow' ) . '" id="' . $this->get_field_id ( 'tweetstoshow' ) . '">';
		$i = 1;
		for(i; $i <= 10; $i ++) {
			echo '<option value="' . $i . '"';
			if ($instance ['tweetstoshow'] == $i) {
				echo ' selected="selected"';
			}
			echo '>' . $i . '</option>';
		}
		echo '
			</select>
		</p>
		<p>
			<label>' . __ ( 'Exclude replies', 'dawnthemes' ) . ':</label>
			<input type="checkbox" name="' . $this->get_field_name ( 'excludereplies' ) . '" id="' . $this->get_field_id ( 'excludereplies' ) . '" value="true"';
		if (! empty ( $instance ['excludereplies'] ) && esc_attr ( $instance ['excludereplies'] ) == 'true') {
			echo ' checked="checked"';
		}
		echo '/></p>';
	}

	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['consumerkey'] = strip_tags( $new_instance['consumerkey'] );
		$instance['consumersecret'] = strip_tags( $new_instance['consumersecret'] );
		$instance['accesstoken'] = strip_tags( $new_instance['accesstoken'] );
		$instance['accesstokensecret'] = strip_tags( $new_instance['accesstokensecret'] );
		$instance['cachetime'] = strip_tags( $new_instance['cachetime'] );
		$instance['username'] = strip_tags( $new_instance['username'] );
		$instance['tweetstoshow'] = strip_tags( $new_instance['tweetstoshow'] );
		$instance['excludereplies'] = strip_tags( $new_instance['excludereplies'] );

		if($old_instance['username'] != $new_instance['username']){
			delete_option('dt_widget_recent_tweets_cache_time');
		}

		return $instance;
	}
}



class DT_Post_Thumbnail_Widget extends DT_Widget {

	public function __construct() {
		$this->widget_cssclass    = 'widget-post-thumbnail';
		$this->widget_description = esc_html__( "Widget post with thumbnail.", 'dawnthemes' );
		$this->widget_id          = 'dt_widget_post_thumbnail';
		$this->widget_name        = esc_html__( 'Post Thumbnail', 'dawnthemes' );
		$this->cached = false;
		$categories = get_categories( array(
				'orderby' => 'NAME',
				'order' => 'ASC'
		));
		$categories_options = array();
		foreach ((array)$categories as $category) {
			$categories_options[$category->term_id] = $category->name;
		}
		$this->settings           = array(
				'title'  => array(
					'type'  => 'text',
					'std'	=>'',
					'label' => esc_html__( 'Title', 'dawnthemes' )
				),
				'posts_per_page' => array(
					'type'  => 'number',
					'step'  => 1,
					'min'   => 1,
					'max'   => '',
					'std'   => 5,
					'label' => esc_html__( 'Number of posts to show', 'dawnthemes' )
				),
				'orderby' => array(
					'type'  => 'select',
					'std'   => 'date',
					'label' => esc_html__( 'Order by', 'dawnthemes' ),
					'options' => array(
							'latest'   => esc_html__( 'Latest', 'dawnthemes' ),
							'comment'  => esc_html__( 'Most Commented', 'dawnthemes' ),
					)
				),
				'categories' => array(
						'type'  => 'select',
						'std'   => '',
						'multiple'=>true,
						'label'=>esc_html__('Categories','dawnthemes'),
						'desc' => esc_html__( 'Select a category or leave blank for all', 'dawnthemes' ),
						'options' => $categories_options,
				),
				'hide_date' => array(
						'type'  => 'checkbox',
						'std'   => 0,
						'label' => esc_html__( 'Hide date in post meta info', 'dawnthemes' )
				),
				'hide_comment' => array(
						'type'  => 'checkbox',
						'std'   => 0,
						'label' => esc_html__( 'Hide comment in post meta info', 'dawnthemes' )
				),
		);
		parent::__construct();
	}
	
	public function widget($args, $instance){
		ob_start();
		extract( $args );
		$title       = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$posts_per_page      = absint( $instance['posts_per_page'] );
		$orderby     = sanitize_title( $instance['orderby'] );
		$hide_date = isset($instance['hide_date']) && $instance['hide_date'] === '1' ? true : false;
		$hide_comment = isset($instance['hide_comment']) && $instance['hide_comment'] === '1' ? true : false;
		$categories  = $instance['categories'];
		$query_args  = array(
				'posts_per_page' => $posts_per_page,
				'post_status' 	 => 'publish',
				'ignore_sticky_posts' => 1,
				'orderby' => 'date',
				"meta_key" => "_thumbnail_id",
				'order' => 'DESC',
		);
		if($orderby == 'comment'){
			$query_args['orderby'] = 'comment_count';
		}
		if(!empty($categories)){
			$query_args['cat'] = $categories;
		}
		$r = new WP_Query($query_args);
		if($r->have_posts()):
			echo $before_widget;
			if ( $title )
				echo $before_title . $title . $after_title;
				echo '<ul class="posts-thumbnail-list">';
				while ($r->have_posts()): $r->the_post();global $post;
					echo '<li>';
					echo '<div class="posts-thumbnail-image">';
					echo '<a href="'.esc_url(get_the_permalink()).'">'.get_the_post_thumbnail(null,'dt-thumbnail-square', array('title' => strip_tags(get_the_title()))).'</a>';
					echo '</div>';
					echo '<div class="posts-thumbnail-content">';
						echo '<h4><a href="'.esc_url(get_the_permalink()).'" title="'.esc_attr(get_the_title()).'">'.get_the_title().'</a></h4>';
						echo '<div class="posts-thumbnail-meta">';
						if(!$hide_date)
							echo '<time datetime="'.get_the_date('c').'">'.get_the_date().'</time>';
						
						if(!$hide_date && !$hide_comment)
							echo ', ';
						
						if(!$hide_comment){
							$output = '';
							$number = get_comments_number($post->ID);
							if ( $number > 1 ) {
								$output = str_replace( '%', number_format_i18n( $number ), ( false === false ) ? esc_html__( '% Comments' ,'dawnthemes') : false );
							} elseif ( $number == 0 ) {
								$output = ( false === false ) ? esc_html__( '0 Comments', 'dawnthemes') : false;
							} else { // must be one
								$output = ( false === false ) ? esc_html__( '1 Comment', 'dawnthemes' ) : false;
							}
							echo '<span class="comment-count"><a href="'.esc_url(get_comments_link()).'">'.$output.'</a></span>';	
						}
						echo '</div>';
					echo '</div>';
					echo '</li>';
				endwhile;
				echo  '</ul>';
			echo $after_widget;
		endif;
		$content = ob_get_clean();
		wp_reset_query();
		echo $content;
	}
	
}

class DT_Mailchimp_Widget extends DT_Widget {
	public function __construct(){
		$this->widget_cssclass    = 'widget-mailchimp';
		$this->widget_description = esc_html__( "Widget Mailchimp Subscribe.", 'dawnthemes' );
		$this->widget_id          = 'dt_widget_mailchimp';
		$this->widget_name        = esc_html__( 'Mailchimp Subscribe', 'dawnthemes' );
		$this->cached = false;
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'	=>'',
				'label' => esc_html__( 'Title', 'dawnthemes' )
			),
		);
		parent::__construct();
	}
	
	public function widget($args, $instance){
		ob_start();
		extract( $args );
		$title       = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		ob_start();
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		dt_mailchimp_form();
		echo $after_widget;
		$content = ob_get_clean();
		echo $content;
	}
}

add_action( 'widgets_init', 'dt_register_widget');
function dt_register_widget(){
	register_widget('DT_Post_Thumbnail_Widget');
	register_widget('DT_Social_Widget');
	register_widget( 'DT_Tweets' );
	register_widget( 'DT_Mailchimp_Widget' );
}