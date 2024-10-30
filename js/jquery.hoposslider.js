/*
hoposSliderLite - jQuery Plugin - v 1.1
Author: Sergio Valle
http://codecanyon.net/user/srvalle
*/

(function($) {

    $.hoposSlider = function(selector, settings) {
		// settings
		var config = {
			slideWidth: 550, // number or '100%'
			slideHeight: 208, // number or 'auto'
			postMargin: 12,
			showPreviousNext: true,
			currentSlide: 0,
			scrollSpeed: 500,
			autoSlide: false,
			autoSlideDelay: 5000,
			showPlayPause: true,
			showPagination: true,
			alignPagination: 'left',
			swipeDrag: true,
			sliderType: 'image_news', //
			pageStyle: 'numbers', // numbers, bullets, thumbnails
			orientation: 'horizontal', // horizontal, vertical
			skin: 'basic',
			resolution: { res960:4, res768:2, res480:1 },
			onScrollEvent: function() {},
			ajaxEvent: function() {}
		};
		//parameters
		if ( settings ){$.extend(config, settings);}
		
		
		var slideWidth = config.slideWidth, slideHeight = config.slideHeight, postMargin = config.postMargin, showPreviousNext = config.showPreviousNext, 
			currentSlide = config.currentSlide, scrollSpeed = config.scrollSpeed, autoSlide = config.autoSlide, 
			autoSlideDelay = config.autoSlideDelay, showPlayPause = config.showPlayPause, showPagination = config.showPagination,
			alignPagination = config.alignPagination, swipeDrag = config.swipeDrag, sliderType = config.sliderType,
			pageStyle = config.pageStyle, orientation = config.orientation, skin = config.skin, resolution = config.resolution;
			
		var $hp_slider = $(selector + ' .hp_slider'),
			$hp_items = $(selector + " .hp_items"),
			$hp_slide = $(selector + ' .hp_slide'),
			$totalSlides = $hp_slide.length,
			$hp_page = $(selector + ' .hp_page'),
			$hp_control = $(selector + ' .hp_control'),
			$hp_paginate = $(selector + ' .hp_paginate'),
			$hp_item = $(selector + ' .hp_item'),
			$hp_title = $(selector + ' .hp_title'),
			$hp_excerpt = $(selector + ' .hp_excerpt'),
			$hp_bar = $(selector + ' .hp_bar'),
			$hp_icons = $(selector + ' .hp_icons'),
			$hp_tooltip = $(selector + ' .hp_tooltip'),
	  		$hp_share = $(selector + ' .hp_share');
			
			
			
					
		var valueGlobal = 0;
		var dragging = false;
		if (navigator.userAgent.match(/msie/i) && navigator.userAgent.match(/7/)) { var is_ie7 = true; }
		if (navigator.userAgent.match(/msie/i) && navigator.userAgent.match(/8/)) { var is_ie8 = true; }
	    if (navigator.userAgent.match(/msie/i)) { var is_ie = true; }
	  	if (navigator.userAgent.match(/firefox/i)) { var is_firefox = true; }
		
		var suppTrans = supportsTransitions();
		var touchSupport = 'ontouchstart' in window;
		//var orientation_mobile = Math.abs(window.orientation) == 90 || Math.abs(window.orientation) == -90 ? 'landscape' : 'portrait';
		
		//skin style
		var skinBoxedStyle = false;
		var skinArr = ['boxed','boxed2','aqua','green','orange','yellow','blue','pink','gray'];
		
		//inArray = Return its index (or -1 if not found)
		if ( $.inArray(skin, skinArr) != -1) {
			skinBoxedStyle = true;
		}		
					
		
		// ------------------------------------------------------------
		// set width / height
		// ------------------------------------------------------------
		
		////////////////////
		
		var imgOriginalPercent = false;
		
		if (slideWidth == '100%') { 
			slideWidth = $(selector).parent().width(); //- 8
			imgOriginalPercent = true;
		}
		
		if (typeof slideWidth == 'string' && slideWidth.indexOf("%") != -1 ) {
			var slideWidthNumber = slideWidth.substring(0, slideWidth.length - 1);
			slideWidth = ( $(selector).parent().width() * slideWidthNumber ) / 100;
			imgOriginalPercent = true;
		}
		
		
		$(selector).width(slideWidth); //hp_container
		$hp_slider.width(slideWidth);
		$hp_slide.width(slideWidth);
		
		if (slideHeight != 'auto') {
			$hp_slider.height(slideHeight);
		}

		
		///////////////////////////////
		$hp_slide.height(slideHeight);
		
		// ------------------------------------------------------------
		// load external styles (skin)
		// ------------------------------------------------------------
		
		function loadjscssfile(filename, filetype){
			if (filetype == "js") { //if filename is a external JavaScript file
				var fileref = document.createElement('script');
				fileref.setAttribute("type","text/javascript");
				fileref.setAttribute("src", filename);
			}
			else if (filetype == "css") { //if filename is an external CSS file
				var fileref = document.createElement("link");
				fileref.setAttribute("rel", "stylesheet");
				fileref.setAttribute("type", "text/css");
				fileref.setAttribute("href", filename);
			}
			
			if (typeof fileref != "undefined") {
				document.getElementsByTagName("head")[0].appendChild(fileref)
			}
		}
		
		//alert(hopos_plugin_dir_param)
		
		//receive plugin dir variable from php
		var strSkin = skin.indexOf("custom");
		if (strSkin == -1) {
			var skinName = hopos_plugin_dir_param.plugin_dir + 'css/skin/' + skin + '.css';
			loadjscssfile(skinName, "css");
		}
				
		// ------------------------------------------------------------
		// preload images
		// ------------------------------------------------------------
				
		if ($totalSlides > 0) {
			
			var slideHeightArr = Array();
			
			setTimeout( function() { config.onScrollEvent.call(this, currentSlide) }, scrollSpeed);
			
			$hp_slide.children('img').css({opacity:0});
			$hp_paginate.css({opacity:0});
			
			var countSlide = 0;
			var sld = $hp_slide.eq(countSlide);
			var intervalSlide;
						
			function preloadSlide() {
				
				//add icon preload
				$hp_slide.append('<div class="hp_preload_16"></div>');
				$hp_slide.children('.hp_preload_16').css( { 
					left: (sld.width() / 2) - 12, 
					top: ($hp_item.height() / 2),
					position: 'absolute'
				} );
				
				//$(window).height() / 2 - $popup.height() / 2

				function nextPreloadSlide() {

					sld.imagesLoaded( function( $images, $proper, $broken ) {
													
						$(this).children('.hp_preload_16').hide();
						
						$(this).children('img').css({display:'block', opacity:0}).stop().animate({ opacity:1 }, { duration: 400 });
						
						var firstHeight;
						if (sliderType == 'image_news' && slideHeight != 'auto') {
							firstHeight = this.height();
							slideHeightArr.push(this.height()); //total slide height (shelf image group)
						} else if (sliderType == 'image_news' && slideHeight == 'auto') {
							firstHeight = slideHeight;
							slideHeightArr.push(slideHeight);
						} else {
							firstHeight = $proper.height();
							slideHeightArr.push($proper.height()); //image only
						}
						
						if (countSlide == 0) {
							if (is_ie) {
								$hp_items.css({display:'block', opacity:1}).stop().animate({ opacity:1 }, { duration: 600 });
							} else {
								$hp_items.css({display:'block', opacity:0}).stop().animate({ opacity:1 }, { duration: 600 });
							}
							$hp_slider.stop().animate({ height: firstHeight }, { duration: 400 });

							/////////////////////
							imageAutoResize();
							changeSlideHeight();
							$(selector).trigger('resize');
							if (autoSlide) initAutoSlide();
						}

						countSlide++;
						sld = $hp_slide.eq(countSlide);
						if (preloadSlideTimeout) clearTimeout(preloadSlideTimeout);
						preloadSlideTimeout = setTimeout(nextPreloadSlide, 500);
					});
			
					if (countSlide > $totalSlides-1) {
						clearTimeout(preloadSlideTimeout);
						$(selector + ' .hp_preload_16').remove();
						$hp_slide.children('img').show();
						
						imageAutoResize();
						changeSlideHeight();
						$(selector).trigger('resize');
						
						if (skinBoxedStyle) {
							$hp_item.trigger('mouseenter');
							//$hp_item.trigger('click');
						}
					}
				}//nextPreloadSlide
			
				preloadSlideTimeout = setTimeout(nextPreloadSlide, 500);
			}//preloadSlide

			var preloadSlideTimeout;
			preloadSlide();
			//itemsSetup(false);
			
		} else {
			return false;	
		}
		
		
		// ------------------------------------------------------------
		// items setup
		// ------------------------------------------------------------
		
		function itemsSetup(resized, newWidth) {
			
			var opacityInitValue = (resized) ? 80 : 0;
						
			var itemHeightMajor = 0;
			var tmpH = 0;

			for (var i=0; i < $hp_item.length; i++) {
				var p0 = $hp_item.eq(i);				
				itemHeightMajor = Math.max(tmpH, p0.height());
				tmpH = itemHeightMajor;
			}			
			
			$hp_item.css({ opacity:0 });
			
			var $hp_main_image = $(selector + " .hp_main_image");
			var imgBorderLeft = parseInt($hp_main_image.css("border-left-width"));
			var imgBorderRight = parseInt($hp_main_image.css("border-right-width"));
			var imgBorders = imgBorderLeft + imgBorderRight;
			
			//console.log( selector + ' - ' + slideHeight );
			
			if (newWidth == undefined) {
				var thumbWidth = $(selector).find('.hp_thumb img').attr('width');
			} else {
				var thumbWidth = newWidth;
			}

			for (var i=0; i < $hp_item.length; i++) {

				var p = $hp_item.eq(i);
				var pThumb = $hp_item.eq(i).children('.hp_thumb');
				var pThumbImg = pThumb.children('img');
				var pTitle = $hp_item.eq(i).children('.hp_title');
				var pExcerpt = $hp_item.eq(i).children('.hp_excerpt');
				var pBar = $hp_item.eq(i).children('.hp_bar');

				
				//p.css({display:'block', height:itemHeightMajor, width:thumbWidth, opacity:opacityInitValue }).stop().delay(50*i).animate({ opacity:1 }, { duration:400 } );
				
				//define each item height
				if (slideHeight == 'auto') {
					p.css({display:'block', width:thumbWidth + imgBorders, opacity:opacityInitValue }).stop().delay(50*i).animate({ opacity:1 }, { duration:400 } );
				} else {
					//console.log( selector + ' - ' + itemHeightMajor );
					p.css({display:'block', height:itemHeightMajor, width:thumbWidth + imgBorders, opacity:opacityInitValue }).stop().delay(50*i).animate({ opacity:1 }, { duration:400 } );
				}
				
				pThumbImg.css({ left: 0, position: 'relative', width: thumbWidth });


				var pPosX = parseInt(p.position().left) + p.width() + postMargin;				
				
				if (pPosX >= $hp_slider.width()) {
					p.after('<div class="hp_clear"></div>');
				} else {
					p.css({ marginRight: postMargin });	
				}
				
				// thumb mouse over
				if (!skinBoxedStyle) {
					//$hp_item.find('.hp_bar').css({ display:'none', opacity:0 });
					p.mouseenter(function(e) {
						$(this).find('.hp_bar').css({ display:'block', opacity:0 }).stop().animate({ opacity:1 }, { duration:300 } );
						$(this).find('.hp_thumb').stop().animate({ opacity:.9 }, { duration:400 } );
					}).mouseleave(function() {
						$(this).find('.hp_bar').stop().animate({ opacity:0 }, { duration:400 } );
						$(this).find('.hp_thumb').stop().animate({ opacity:1 }, { duration:300 } );
					});
				}
				
			}//for

		}//itemsSetup
		
	

		// ------------------------------------------------------------
		// nav prev/next, play/pause, pagination
		// ------------------------------------------------------------

		if (showPreviousNext) {
			var idPrev = "prev_" + $(selector).attr('id');
			var idNext = "next_" + $(selector).attr('id');
			$(selector).append('<a href="#" class="hp_previous skinner" id="' + idPrev + '"></a><a href="#" class="hp_next skinner" id="' + idNext + '"></a>');
			
			changeStyles(currentSlide);
		}

		
		if (swipeDrag && !touchSupport) {
			$hp_slider.mouseover(function(e) {
				//grab cursor
				$(this).addClass('grab_cursor');
				
				$(this).mousedown(function() {
					$(this).removeClass('grab_cursor').addClass('grabbing_cursor');
				}).mouseup(function() {
					$(this).removeClass('grabbing_cursor').addClass('grab_cursor');
				});
			}).mouseout(function() {
				$(this).removeClass('grab_cursor');
			});
		}
		
		//play/pause
		var $play = $(selector + ' .hp_control .hp_play');
		var $pause = $(selector + ' .hp_control .hp_pause');
		$pause.hide();
		
		if (!showPlayPause) {
			$hp_control.hide();
		} else {
			if (autoSlide) {
				$pause.show(); 
				$play.hide();
			} else {
				$pause.hide();
				$play.show();
			}
			$hp_control.css("display",'none').fadeTo("slow",1);
		}
		
		//pagination
		if (!showPagination) {
			$hp_page.hide();
			
			//change style in $hp_control
			$hp_control.css({ border:'none', marginLeft:0, paddingLeft:0 });
			$pause.css({ margin:0 });
			$play.css({ margin:0 });
		} else {
		
			//create pages
			var pageAppend = '';
			for (var i=0; i < $totalSlides; i++) {
				
				//if ($totalSlides > 1) {
					if (pageStyle == 'numbers') {
						var num = i+1;
						if (currentSlide == i) {
							pageAppend += '<a href="#" class="hp_btn active skinner">' + num + '</a>';
						} else {
							pageAppend += '<a href="#" class="hp_btn skinner">' + num + '</a>';
						}
					} else if (pageStyle == 'bullets') {
						if (currentSlide == i) {
							pageAppend += '<a href="#" class="bullets_page_active skinner"></a>';
						} else {
							pageAppend += '<a href="#" class="bullets_page skinner"></a>';
						}
					} else if (pageStyle == 'thumbnails') {
						var getImgSrc = $hp_page.children('img').eq(i).attr('src');
											
						if (currentSlide == i) {
							pageAppend += '<a href="#" class="thumbnails_page_active">' + '<img src="' + getImgSrc + '" alt="">' + '</a>';
						} else {
							pageAppend += '<a href="#" class="thumbnails_page">' + '<img src="' + getImgSrc + '" alt="">' + '</a>';
						}					
					}
				//}
			}
			$hp_page.children('img').remove();
			$hp_page.fadeTo("fast",0).append(pageAppend).fadeTo("normal",1);
			
			//pagination align
			//alignPaginationConfig();
		}
		
		function alignPaginationConfig() {
			if (alignPagination == 'left') {
				$hp_paginate.css({ float: 'left' });
			} else if (alignPagination == 'right') {
				$hp_paginate.css({ float: 'right' });
			} else {
				$hp_paginate.css({ left: ($hp_slider.width() - $hp_paginate.width()) / 2 });
			}
			
			$hp_paginate.stop().animate({ opacity:1 }, { duration: 600 } );
		}
		

		// ------------------------------------------------------------
		// SWIPE
		// ------------------------------------------------------------
		
		var swipeOptions =
		{
			triggerOnTouchEnd : true,	
			swipeStatus : swipeStatus,
			allowPageScroll: (orientation == 'vertical') ? "horizontal" : "vertical",
			fallbackToMouseEvents: (swipeDrag) ? true : false,
			threshold: 20
		}

		$hp_items.swipe( swipeOptions );

		// ------------------------------------------------------------
		// move > drag the div
		// cancel > animate back
		// end > animate to the next slide
		// ------------------------------------------------------------
				
		function swipeStatus(event, phase, direction, distance) {
		
			if ( phase == "move" && (direction == "left" || direction == "right" || direction == "up" || direction == "down") ) {
				
				dragging = true;
								
				//if ($hp_tooltip.length > 0) $hp_tooltip.remove();
				//if($hp_tooltip.is(':visible')) $hp_tooltip.hide();
				
				if (orientation == 'vertical' && (direction == "left" || direction == "right")) return false;
				if (orientation == 'horizontal' && (direction == "up" || direction == "down")) return false;
				
				var duration = 0;
				
				if (orientation == 'vertical') {
					var posY = -valueGlobal;
					$hp_items.css({ top: -posY, position: 'absolute' });
				} else {
					var posX = -valueGlobal;
					$hp_items.css({ left: -posX, position: 'absolute' });
				}
				
				if (direction == "left") {
					scrollSlides((slideWidth * currentSlide) + distance, duration);
				} else if (direction == "right") {
					scrollSlides((slideWidth * currentSlide) - distance, duration);
				} else if (direction == "up") {
					scrollSlides((slideHeight * currentSlide) + distance, duration);
				} else if (direction == "down") {
					scrollSlides((slideHeight * currentSlide) - distance, duration);
				}
				
			} else if ( phase == "cancel") {
				
				setTimeout(function() {
					dragging = false;
				},50);
				
				if (orientation == 'vertical') {
					if (!suppTrans) {
						setTimeout(function() {
							$hp_items.stop().animate({ top: valueGlobal }, { duration: scrollSpeed/2.5 } );
						},10);
					}
						
					scrollSlides(slideHeight * currentSlide, scrollSpeed);
					
				} else {
					
					if (!suppTrans) {
						setTimeout(function() {
							$hp_items.stop().animate({ left: valueGlobal }, { duration: scrollSpeed/2.5 } );
						},10);
					}
						
					scrollSlides(slideWidth * currentSlide, scrollSpeed);
				}
			
			} else if ( phase == "end" ) {
				
				//important delay. Then click is enabled.
				setTimeout(function() {
					dragging = false;
				},50);
		
				pauseAutoSlide();
				
				if (!suppTrans) {
					if (orientation == 'vertical') {
						setTimeout(function() {
							$hp_items.stop().animate({ top: valueGlobal }, { duration: scrollSpeed } );
						},10);
					} else {
						setTimeout(function() {
							$hp_items.stop().animate({ left: valueGlobal }, { duration: scrollSpeed } );
						},10);
					}
				}
				
				if (orientation == 'vertical') {
					
					if (direction == "down") {
						previousSlide(dragging);
					} else if (direction == "up")	{	
						nextSlide(dragging);
					}
					
				} else {
				
					if (direction == "right") {
						previousSlide(dragging);
					} else if (direction == "left")	{	
						nextSlide(dragging);
					}
				}
			}//end
		}
		
		
		function previousSlide(dragging) {
			currentSlide = Math.max(currentSlide-1, 0);
			
			if (orientation == 'vertical') {
				scrollSlides( slideHeight * currentSlide, scrollSpeed);
				
				if (!suppTrans && !dragging) {
					$hp_items.css({ position:'absolute' });
					setTimeout(function() {
						$hp_items.stop().animate({ top: valueGlobal }, { duration: scrollSpeed } );
					},10);
				}
				
			} else {

				scrollSlides( slideWidth * currentSlide, scrollSpeed);
				
				if (!suppTrans && !dragging) {
					$hp_items.css({ position:'absolute' });
					setTimeout(function() {
						$hp_items.stop().animate({ left: valueGlobal }, { duration: scrollSpeed } );
					},10);
				}
			}
			
			setTimeout( function() { config.onScrollEvent.call(this, currentSlide) }, scrollSpeed);
			changeStyles(currentSlide);		
		}
		
		function nextSlide(dragging) {
			currentSlide = Math.min(currentSlide+1, $totalSlides-1);
			
			if (orientation == 'vertical') {
				
				scrollSlides( slideHeight * currentSlide, scrollSpeed);

				if (!suppTrans && !dragging) {
					$hp_items.css({ position:'absolute' });
					setTimeout(function() {
						$hp_items.stop().animate({ top: valueGlobal }, { duration: scrollSpeed } );
					},10);
				}

			} else {
				
				scrollSlides( slideWidth * currentSlide, scrollSpeed);

				if (!suppTrans && !dragging) {
					$hp_items.css({ position:'absolute' });
					setTimeout(function() {
						$hp_items.stop().animate({ left: valueGlobal }, { duration: scrollSpeed } );
					},10);
				}
			}
			
			setTimeout( function() { config.onScrollEvent.call(this, currentSlide) }, scrollSpeed);
			changeStyles(currentSlide);		
		}
		
		function gotoSlide(slideNum, dragging) {		
			currentSlide = slideNum;
			
			if (orientation == 'vertical') {
				scrollSlides( slideHeight * currentSlide, scrollSpeed);
				
				if (!suppTrans && !dragging) {
					$hp_items.css({ position:'absolute' });
					setTimeout(function() {
						$hp_items.stop().animate({ top: valueGlobal }, { duration: scrollSpeed } );
					},10);
				}
				
			} else {
				
				scrollSlides( slideWidth * currentSlide, scrollSpeed);
				
				if (!suppTrans && !dragging) {
					$hp_items.css({ position:'absolute' });
					setTimeout(function() {
						$hp_items.stop().animate({ left: valueGlobal }, { duration: scrollSpeed } );
					},10);
				}
			}
			
			setTimeout( function() { config.onScrollEvent.call(this, currentSlide) }, scrollSpeed);
			changeStyles(currentSlide);		
		}
		
		function changeStyles(slideNum) {
			
			if (pageStyle == 'numbers') {
				$hp_page.children("a").removeClass('active');
				$hp_page.children("a").eq(slideNum).addClass('active');
			} else if (pageStyle == 'bullets') {
				$hp_page.children("a").removeClass('bullets_page_active skinner').addClass('bullets_page skinner');
				$hp_page.children("a").eq(slideNum).addClass('bullets_page_active skinner');
			} else if (pageStyle == 'thumbnails') {
				$hp_page.children("a").removeClass('thumbnails_page_active').addClass('thumbnails_page');
				$hp_page.children("a").eq(slideNum).addClass('thumbnails_page_active');
			}
			
			if (slideNum == $totalSlides-1) {
				$(selector + ' .hp_next').css({ opacity: .5 });
			} else {
				$(selector + ' .hp_next').css({ opacity: 1 });
			}
			
			if (slideNum == 0) {
				$(selector + ' .hp_previous').css({ opacity: .5 });
			} else {
				$(selector + ' .hp_previous').css({ opacity: 1 });
			}
		}


		// Update the position of the $hp_items on drag
		function scrollSlides(distance, duration) {
			
			//if($hp_tooltip.is(':visible')) $hp_tooltip.hide();

			setTimeout(function() {
				$hp_slider.stop().animate({ height: slideHeightArr[currentSlide] }, { duration: duration });
			},duration);
			
			if (suppTrans) {

				$hp_items.css(
				{
					"-webkit-transition-duration": (duration/1000).toFixed(1) + "s", 
					"-moz-transition-duration": (duration/1000).toFixed(1) + "s",
					"-o-transition-duration": (duration/1000).toFixed(1) + "s",
					"-ms-transition-duration": (duration/1000).toFixed(1) + "s",
					"transition-duration": (duration/1000).toFixed(1) + "s"
				});
				
				var valueLocal = (distance < 0 ? "" : "-") + Math.abs(distance).toString();
				
				if (orientation == 'vertical') {
					
					$hp_items.css(
					{
						"-webkit-transform": "translate(0px,"+ valueLocal +"px)",
						"-moz-transform": "translate(0px,"+ valueLocal +"px)",
						"-o-transform": "translate(0px,"+ valueLocal +"px)",
						"-ms-transform": "translate(0px,"+ valueLocal +"px)",
						"transform": "translate(0px,"+ valueLocal +"px)"
					});
					
				} else {

					$hp_items.css(
					{
						"-webkit-transform": "translate("+ valueLocal +"px,0px)",
						"-moz-transform": "translate("+ valueLocal +"px,0px)",
						"-o-transform": "translate("+ valueLocal +"px,0px)",
						"-ms-transform": "translate("+ valueLocal +"px,0px)",
						"transform": "translate("+ valueLocal +"px,0px)"
					});
				}
		
			} else {

				valueGlobal = (distance < 0 ? "" : "-") + Math.abs(distance).toString();
				
			}//suppTrans
		}
		
		
		// ------------------------------------------------------------
		//  Paginate / Control
		// ------------------------------------------------------------
		
		$hp_slide.on("click", function(e) {
			if(dragging) return false;
		});		
		
		//previous slide
		$(selector + ' .hp_previous').on("click", function(e) {
			e.preventDefault();
			if (currentSlide <= 0) {
				gotoSlide($totalSlides-1);
			} else {
				previousSlide();
			}
			pauseAutoSlide();
		});
		
		//next slide
		$(selector + ' .hp_next').on("click", function(e) {		
			e.preventDefault();
			
			if (currentSlide >= $totalSlides-1) {
				gotoSlide(0);
			} else {
				nextSlide();
			}
			pauseAutoSlide();
		});
		
		//goto slide
		$hp_page.children('a').on("click", function(e) {
			e.preventDefault();
			gotoSlide( $(this).index() );
			pauseAutoSlide();
		});
		
		//current slide different of 0
		if (currentSlide > 0 && currentSlide < $totalSlides) {
			setTimeout(function() {
				gotoSlide(currentSlide);
			},500);
		}
		
		//play / pause button
		$play.on("click", function(e) {
			e.preventDefault();
			
			initAutoSlide();
			$(this).hide();
			$pause.show();
			$pause.css({ opacity:0 }).stop().animate({ opacity: 1 }, { duration: 500 } );
		});

		$pause.on("click", function(e) {
			e.preventDefault();
			
			pauseAutoSlide();
			$(this).hide();
			$play.show();
			$play.css({ opacity:0 }).stop().animate({ opacity: 1 }, { duration: 500 } );
		});
		
		//share icon
		//$(selector + ' .hp_share').on("click", function(e) {
	  	$hp_share.on("click", function(e) {
	  
			e.preventDefault();
		  	//dragging = true;
			
			var hpttId = null;
			var shareId = $(this).attr('id').split('_');
			
			hpttId = $(selector + ' #hptooltip_' + shareId[1]);
			
			if(hpttId.is(':hidden')) {
				hpttId.css({ display:'block', opacity:0 }).stop().animate({ opacity: 1 }, { duration: 400 } );
			} else {
				hpttId.css({ display:'none' });
			}
		});


		// ------------------------------------------------------------
		// auto slide
		// ------------------------------------------------------------
		
		var interval;
		
		function initAutoSlide() {
			autoSlide = true;

			function next() {
				if (currentSlide >= $totalSlides-1) {
					gotoSlide(0);
				} else {
					nextSlide();
				}
			}//next
			
			interval = setInterval(next, autoSlideDelay);
		}//initAutoSlide
		
		//if (autoSlide) initAutoSlide();

		
		function pauseAutoSlide() {
			clearInterval(interval);
			autoSlide = false;
			
			if (showPlayPause) {
				$play.show();
				$pause.hide();
			}
		}
		
		
		// ------------------------------------------------------------
		// lightbox
		// ------------------------------------------------------------
		
		var $lightbox = $(selector + ' .hp_lightbox');
		var $popupMaxWidth;
		var $popupMaxHeight;
		
		$lightbox.on("click", function(e) {
			
			e.preventDefault();
			//var $p = $(this);
			
			pauseAutoSlide();
			
			if(dragging) return false;
			
			//add hp_mask_lightbox
			$('body').append('<div id="hp_mask_lightbox"></div>');
			$('body').append('<div id="hp_popup_lightbox"></div>');
			$('#hp_popup_lightbox').append("<div id='hp_preload_lightbox'></div>");
			
			var $mask = $('#hp_mask_lightbox');
			var $popup = $('#hp_popup_lightbox');
		
			//Get width / height
			var winWidth = $(window).width();
			var winHeight = $(window).height();
			var docHeight = $(document).height();

			$mask.css({'width': winWidth,'height': docHeight});
			$mask.fadeTo(600,0.7);

			//popup
			$popup.css({
				top: winHeight / 2 - $popup.height() / 2,
			 	left: winWidth / 2 - $popup.width() / 2
			});
			
						
			lightboxData( $(this) );
			
			function lightboxData( currentProd ) {
				
				winWidth = $(window).width();
				winHeight = $(window).height();
				docHeight = $(document).height();
				
				var $p = currentProd;
				var data_href = $p.attr('href');
				
				if ($p.attr('data-type') != undefined ) {
					var data_type = $p.attr('data-type');
				}
				
				if ($p.attr('data-poster') != undefined ) {
					var data_poster = $p.attr('data-poster');
				} else {
					data_poster = '';
				}
				
				if ($p.attr('data-size') != undefined) {
					var data_size = $p.attr('data-size').split('x');
				} else {
					//default value
					var data_size = [640,360];
				}
				$popupMaxWidth = data_size[0];
				$popupMaxHeight = data_size[1];
				
				var is_img = ( data_href.indexOf('.jpg') != -1 || data_href.indexOf('.gif') != -1 || data_href.indexOf('.png') != -1 ) || data_type == 'image' ? true : false;
				
				if (is_img) {
					var this_item = $p;
					
					if ( is_ie7 || is_ie8 ) {
						var img = $("<img />").attr('src', data_href + "?" + new Date().getTime()).attr('id', 'large_image');
					} else {
						var img = $("<img />").attr('src', data_href).attr('id', 'large_image');
					}
						
					img.load(function() {
						
						var img_width = this.width;
						var img_height = this.height;
						
						if (this.width > winWidth || this.height > winHeight) { 
	
							var a = winWidth - 80;
							var b = this.width;
							
							var percentA = (a/b) * 100;
	
							this.height = (percentA / 100) * this.height;
							this.width = a;
						}
						
						$popupMaxWidth = this.width;
						$popupMaxHeight = this.height;
						
						popupCenterAnimate(this.width, this.height, img, this_item, 'image');
						$(img).hide();
					});
					
				} else if (data_type == 'video-youtube') {
					
					//screen resolution
					var w = parseInt(data_size[0]), h = parseInt(data_size[1]);
					if (w > winWidth) { w = winWidth - 80; }
					
					var str_id = data_href.split("?v=");
					var str_params = "?autohide=2&amp;autoplay=0&amp;controls=1&amp;disablekb=0&amp;fs=1&amp;hd=0&amp;loop=0&amp;rel=0&amp;showinfo=0&amp;showsearch=1&amp;wmode=transparent&amp;enablejsapi=1";
					var str_iframe = '<iframe class="video_player" id="video_player" width="' + w + '" height="' + h + '" src="http://www.youtube.com/embed/' + str_id[1] + str_params + '" frameborder="0" allowfullscreen></iframe>';
					
					popupCenterAnimate(w, h, str_iframe, $p, 'video');
					
				} else if (data_type == 'video-vimeo') {
					
					var w = parseInt(data_size[0]), h = parseInt(data_size[1]);
					if (w > winWidth) { w = winWidth - 80; }
	
					var str_id = data_href.split("/").pop();
					var str_iframe = '<iframe class="video_player" id="video_player" src="http://player.vimeo.com/video/' + str_id +  '?title=0&amp;byline=0&amp;portrait=0&amp;autoplay=0" width="' + w + '" height="' + h + '" frameborder="0"></iframe>';

					popupCenterAnimate(w, h, str_iframe, $p, 'video');
					
				} else if (data_type == 'html-content') {
					
					var w = parseInt(data_size[0]), h = parseInt(data_size[1]);
					if (w > winWidth) { w = winWidth - 80; }
					if (h > winHeight) { 
						h = winHeight - 120;
						$popupMaxHeight = h;
					}
					
					// ajax
					var post_id = $p.attr('data-id');
					var post_title = $p.attr("title");
					var post_type = $p.attr('data-post-type');
					
					var data = {
						action: 'hp_lite_query_ajax',
						post_id: post_id,
						post_type: post_type
					};
					
					$.get(hopos_script.ajaxurl, data, function(data) {
						if (data == 'post not found.') post_title = '';
						var str_div = '<div class="hp_html_content_lightbox" id="hp_html_content_lightbox"><h3>' + post_title + '</h3>' + data + '</div>';
						popupCenterAnimate(w, h, str_div, $p, 'html');
					});
					
				} else {
					
					//screen resolution
					var w = parseInt(data_size[0]), h = parseInt(data_size[1]);
					if (w > winWidth) { w = winWidth - 80; }
					
					if (data_type == 'video' || data_type == 'video-mp4' || data_type == 'video-flv') {
						//type='video/flv' or 'video/mp4'
						var str_div = '<div class="video_player" id="video_player"><video src="' + data_href +'" width="' + w  + '" height="' + h + '" poster="' + data_poster + '"></video></div>';
						popupCenterAnimate(w, h, str_div, $p, 'video');
					} else if (data_type == 'audio') {

						var browserChrome = /chrome/.test(navigator.userAgent.toLowerCase());
						
						if(browserChrome) {
							var str_div = '<div class="hp_audio_player"><audio controls="controls" preload="none" src="' + data_href +'" width="' + w  + '" height="' + h + '" poster="' + data_poster + '" type="audio/mpeg"></audio></div>';
						} else {
							var str_div = '<div class="hp_audio_player"><audio controls="controls" src="' + data_href +'" width="' + w  + '" height="' + h + '" poster="' + data_poster + '" type="audio/mpeg"></audio></div>';
						}

						popupCenterAnimate(w, h, str_div, $p, 'audio');
					}
					
				}//if

			}//lightboxData
			

			// ------------------------------------
			// popup center animate / append item
			// ------------------------------------
			
			function popupCenterAnimate(w,h,str,this_elem,content_type) {
				
				if (w > winWidth || h > winHeight) {
					if (w > winWidth) {
						w = winWidth - 80;
						str.width(w);
						str.height('auto');
					}
					
					if (h > winHeight) {
						h = winHeight - 80;
						str.height(h);
						str.width('auto');
					}
				}
				
				var $popup = $('#hp_popup_lightbox');

				$popup.stop().delay(100).animate({ 
					'top': winHeight / 2 - $popup.height() / 2,  
					'left': winWidth / 2 - $popup.width() / 2
				}, 400, function() {
					$('#hp_preload_lightbox').remove();
				});

				var posX = (winWidth / 2 - w / 2);
				var posY = (winHeight / 2 - h / 2);
				
				var padd = parseInt($popup.css('padding-left'));
				
				//alert( w + ' - ' + $popup.width() )
				
				$popup.stop().delay(100).animate({'height': h, 'width': w, 'top': posY,  'left': posX }, 400, function() {
					
					//add img/video/audio
					$popup.append(str);
					$(str).fadeIn(500);

					$popup.append("<div id='hp_close_btn_lightbox'></div>");
					
					//popup fade
					$("#hp_close_btn_lightbox").css({ opacity:0, display:'block' }).stop().animate({ opacity:1 }, { duration: 400 });
		
					//btn close
					$('#hp_close_btn_lightbox').click(function () {
						closeLightbox();
					});

					//execute script if is video or audio
					if (this_elem.attr('data-type') == 'video-mp4' || this_elem.attr('data-type') == 'video-flv') {
						$('video').mediaelementplayer({
							videoWidth: '100%',
							videoHeight: '100%',
							startVolume: 0.6,
							enableAutosize: true,
							features: ['playpause','current','progress','duration','volume','fullscreen'],
							videoVolume: 'horizontal'
						});	
					} else if (this_elem.attr('data-type') == 'audio') { 
						
						$('audio').mediaelementplayer({
							startVolume: 0.6,
							loop: true,
							audioWidth: '95%',
							features: ['playpause','current','progress','duration','volume','fullscreen'],
							videoVolume: 'horizontal'
						});
					}
					
					if (content_type == 'image') {
						var ligthboxChildren = $(this).children('#large_image');
					} else if (content_type == 'video') {
						var ligthboxChildren = $(this).children('#video_player');
					} else if (content_type == 'audio') {
						var ligthboxChildren = $(this).children('.hp_audio_player');
					} else if (content_type == 'html') {
						var ligthboxChildren = $(this).children('#hp_html_content_lightbox');
					}
					
					
					if ($(this).width() < ligthboxChildren.width() || $(this).height() < ligthboxChildren.height()) {
						if (content_type == 'html') {
							ligthboxChildren.css( { width: $(this).width() - 40, height: $(this).height() - 40 } );
						} else {
							ligthboxChildren.css( { width: $(this).width(), height: $(this).height() } );
						}
					}
				});
				
			}//popupCenterAnimate
			
	
			// -------------------------------
			// click hp_mask_lightbox
			// -------------------------------
			
			$('#hp_mask_lightbox').click(function () {
				closeLightbox();
			});

			$(document).keydown(function(e) {
				//escape key
				if (e.keyCode == 27) {
					closeLightbox();
				}
			});
			
			function closeLightbox() {
				if (!touchSupport) {
					if ( $('.mejs-container').length > 0 ) { 
						$('video,audio').each(function() {
							$(this)[0].player.pause();
						});
					}
				}
				
				$popupMaxWidth = undefined;
				$popupMaxHeight = undefined;
				
				$("#hp_mask_lightbox").stop().animate( { opacity: 0 }, { duration: 200 });
				$("#hp_popup_lightbox").stop().delay(50).animate({ opacity: 0 }, 400, function() {
					$('#hp_mask_lightbox').hide();
					$('#hp_mask_lightbox').remove();
					$('#hp_popup_lightbox').remove();
				});
				
				$(selector).trigger('resize'); 
			}
		});//lightbox
		

		// -------------------------------
		// resize event
		// -------------------------------
		
		function changeSlideHeight() {
			
			/////////////////////////////
			if (skinBoxedStyle) {
				setBarHeight();
			}
			
			for (var i=0; i < $hp_slide.length; i++) {
			
				var sld = $hp_slide.eq(i);
				var itemsHeight = 0;
				var tmpH = 0;
				
				for (var j=0; j < sld.find($hp_item).length; j++) {
					
					var it = sld.find($hp_item).eq(j);
					var itemMarginBottom = parseInt(it.css("marginBottom"));
					var itemMarginTop = parseInt(it.css("marginTop"));
					
					var itHeight = it.position().top + it.height() + itemMarginBottom + itemMarginTop;
					itemsHeight = Math.max(tmpH, itHeight);
					tmpH = itemsHeight;
				}//for j
				
				//////////////////////
				slideHeightArr[i] = itemsHeight;
				slideWidth = sld.width();
				
			}//for i
			
			gotoSlide(currentSlide);
		}

		function setBarHeight() {
			$(selector + ' .hp_bar').css({ bottom: 0 , top:'auto', position: 'absolute' });
			
			for (var i=0; i < $hp_item.length; i++) {
				$hp_item.eq(i).css({ height: $hp_item.eq(i).height() + $hp_bar.height() });
				$hp_bar.eq(i).css({ bottom: $hp_item.eq(i).position.top, position: 'absolute' });
			}
		  
		  	//console.log( $hp_item.eq(0).position.top );
		    //$hp_bar.css({ bottom:0, position: 'absolute' });
		}
		
		
		// -------------------------------
		// resize end
		// -------------------------------
		
		function resizeEnd() {
			imageAutoResize();
			changeSlideHeight();
			alignPaginationConfig();
		}
		
		
		// -------------------------------
		// image auto resize
		// -------------------------------
		
		function imageAutoResize() {

			var $hp_main_image = $(selector + " .hp_main_image");
			var newWidth = 0;
			var columns = 4;
			
			$hp_slide.children('.hp_clear').remove();
			$hp_item.css({ marginRight: 0 });

			
			// desktop / tablet / phone
			if (imgPerSlide == 1) {
				columns = 1;
			} else {
				if ( $(window).width() > 768) { //&& $(window).width() <= 960
					columns = resolution.res960;
				}
				if ( $(window).width() > 480 && $(window).width() <= 768) {
					columns = resolution.res768;
				}
				if ( $(window).width() <= 480) {
					columns = resolution.res480;
				}
			}

			
			var itemBorderLeft = parseInt($hp_item.css("border-left-width"));
			var itemBorderRight = parseInt($hp_item.css("border-right-width"));
			
			var imgBorderLeft = parseInt($hp_main_image.css("border-left-width"));
			var imgBorderRight = parseInt($hp_main_image.css("border-right-width"));
			var imgBorders = parseInt(imgBorderLeft + imgBorderRight);

			newWidth = ($hp_slide.width() / columns) - postMargin;
		  
		  
		  	//alert( itemBorderLeft )
			//ie8
			if (isNaN(imgBorders)) {
		  		var newWidth2 = newWidth + (postMargin/columns) - itemBorderLeft - itemBorderRight;
			} else {
			  	
			  if (is_firefox) {
			   	var newWidth2 = newWidth + (postMargin/columns) - itemBorderLeft - itemBorderRight - imgBorders - 1;
			  } else {
				var newWidth2 = newWidth + (postMargin/columns) - itemBorderLeft - itemBorderRight - imgBorders;
			  }
			}

			
			$hp_item.css({ width:newWidth2, height:'auto' });
			$hp_main_image.css({ width:newWidth2, height:'auto' });
			

			itemsSetup(true, newWidth2);
		}
		
				
		var maxWidth = parseFloat(slideWidth);
		var maxHeight = parseFloat(slideHeight);
		var currentHeight, currentWidth;
		var resizeTimer = 0;
		
		
		/////////////////////
		var imgPerSlide = $hp_slide.eq(0).find('.hp_item').length;
		var imgMaxWidth = $hp_slide.eq(0).find('.hp_main_image').width();
		var imgMaxHeight = $hp_slide.eq(0).find('.hp_main_image').height();
		
		var hasImage = $hp_slide.eq(0).find('.hp_main_image').length;
		if (hasImage == 0) {
			$hp_item.width('100%');
		}
		

		$(window).on("resize", function(e) {
			
			var windowHeight = $(window).height();
  			////var windowWidth = $(window).width();
			var windowWidth = $(selector).parent().width();
			
			
			if (currentHeight == undefined || currentHeight != windowHeight || currentWidth == undefined || currentWidth != windowWidth) {	
				
				if (sliderType == 'image_news') {
					
					$(selector).width("100%");

					if ($(selector).width() > maxWidth) {
						$(selector).width(maxWidth);
					}
					
					if ($(selector).width() < maxWidth) {
						$hp_slider.width( $(selector).width() );
						$hp_slide.width( $(selector).width() );
						
						///////////////////
						//imageAutoResize();
					}
					
					
					//if ( $(window).width() > maxWidth ) {
					if ( windowWidth >= maxWidth ) {
						$(selector).width(maxWidth);
						$hp_slider.width(maxWidth);
						$hp_slide.width(maxWidth);						

						/////////////////////
						//imageAutoResize();
					}
					
					
					//console.log( $(selector).parent().width() + " - " + maxWidth );
					
					////////////////////////////////
					if ( windowWidth >= maxWidth && imgOriginalPercent) {
						$(selector).width(windowWidth);
						$hp_slider.width(windowWidth);
						$hp_slide.width(windowWidth);
						
						/////////////////////
						//imageAutoResize();
					}
					
					//imageAutoResize();
					//changeSlideHeight();
					alignPaginationConfig();
					
					//resizeend
					if (resizeTimer) clearTimeout(resizeTimer);
					resizeTimer = setTimeout(resizeEnd, 300);

				}//image_news
				
				
				//popup lightbox resize
				if ( $('#hp_popup_lightbox').length > 0 ) {
					
					var $popup = $('#hp_popup_lightbox');
	
					$popup.css({
						'top': $(window).height() / 2 - $popup.height() / 2, 
						'left': $(window).width() / 2 - $popup.width() / 2
					});
					
					var $mask = $('#hp_mask_lightbox');
					$mask.css({'width': $(window).width(),'height': $(document).height() });
					
					if ( $(window).width() < $popup.width() ) {
						
						$popup.css({ width:'82%', height: 'auto', marginLeft: -20 });
						$('.video_player').css( { width:'100%' } );
						
						/////////////////////
						$popup.find('.video_player').css( { width:'100%' } );
						$popup.find('#large_image').css( { width:'100%', height: 'auto' } );
						//$popup.find('object').css( { width:'100%' } );
						
					}
					
					if ( $(window).width() > $popupMaxWidth ) {
						//$popup.css( { width: $popupMaxWidth } );
						$popup.css( { width:'auto' } );
						
						var img = $popup.find('#large_image');
						if (img) {
							img.css( { width:'100%', height: 'auto' } );
							$popup.css( { width: $popupMaxWidth, height: 'auto' } );
							
							///////////////////
							if ( img.height() > $(window).height() ) {
								img.height( $(window).height() - 180 );
								img.width( 'auto' );
								
								$popup.css( { width: img.width(), height: img.height() } );
							}
						}
					}
					
					if ($popup.height() > $popupMaxHeight) {
						$popup.height( $popupMaxHeight );
					}
					if ($(window).height() < $popup.height()) {
						$popup.height( $(window).height() - 120 );
						
						$popup.find('#large_image').css( { width: 'auto', height: $popup.height() } );
					}

				}//lightbox
				
				currentHeight = windowHeight;
     			currentWidth = windowWidth;
	  
			}//currentHeight, currentWidth;
			
	
		});//resize
		
		
		// ----------------------------------
		// remove the empty p tags, wp add
		// ----------------------------------
		
		var $hp_p = $(selector + ' p');
		$hp_p.each(function() {
			var $this = $(this);
			if($this.html().replace(/\s|&nbsp;/g, '').length == 0 || $this.text() == '') {			
				$this.remove();
			}
		});
		
		
		// ----------------------------------
		// insert skin
		// ----------------------------------	
			
		var allChild = $(selector).find('*');
		var selSkin = skin;
		
		//
		if ($(selector).hasClass("skinner") ) {
			$(selector).removeClass("skinner").addClass(selSkin);
		}
		
		allChild.each(function() {
			var $this = $(this);
			
			////$this.toggleClass('"skinner ' + selSkin + '"');
			if ($this.hasClass("skinner") ) {
				$this.removeClass("skinner").addClass(selSkin);
			}
		});

		
		return this;
	};
	
})(jQuery);


