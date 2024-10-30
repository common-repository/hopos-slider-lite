(function($) {
	
	jQuery(window).load(function() {
		
		//--------------------------------------------
		// info tooltip
		//--------------------------------------------
			
		var hp_info;
		var btnClicked = '';
		
		$('.hp_info_btn').click(function(e) {
			e.preventDefault();
			
			if (btnClicked == $(this).attr('id')) {
				$('.hp_info').hide();
				btnClicked = '';
				return false;
			}
			
			btnClicked = $(this).attr('id');
			
			$('.hp_info').hide();
			hp_info = $(this).next('.hp_info');
			hp_info.show();
			hp_info.css( { top: $(this).position().top - 10, left: $(this).position().left } );
		});
		
		
		//--------------------------------------------
		// change event - open content
		//--------------------------------------------
		
		//alert( $('#_hp_open_content').val() )
		
		var hp_open_content = $('#_hp_open_content').val();
				
		//setTimeout(function() {
			//$('#field_open_in_new_window').hide();
		//},1000);
		
		if (hp_open_content == 'open_page') {
			$('#field_open_in_new_window').show();
			$('#field_lightbox_size').hide();
			$('#field_custom_link_url').hide();
		} else if (hp_open_content == 'open_lightbox') {
			$('#field_open_in_new_window').hide();
			$('#field_lightbox_size').show();
			$('#field_custom_link_url').hide();
		} else if (hp_open_content == 'custom_link') {
			$('#field_open_in_new_window').show();
			$('#field_lightbox_size').hide();
			$('#field_custom_link_url').show();
		}
		
		
		$('#_hp_open_content').change(function(e) {		
			if ($(this).val() == 'open_page') {
				$('#field_open_in_new_window').show();
				$('#field_lightbox_size').hide();
				$('#field_custom_link_url').hide();
			} else if ($(this).val() == 'open_lightbox') {
				$('#field_open_in_new_window').hide();
				$('#field_lightbox_size').show();
				$('#field_custom_link_url').hide();
			} else if ($(this).val() == 'custom_link') {
				$('#field_open_in_new_window').show();
				$('#field_lightbox_size').hide();
				$('#field_custom_link_url').show();
			}
		});
		
		
		//--------------------------------------------
		// change event - video url
		//--------------------------------------------
		
		if ($('#_hp_video_url').val() != '') {
			$('#field_video_type').show();
		} else {
			$('#field_video_type').hide();
		}
		
		$('#_hp_video_url').keyup(function(e) {	
				
			var str = $(this).val();
	
			if( $.trim(str) != '') {
				$('#field_video_type').show();
			} else {
				$('#field_video_type').hide();
			}
		});
		
		
		//--------------------------------------------
		// change event - add excerpt
		//--------------------------------------------
		
		if ($('#hpAddExcerpt').val() == 'true') {
			$('#field_hpExcerptNumWords').show();
		} else {
			$('#field_hpExcerptNumWords').hide();
		}
		
		$('#hpAddExcerpt').change(function(e) {		
			if ($(this).val() == 'true') {
				$('#field_hpExcerptNumWords').show();
			} else {
				$('#field_hpExcerptNumWords').hide();
			}
		});
		
		
		//--------------------------------------------
		// change event - custom skin
		//--------------------------------------------
		
		
		function infoSkin() {
			
			if ($('#hpSkin').val() == 'basic') {
				$('#hp_class_info').html('/* Example for Basic skin. This will change the color of the Title to blue. */ <br>.hp_title a.basic { <br>&nbsp;&nbsp;&nbsp;color: blue; <br>}');
			} else if ($('#hpSkin').val() == 'box') {
				$('#hp_class_info').html('/* Example for Box skin. This will change the color of the Title to blue. */ <br>.hp_title a.box { <br>&nbsp;&nbsp;&nbsp;color: blue; <br>}');
			} else if ($('#hpSkin').val() == 'boxed') {
				$('#hp_class_info').html('/* Example for Boxed skin. This will change the color of the Title to blue. */ <br>.hp_title a.boxed { <br>&nbsp;&nbsp;&nbsp;color: blue; <br>}');
			} else if ($('#hpSkin').val() == 'boxed2') {
				$('#hp_class_info').html('/* Example for Boxed 2 skin. This will change the color of the Title to blue. */ <br>.hp_title a.boxed2 { <br>&nbsp;&nbsp;&nbsp;color: blue; <br>}');
			} else if ($('#hpSkin').val() == 'img_border') {
				$('#hp_class_info').html('/* Example for Image Border skin. This will change the color of the Title to blue. */ <br>.hp_title a.img_border { <br>&nbsp;&nbsp;&nbsp;color: blue; <br>}');
			} else if ($('#hpSkin').val() == 'full_slider') {
				$('#hp_class_info').html('/* Example for Full Slider skin. This will change the color of the Title to blue. */ <br>.hp_title a.full_slider { <br>&nbsp;&nbsp;&nbsp;color: blue; <br>}');
			} else if ($('#hpSkin').val() == 'aqua') {
				$('#hp_class_info').html('/* Example for Aqua skin. This will change the color of the Title to blue. */ <br>.hp_title a.aqua { <br>&nbsp;&nbsp;&nbsp;color: blue; <br>}');
			} else if ($('#hpSkin').val() == 'green') {
				$('#hp_class_info').html('/* Example for Green skin. This will change the color of the Title to blue. */ <br>.hp_title a.green { <br>&nbsp;&nbsp;&nbsp;color: blue; <br>}');
			} else if ($('#hpSkin').val() == 'orange') {
				$('#hp_class_info').html('/* Example for Orange skin. This will change the color of the Title to blue. */ <br>.hp_title a.orange { <br>&nbsp;&nbsp;&nbsp;color: blue; <br>}');
			} else if ($('#hpSkin').val() == 'yellow') {
				$('#hp_class_info').html('/* Example for Yellow skin. This will change the color of the Title to blue. */ <br>.hp_title a.yellow { <br>&nbsp;&nbsp;&nbsp;color: blue; <br>}');
			} else if ($('#hpSkin').val() == 'blue') {
				$('#hp_class_info').html('/* Example for Blue skin. This will change the color of the Title to blue. */ <br>.hp_title a.blue { <br>&nbsp;&nbsp;&nbsp;color: blue; <br>}');
			} else if ($('#hpSkin').val() == 'pink') {
				$('#hp_class_info').html('/* Example for Pink skin. This will change the color of the Title to blue. */ <br>.hp_title a.pink{ <br>&nbsp;&nbsp;&nbsp;color: blue; <br>}');
			} else if ($('#hpSkin').val() == 'gray') {
				$('#hp_class_info').html('/* Example for Gray skin. This will change the color of the Title to blue. */ <br>.hp_title a.gray { <br>&nbsp;&nbsp;&nbsp;color: blue; <br>}');
			} else if ($('#hpSkin').val() == 'custom') {
				$('#hp_class_info').html('/* The "Custom" option does not use any of the predefined Skin. */ <br>.hp_title a.custom { <br>&nbsp;&nbsp;&nbsp;color: blue; <br>}');
			}
	
		}
		
	
		//infoSkin()
		$('#hpSkin').on('load', infoSkin());
		
		$('#hpSkin').on('change', function() {
			infoSkin();
		});
	



		//--------------------------------------------
		// click event - hpCustomThisSkin
		//--------------------------------------------
		
		//alert($('#hpCustomThisSkin').attr("checked"))
		
		if ( $('#hpCustomThisSkin').attr("checked") == "checked" ) {
				$(this).attr('checked', 'checked');
				$('#hpCustomSkinContainer').show();
			} else {
				$(this).removeAttr('checked');
				$('#hpCustomSkinContainer').hide();
			}
		
		$('#hpCustomThisSkin').click(function(e) {
			//e.preventDefault();
			
			if ( $(this).attr("checked") == "checked") {
				$(this).attr('checked', 'checked');
				$(this).attr('value', 'true');
				$('#hpCustomSkinContainer').show();
			} else {
				$(this).removeAttr('checked');
				$('#hpCustomSkinContainer').hide();
				$(this).attr('value', 'false');
			}
			
			//alert( $(this).val() );

		});
		
		
		//--------------------------------------------
		// custom post type / taxonomy
		//--------------------------------------------
		
		if ( $('#hp_url').val() != undefined ) {
		
			var data = {
				action: 'hp_lite_taxonomy_ajax',
				post_type: $('#hpPostType').val(),
				id: $('#hp_id').val()
			};
			
			$.get(hopos_lite_util.ajaxurl, data, function(data) {
				$('#field_tax_categories').html( data );
			});

		}
		
		
		$('#hpPostType').change(function(e) {
	
			var data = {
				action: 'hp_lite_taxonomy_ajax',
				post_type: $(this).val(),
				id: $('#hp_id').val()
			};
			
			$.get(hopos_lite_util.ajaxurl, data, function(data) {
				$('#field_tax_categories').html( data );
	
			});
		});
		
		
		//--------------------------------------------
		// save options ajax
		//--------------------------------------------
		
		$('#save_hopos_lite').click(function(e) {
			e.preventDefault();
			
			$('.hpPreload').css({display:'block'});
			$('.hpSaved').css({display:'none'});
			
			var cats = '';
			
			$('#field_tax_categories input').each(function() {				
				if ( $(this).is(':checked') ) {
					cats += $(this).val() + ",";
				}
			});
			
			var n = cats.lastIndexOf(",");
			cats = cats.substr(0,n);
			
			
			///////////////////////
			var hopos_plugin_dir = hopos_lite_util.hopos_plugin_dir;
			var hpCustomSkinVal = $('#hpCustomSkin').val();
		
			hpCustomSkinReplace = replaceAll(hpCustomSkinVal, "../../", hopos_plugin_dir);
			hpCustomSkinReplace = replaceAll(hpCustomSkinReplace, "../", hopos_plugin_dir);
			
				
			var data = {
				action: 'hp_lite_save_ajax',	
				hp_action: $('#hp_action').val(),
				hp_id: $('#hp_id').val(),
				hp_name: $('#title').val(),
				hpCustomSkin: hpCustomSkinReplace,
				
				hpPostsPerSlide: $('#hpPostsPerSlide').val(),
				hpSlideWidth: $('#hpSlideWidth').val(),
				hpSlideHeight: $('#hpSlideHeight').val(),
				hpPostMargin: $('#hpPostMargin').val(),
				hpShowPreviousNext: $('#hpShowPreviousNext').val(),
				hpScrollSpeed: $('#hpScrollSpeed').val(),
				hpAutoSlide: $('#hpAutoSlide').val(),
				hpAutoSlideDelay: $('#hpAutoSlideDelay').val(),
				hpShowPlayPause: $('#hpShowPlayPause').val(),
				hpShowPagination: $('#hpShowPagination').val(),
				hpAlignPagination: $('#hpAlignPagination').val(),
				hpSwipeDrag: $('#hpSwipeDrag').val(),
				hpPageStyle: $('#hpPageStyle').val(),
				hpOrientation: $('#hpOrientation').val(),
				hpThumbWidth: $('#hpThumbWidth').val(),
				hpThumbHeight: $('#hpThumbHeight').val(),
				hpSkin: $('#hpSkin').val(),
				hpCustomThisSkin: $('#hpCustomThisSkin').val(),
				hpAddExcerpt: $('#hpAddExcerpt').val(),
				hpExcerptNumWords: $('#hpExcerptNumWords').val(),
				hpShowTitle: $('#hpShowTitle').val(),
				hpAddShadow: $('#hpAddShadow').val(),
				hpTitleHeader: $('#hpTitleHeader').val(),
				hpAmountPosts: $('#hpAmountPosts').val(),
				hpCategories: cats,
				hpPostType: $('#hpPostType').val(),
				hpTaxonomy: $('#hpTaxonomy').val(),
				hpOrderby: $('#hpOrderby').val(),
				hpOrder: $('#hpOrder').val(),
				hpResolution960: $('#hpResolution960').val(),
				hpResolution768: $('#hpResolution768').val(),
				hpResolution480: $('#hpResolution480').val()
			};


			$.post(hopos_lite_util.ajaxurl, data).error( 
				function() {
					alert('Error. Please try again.');
				}).success( function(data) {
					
					//alert(data); return false;
					
					if (data == 'permission denied') {
						alert ('permission denied');
						return false;
					}

					if (data == 'saved') {
						
						//edit //message
						$('.hpPreload').css({display:'none'});
						$('.hpSaved').css({display:'block', opacity:0}).stop().animate({ opacity:1 }, { duration: 400 });
						setTimeout( function() { $('.hpSaved').stop().animate({ opacity:0 }, { duration: 600 }) }, 5000);
					
					} else {

						//add
						var splitData = data.split("||");
						var hp_url = $('#hp_url').val() + '&action=edit&id=' + splitData[1];
						window.location = hp_url;
					}
				});

			return false;
	
		});//save
		
		
		//--------------------------------------------
		// delete item
		//--------------------------------------------
		
		$('.delete_hopos').click(function(e) {
			
			e.preventDefault();
			
			var name = $(this).attr('data-name');
			var conf = confirm('Confirm delete? \n"' + name + '"');
			
			if (conf == true) {
				
				var id = $(this).attr('id').split("_");
				
				var data = {
					action: 'hp_lite_delete_ajax',
					hp_action: 'delete',
					hp_id: id[1]
				}
				
				$.post(hopos_lite_util.ajaxurl, data).error( 
					function() {
						alert('Error. Please try again.');
					}).success( function(data) {
						
						if (data == 'permission denied') {
							alert ('permission denied');
							return false;
						}	
						
						if (data == 'deleted') {
							var hp_url = $('#hp_url_list').val() + '&action=list';
							window.location = hp_url;
						}
					});
			}
		
		});//delete
		
		
		//--------------------------------------------
		// title prompt
		//--------------------------------------------
			
		$('#titlediv #title').click(function(e) {
			$('#hp-title-prompt-text').hide();
		});
		


		//--------------------------------------------
		// function replace characters
		//--------------------------------------------
		
		function replaceAll(string, token, newtoken) {
			while (string.indexOf(token) != -1) {
				string = string.replace(token, newtoken);
			}
			return string;
		}
		
	});//ready

})(jQuery);