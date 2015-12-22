/**
 * Theme functions file
 *
 * Contains handlers for navigation, accessibility, header sizing
 * footer widgets and Featured Content slider
 *
 */
( function( $ ) {
	var body    = $( 'body' ),
		$win = $( window ),
		nav, button, menu;

	nav = $( '#primary-navigation' );
	button = nav.find( '.menu-toggle' );
	menu = nav.find( '.nav-menu' );

	// Enable menu toggle for small screens.
	( function() {
		if ( ! nav || ! button ) {
			return;
		}

		// Hide button if menu is missing or empty.
		if ( ! menu || ! menu.children().length ) {
			button.hide();
			return;
		}

		button.on( 'click', function() {
			nav.toggleClass( 'toggled-on' );
			if ( nav.hasClass( 'toggled-on' ) ) {
				$( this ).attr( 'aria-expanded', 'true' );
				menu.attr( 'aria-expanded', 'true' );
			} else {
				$( this ).attr( 'aria-expanded', 'false' );
				menu.attr( 'aria-expanded', 'false' );
			}
		} );
	} )();

	/*
	 * Makes "skip to content" link work correctly in IE9 and Chrome for better
	 * accessibility.
	 *
	 * @link http://www.nczonline.net/blog/2013/01/15/fixing-skip-to-content-links/
	 */
	$win.on( 'hashchange', function() {
		var hash = location.hash.substring( 1 ), element;

		if ( ! hash ) {
			return;
		}

		element = document.getElementById( hash );

		if ( element ) {
			if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) {
				element.tabIndex = -1;
			}

			element.focus();

			// Repositions the window on jump-to-anchor to account for header height.
			window.scrollBy( 0, -80 );
		}
	} );

	$( function() {
		// Search toggle.
		$( '.search-toggle' ).on( 'click', function( event ) {
			var that    = $( this ),
				wrapper = $( '#search-container' ),
				container = that.find( 'a' );

			that.toggleClass( 'active' );
			wrapper.toggleClass( 'hide' );

			if ( that.hasClass( 'active' ) ) {
				container.attr( 'aria-expanded', 'true' );
			} else {
				container.attr( 'aria-expanded', 'false' );
			}

			if ( that.is( '.active' ) || $( '.search-toggle .screen-reader-text' )[0] === event.target ) {
				wrapper.find( '.search-field' ).focus();
			}
		} );

		/*
		 * Fixed header for large screen.
		 * If the header becomes more than 48px tall, unfix the header.
		 *
		 * The callback on the scroll event is only added if there is a header
		 * image and we are not on mobile.
		 */
		if ( $win.width() > 781 ) {
			var mastheadHeight = $( '#header' ).height(),
				toolbarOffset, mastheadOffset;

			if ( mastheadHeight > 80 ) {
				body.removeClass( 'masthead-fixed' );
			}

			if ( body.is( '.header-image' ) ) {
				toolbarOffset  = body.is( '.admin-bar' ) ? $( '#wpadminbar' ).height() : 0;
				mastheadOffset = $( '#header' ).offset().top - toolbarOffset;

				$win.on( 'scroll.dawn', function() {
					if ( $win.scrollTop() > mastheadOffset && mastheadHeight < 49 ) {
						body.addClass( 'masthead-fixed' );
					} else {
						body.removeClass( 'masthead-fixed' );
					}
				} );
			}
		}

		// Focus styles for menus.
		$( '.primary-navigation, .secondary-navigation' ).find( 'a' ).on( 'focus blur', function() {
			$( this ).parents().toggleClass( 'focus' );
		} );
	} );

	/**
	 * @summary Add or remove ARIA attributes.
	 * Uses jQuery's width() function to determine the size of the window and add
	 * the default ARIA attributes for the menu toggle if it's visible.
	 * @since Dawn 1.4
	 */
	function onResizeARIA() {
		if ( 781 > $win.width() ) {
			button.attr( 'aria-expanded', 'false' );
			menu.attr( 'aria-expanded', 'false' );
			button.attr( 'aria-controls', 'primary-menu' );
		} else {
			button.removeAttr( 'aria-expanded' );
			menu.removeAttr( 'aria-expanded' );
			button.removeAttr( 'aria-controls' );
		}
	}

	$win
		.on( 'load', onResizeARIA )
		.on( 'resize', function() {
			onResizeARIA();
	} );

	$win.load( function() {
		// Arrange footer widgets vertically.
		if ( $.isFunction( $.fn.masonry ) ) {
			$( '#footer-sidebar' ).masonry( {
				itemSelector: '.widget',
				columnWidth: function( containerWidth ) {
					return containerWidth / 4;
				},
				gutterWidth: 0,
				isResizable: true,
				isRTL: $( 'body' ).is( '.rtl' )
			} );
		}
	} );
	
	var dawn = {
			ini: function(){
				dawn.sidebar_offcanvas();
				dawn.menu();
			},
			
			//Set up "Sidebar Offcanvas"
			sidebar_offcanvas: function(){
				if($(window).width() > ($(".container").width() + $('.sidebar-offcanvas').width())){
					$('.sidebar-offcanvas').show().removeClass('animated slideOutLeft');
					$('body').addClass('body-offcanvas');
					$('.sidebar-offcanvas-toggle').hide();
					$('.toggle-wrap .offcanvas-toggle').hide();
					/* OffCanvas */
					$(".body-offcanvas #header").css("width", $(window).width() - 320);
				}else{
					$('body').removeClass('body-offcanvas');
					$("#header").css("width", "100%");
					$('.sidebar-offcanvas').removeClass('animated slideInLeft').removeClass('animated slideOutLeft').hide();
					$('.sidebar-offcanvas-toggle').show();
					$('.toggle-wrap .offcanvas-toggle').show();
				}
				
				//Open Sidebar Offcanvas
				$('.sidebar-offcanvas-toggle').click(function(){
					if($('.sidebar-offcanvas').css('display') == 'none'){
						$('.sidebar-offcanvas').show().removeClass('animated slideOutLeft').addClass('animated slideInLeft');
					}
				});
				
				//Close Sidebar Offcanvas
				$('.toggle-wrap .offcanvas-toggle').click(function(){
					if($('.sidebar-offcanvas').css('display') == 'block'){
						$('.sidebar-offcanvas').removeClass('animated slideInLeft').addClass('animated slideOutLeft').delay(1000).hide(0);
					}
				});
				
				//Open Search Box
				$('.sidebar-offcanvas-header .search-toggle').click(function(){
					$('.sidebar-offcanvas-header .user-panel').hide();
					$('.sidebar-offcanvas-header .toggle-wrap').hide();
					$('.sidebar-offcanvas-header .search-box').fadeIn();
				});

				//Close Search Box
				$(document).click(function (e) {		
					if (!$(e.target).hasClass("search-toggle") 
			        	&& $(e.target).parents(".sidebar-offcanvas-header").length === 0) 
				    {
				        $(".search-box").hide();
				        $('.sidebar-offcanvas-header .user-panel').fadeIn();
						$('.sidebar-offcanvas-header .toggle-wrap').fadeIn();
				    }
				});
			},
			
			menu: function(){
				$('#primary-navigation .nav-menu li').hoverIntent(function(){
					$(this).addClass('item-hover');			
					$(this).find(' > ul').show().addClass('animated x2 slideInUp');
				},function(){
					$(this).find('ul').removeClass('animated x2 slideInUp').fadeOut();
					$(this).removeClass('item-hover');
				});
				
				// Sticky menu
				var iScrollPos = 0;

				$win.scroll(function () {
					if( $('#header').hasClass('is_sticky_menu') ){
						var iCurScrollPos = $(this).scrollTop();
					    var $wpadminbar = 0;
						if($("#wpadminbar").length > 0){
							var $wpadminbar = $("#wpadminbar").height();
						}
						
					    if (iCurScrollPos > iScrollPos && iCurScrollPos > 80) {
					        //Scrolling Down
					        $('#header').css('top',-80);
							$('#header').removeClass('sticky-navigation-holder scrolled bgc');
					    } else {
					       //Scrolling Up
					       	$('#header').css({'top': $wpadminbar });
							$('#header').addClass('sticky-navigation-holder scrolled bgc');
					    }
					    
					    iScrollPos = iCurScrollPos;
					    
					    if(iCurScrollPos == 0){
					    	//$('#header').css('top',0);
							$('#header').removeClass('sticky-navigation-holder scrolled bgc');
					    }
					}
				});
			}
	};
	
	$(document).ready(function(){
		dawn.ini();
	});
	//
} )( jQuery );