// ----------------------------------------------------
// if supports css3 transitions
// ----------------------------------------------------
		
function supportsTransitions() {
	var b = document.body || document.documentElement;
	var s = b.style;
	var p = 'transition';
	if(typeof s[p] == 'string') { return true; }

	// Tests for vendor specific prop
	v = ['Moz', 'Webkit', 'Khtml', 'O', 'ms'],
	p = p.charAt(0).toUpperCase() + p.substr(1);
	for(var i=0; i<v.length; i++) {
	  if(typeof s[v[i] + p] == 'string') { return true; }
	}
	return false;
}


/*
* touchSwipe - jQuery Plugin
* https://github.com/mattbryson/TouchSwipe-Jquery-Plugin
* http://labs.skinkers.com/touchSwipe/
* http://plugins.jquery.com/project/touchSwipe
*
* Copyright (c) 2010 Matt Bryson (www.skinkers.com)
* Dual licensed under the MIT or GPL Version 2 licenses.
*
* $version: 1.3.3
*/(function(e){function g(t){if(t&&t.allowPageScroll===undefined&&(t.swipe!==undefined||t.swipeStatus!==undefined)){t.allowPageScroll=s}if(!t){t={}}t=e.extend({},e.fn.swipe.defaults,t);return this.each(function(){var n=e(this);var r=n.data(v);if(!r){r=new y(this,t);n.data(v,r)}})}function y(m,g){function H(e){e=e.originalEvent;var t,n=d?e.touches[0]:e;k=l;if(d){L=e.touches.length}else{if(e.preventDefault){e.preventDefault()}else{e.returnValue=false}}x=0;T=null;N=0;if(!d||L===g.fingers||g.fingers===f){A.x=O.x=navigator.userAgent.match(/msie/i)&&(navigator.userAgent.match(/8/)||navigator.userAgent.match(/7/))?n.clientX:n.pageX;A.y=O.y=navigator.userAgent.match(/msie/i)&&(navigator.userAgent.match(/8/)||navigator.userAgent.match(/7/))?n.clientY:n.pageY;_=J();if(g.swipeStatus){t=I(e,k)}}else{F(e)}if(t===false){k=p;I(e,k);return t}else{C.bind(w,B);C.bind(E,j)}}function B(e){e=e.originalEvent;if(k===h||k===p)return;var t,n=d?e.touches[0]:e;O.x=navigator.userAgent.match(/msie/i)&&(navigator.userAgent.match(/8/)||navigator.userAgent.match(/7/))?n.clientX:n.pageX;O.y=navigator.userAgent.match(/msie/i)&&(navigator.userAgent.match(/8/)||navigator.userAgent.match(/7/))?n.clientY:n.pageY;D=J();T=V();if(d){L=e.touches.length}k=c;U(e,T);if(L===g.fingers||g.fingers===f||!d){x=W();N=z();if(g.swipeStatus){t=I(e,k,T,x,N)}if(!g.triggerOnTouchEnd){var r=!R();if(q()===true){k=h;t=I(e,k)}else if(r){k=p;I(e,k)}}}else{k=p;I(e,k)}if(t===false){k=p;I(e,k)}}function j(e){e=e.originalEvent;if(e.preventDefault){e.preventDefault()}else{e.returnValue=false}D=J();x=W();T=V();N=z();if(g.triggerOnTouchEnd||g.triggerOnTouchEnd===false&&k===c){k=h;if((L===g.fingers||g.fingers===f||!d)&&O.x!==0){var t=!R();if((q()===true||q()===null)&&!t){I(e,k)}else if(t||q()===false){k=p;I(e,k)}}else{k=p;I(e,k)}}else if(k===c){k=p;I(e,k)}C.unbind(w,B,false);C.unbind(E,j,false)}function F(){L=0;A.x=0;A.y=0;O.x=0;O.y=0;M.x=0;M.y=0;D=0;_=0}function I(e,s){var o=undefined;if(g.swipeStatus){o=g.swipeStatus.call(C,e,s,T||null,x||0,N||0,L)}if(s===p){if(g.click&&(L===1||!d)&&(isNaN(x)||x===0)){o=g.click.call(C,e,e.target)}}if(s==h){if(g.swipe){o=g.swipe.call(C,e,T,x,N,L)}switch(T){case t:if(g.swipeLeft){o=g.swipeLeft.call(C,e,T,x,N,L)}break;case n:if(g.swipeRight){o=g.swipeRight.call(C,e,T,x,N,L)}break;case r:if(g.swipeUp){o=g.swipeUp.call(C,e,T,x,N,L)}break;case i:if(g.swipeDown){o=g.swipeDown.call(C,e,T,x,N,L)}break}}if(s===p||s===h){F(e)}return o}function q(){if(g.threshold!==null){return x>=g.threshold}return null}function R(){var e;if(g.maxTimeThreshold){if(N>=g.maxTimeThreshold){e=false}else{e=true}}else{e=true}return e}function U(e,f){if(g.allowPageScroll===s){if(e.preventDefault){e.preventDefault()}else{e.returnValue=false}}else{var l=g.allowPageScroll===o;switch(f){case t:if(g.swipeLeft&&l||!l&&g.allowPageScroll!=u){if(e.preventDefault){e.preventDefault()}else{e.returnValue=false}}break;case n:if(g.swipeRight&&l||!l&&g.allowPageScroll!=u){if(e.preventDefault){e.preventDefault()}else{e.returnValue=false}}break;case r:if(g.swipeUp&&l||!l&&g.allowPageScroll!=a){if(e.preventDefault){e.preventDefault()}else{e.returnValue=false}}break;case i:if(g.swipeDown&&l||!l&&g.allowPageScroll!=a){if(e.preventDefault){e.preventDefault()}else{e.returnValue=false}}break}}}function z(){return D-_}function W(){return Math.round(Math.sqrt(Math.pow(O.x-A.x,2)+Math.pow(O.y-A.y,2)))}function X(){var e=A.x-O.x;var t=O.y-A.y;var n=Math.atan2(t,e);var r=Math.round(n*180/Math.PI);if(r<0){r=360-Math.abs(r)}return r}function V(){var e=X();if(e<=45&&e>=0){return t}else if(e<=360&&e>=315){return t}else if(e>=135&&e<=225){return n}else if(e>45&&e<135){return i}else{return r}}function J(){var e=new Date;return e.getTime()}function K(){C.unbind(b,H);C.unbind(S,F);C.unbind(w,B);C.unbind(E,j)}var y=d||!g.fallbackToMouseEvents,b=y?"touchstart":"mousedown",w=y?"touchmove":"mousemove",E=y?"touchend":"mouseup",S="touchcancel";var x=0;var T=null;var N=0;var C=e(m);var k="start";var L=0;var A={x:0,y:0};var O={x:0,y:0};var M={x:0,y:0};var _=0;var D=0;try{C.bind(b,H);C.bind(S,F)}catch(P){e.error("events not supported "+b+","+S+" on jQuery.swipe")}this.enable=function(){C.bind(b,H);C.bind(S,F);return C};this.disable=function(){K();return C};this.destroy=function(){K();C.data(v,null);return C};}var t="left",n="right",r="up",i="down",s="none",o="auto",u="horizontal",a="vertical",f="all",l="start",c="move",h="end",p="cancel",d="ontouchstart"in window,v="TouchSwipe";var m={fingers:1,threshold:50,maxTimeThreshold:null,swipe:null,swipeLeft:null,swipeRight:null,swipeUp:null,swipeDown:null,swipeStatus:null,click:null,triggerOnTouchEnd:true,allowPageScroll:"auto",fallbackToMouseEvents:true};e.fn.swipe=function(t){var n=e(this),r=n.data(v);if(r&&typeof t==="string"){if(r[t]){return r[t].apply(this,Array.prototype.slice.call(arguments,1))}else{e.error("Method "+t+" does not exist on jQuery.swipe")}}else if(!r&&(typeof t==="object"||!t)){return g.apply(this,arguments)}return n};e.fn.swipe.defaults=m;e.fn.swipe.phases={PHASE_START:l,PHASE_MOVE:c,PHASE_END:h,PHASE_CANCEL:p};e.fn.swipe.directions={LEFT:t,RIGHT:n,UP:r,DOWN:i};e.fn.swipe.pageScroll={NONE:s,HORIZONTAL:u,VERTICAL:a,AUTO:o};e.fn.swipe.fingers={ONE:1,TWO:2,THREE:3,ALL:f}})(jQuery);

