<?php

class WPBakeryShortCode_VC_flickr extends WPBakeryShortCode {
	protected function contentInline( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'el_class' => '',
			'title' => '',
			'flickr_id' => '95572727@N00',
			'count' => '6',
			'type' => 'user',
			'display' => 'latest',
		), $atts ) );

		$css = isset( $atts['css'] ) ? $atts['css'] : '';
		$el_class = isset( $atts['el_class'] ) ? $atts['el_class'] : '';

		$class_to_filter = 'wpb_flickr_widget wpb_content_element';
		$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

		$output = '
			<div class="' . $css_class . '">
				<div class="wpb_wrapper">
					' . wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_flickr_heading' ) ) . '
					<div class="vc_flickr-inline-placeholder" data-link="http://www.flickr.com/badge_code_v2.gne?count=' . $count . '&amp;display=' . $display . '&amp;size=s&amp;layout=x&amp;source=' . $type . '&amp;' . $type . '=' . $flickr_id . '"></div>
					<p class="flickr_stream_wrap"><a class="wpb_follow_btn wpb_flickr_stream" href="http://www.flickr.com/photos/' . $flickr_id . '">' . __( 'View stream on flickr', 'js_composer' ) . '</a></p>
				</div>
			</div>
		';

		return $output;
	}
}
