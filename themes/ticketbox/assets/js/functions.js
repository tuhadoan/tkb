/**
 * Theme functions file
 *
 * Contains handlers for navigation, accessibility, header sizing
 * footer widgets and Featured Content slider
 *
 */
( function( $ ) {
	var body = $( 'body' ),
		$win = $( window );

	var dawn = {
			ini: function(){
				dawn.sidebar_offcanvas();
				dawn.menu();
				dawn.scrollToTOp();
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