//------------------------------------
// images loaded plugin
// MIT License. by Paul Irish et al.
//------------------------------------

(function(c,q){var m="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";c.fn.imagesLoaded=function(f){function n(){var b=c(j),a=c(h);d&&(h.length?d.reject(e,b,a):d.resolve(e));c.isFunction(f)&&f.call(g,e,b,a)}function p(b){k(b.target,"error"===b.type)}function k(b,a){b.src===m||-1!==c.inArray(b,l)||(l.push(b),a?h.push(b):j.push(b),c.data(b,"imagesLoaded",{isBroken:a,src:b.src}),r&&d.notifyWith(c(b),[a,e,c(j),c(h)]),e.length===l.length&&(setTimeout(n),e.unbind(".imagesLoaded",
p)))}var g=this,d=c.isFunction(c.Deferred)?c.Deferred():0,r=c.isFunction(d.notify),e=g.find("img").add(g.filter("img")),l=[],j=[],h=[];c.isPlainObject(f)&&c.each(f,function(b,a){if("callback"===b)f=a;else if(d)d[b](a)});e.length?e.bind("load.imagesLoaded error.imagesLoaded",p).each(function(b,a){var d=a.src,e=c.data(a,"imagesLoaded");if(e&&e.src===d)k(a,e.isBroken);else if(a.complete&&a.naturalWidth!==q)k(a,0===a.naturalWidth||0===a.naturalHeight);else if(a.readyState||a.complete)a.src=m,a.src=d}):
n();return d?d.promise(g):g}})(jQuery);


