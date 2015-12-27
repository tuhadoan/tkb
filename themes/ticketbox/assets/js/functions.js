/**
 * Theme functions file
 *
 * Contains handlers for navigation, accessibility, header sizing
 * footer widgets and Featured Content slider
 *
 */
;( function( $ ) {
	'use strict';
	var body = $( 'body' ),
		$win = $( window );
	
	var dawn = {
			ini: function(){
				dawn.menu_toggle();
				dawn.menu();
				dawn.scrollToTOp();
			},
			
			menu_toggle: function(){
				//Off Canvas menu
				$('.menu-toggle').on('click',function(e){
					e.stopPropagation();
					e.preventDefault();
					if($('body').hasClass('open-offcanvas')){
						$('body').removeClass('open-offcanvas').addClass('close-offcanvas');
						$('.menu-toggle').removeClass('x');
					}else{
						$('body').removeClass('close-offcanvas').addClass('open-offcanvas');
						
					}
				});
				$('body').on('mousedown', $.proxy( function(e){
					var element = $(e.target);
					if($('.offcanvas').length && $('body').hasClass('open-offcanvas')){
						if(!element.is('.offcanvas') && element.parents('.offcanvas').length === 0 && !element.is('.navbar-toggle') && element.parents('.navbar-toggle').length === 0 )
						{
							$('body').removeClass('open-offcanvas');
							$('.menu-toggle').removeClass('x');
						}
					}
				}, this) );
				
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
						if($("#wpadminbar").length > 0 && $win.width() > 600){
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
							$('#header').removeClass('sticky-navigation-holder scrolled bgc');
					    }
					}
				});
			},
			
			scrollToTOp: function(){
				var linkToTop = $('#scroll-to-top');
				
				linkToTop.on('click', function(){
					$('html, body').animate({
						scrollTop: 0
					}, 400);
					return false;
				});
			},
	};
	
	$(document).ready(function(){
		dawn.ini();
	});
	//
} )( jQuery );