//------------------------------------
// respondjs - media query support
//------------------------------------

/*! matchMedia() polyfill - Test a CSS media type/query in JS. Authors & copyright (c) 2012: Scott Jehl, Paul Irish, Nicholas Zakas. Dual MIT/BSD license */
/*! NOTE: If you're already including a window.matchMedia polyfill via Modernizr or otherwise, you don't need this part */
window.matchMedia=window.matchMedia||function(a){"use strict";var c,d=a.documentElement,e=d.firstElementChild||d.firstChild,f=a.createElement("body"),g=a.createElement("div");return g.id="mq-test-1",g.style.cssText="position:absolute;top:-100em",f.style.background="none",f.appendChild(g),function(a){return g.innerHTML='&shy;<style media="'+a+'"> #mq-test-1 { width: 42px; }</style>',d.insertBefore(f,e),c=42===g.offsetWidth,d.removeChild(f),{matches:c,media:a}}}(document);

/*! Respond.js v1.1.0: min/max-width media query polyfill. (c) Scott Jehl. MIT/GPLv2 Lic. j.mp/respondjs  */
(function(a){"use strict";function x(){u(!0)}var b={};if(a.respond=b,b.update=function(){},b.mediaQueriesSupported=a.matchMedia&&a.matchMedia("only all").matches,!b.mediaQueriesSupported){var q,r,t,c=a.document,d=c.documentElement,e=[],f=[],g=[],h={},i=30,j=c.getElementsByTagName("head")[0]||d,k=c.getElementsByTagName("base")[0],l=j.getElementsByTagName("link"),m=[],n=function(){for(var b=0;l.length>b;b++){var c=l[b],d=c.href,e=c.media,f=c.rel&&"stylesheet"===c.rel.toLowerCase();d&&f&&!h[d]&&(c.styleSheet&&c.styleSheet.rawCssText?(p(c.styleSheet.rawCssText,d,e),h[d]=!0):(!/^([a-zA-Z:]*\/\/)/.test(d)&&!k||d.replace(RegExp.$1,"").split("/")[0]===a.location.host)&&m.push({href:d,media:e}))}o()},o=function(){if(m.length){var b=m.shift();v(b.href,function(c){p(c,b.href,b.media),h[b.href]=!0,a.setTimeout(function(){o()},0)})}},p=function(a,b,c){var d=a.match(/@media[^\{]+\{([^\{\}]*\{[^\}\{]*\})+/gi),g=d&&d.length||0;b=b.substring(0,b.lastIndexOf("/"));var h=function(a){return a.replace(/(url\()['"]?([^\/\)'"][^:\)'"]+)['"]?(\))/g,"$1"+b+"$2$3")},i=!g&&c;b.length&&(b+="/"),i&&(g=1);for(var j=0;g>j;j++){var k,l,m,n;i?(k=c,f.push(h(a))):(k=d[j].match(/@media *([^\{]+)\{([\S\s]+?)$/)&&RegExp.$1,f.push(RegExp.$2&&h(RegExp.$2))),m=k.split(","),n=m.length;for(var o=0;n>o;o++)l=m[o],e.push({media:l.split("(")[0].match(/(only\s+)?([a-zA-Z]+)\s?/)&&RegExp.$2||"all",rules:f.length-1,hasquery:l.indexOf("(")>-1,minw:l.match(/\(\s*min\-width\s*:\s*(\s*[0-9\.]+)(px|em)\s*\)/)&&parseFloat(RegExp.$1)+(RegExp.$2||""),maxw:l.match(/\(\s*max\-width\s*:\s*(\s*[0-9\.]+)(px|em)\s*\)/)&&parseFloat(RegExp.$1)+(RegExp.$2||"")})}u()},s=function(){var a,b=c.createElement("div"),e=c.body,f=!1;return b.style.cssText="position:absolute;font-size:1em;width:1em",e||(e=f=c.createElement("body"),e.style.background="none"),e.appendChild(b),d.insertBefore(e,d.firstChild),a=b.offsetWidth,f?d.removeChild(e):e.removeChild(b),a=t=parseFloat(a)},u=function(b){var h="clientWidth",k=d[h],m="CSS1Compat"===c.compatMode&&k||c.body[h]||k,n={},o=l[l.length-1],p=(new Date).getTime();if(b&&q&&i>p-q)return a.clearTimeout(r),r=a.setTimeout(u,i),void 0;q=p;for(var v in e)if(e.hasOwnProperty(v)){var w=e[v],x=w.minw,y=w.maxw,z=null===x,A=null===y,B="em";x&&(x=parseFloat(x)*(x.indexOf(B)>-1?t||s():1)),y&&(y=parseFloat(y)*(y.indexOf(B)>-1?t||s():1)),w.hasquery&&(z&&A||!(z||m>=x)||!(A||y>=m))||(n[w.media]||(n[w.media]=[]),n[w.media].push(f[w.rules]))}for(var C in g)g.hasOwnProperty(C)&&g[C]&&g[C].parentNode===j&&j.removeChild(g[C]);for(var D in n)if(n.hasOwnProperty(D)){var E=c.createElement("style"),F=n[D].join("\n");E.type="text/css",E.media=D,j.insertBefore(E,o.nextSibling),E.styleSheet?E.styleSheet.cssText=F:E.appendChild(c.createTextNode(F)),g.push(E)}},v=function(a,b){var c=w();c&&(c.open("GET",a,!0),c.onreadystatechange=function(){4!==c.readyState||200!==c.status&&304!==c.status||b(c.responseText)},4!==c.readyState&&c.send(null))},w=function(){var b=!1;try{b=new a.XMLHttpRequest}catch(c){b=new a.ActiveXObject("Microsoft.XMLHTTP")}return function(){return b}}();n(),b.update=n,a.addEventListener?a.addEventListener("resize",x,!1):a.attachEvent&&a.attachEvent("onresize",x)}})(this);
