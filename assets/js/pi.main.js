(function($)
{
	$(document).ready(function(){


      		
      	
		
		$("body").after("<div class='pi-inline' style='display:none'></div>");

		var $container, $data, $numOfColumns, $gutter, $toltalGut, $widthOfItem, $widthAfter, $calPadding="0px";

		piCaculateSize();
		piGalleryHeader();
		piLightboxWrapper();


		/* ----------------------------- */
		/* piCaculateSize
		/* ----------------------------- */
		function piCaculateSize()
		{
			var $widthOfWrap=0;

			$(".pi-gallery-lighbox").each(function()
			{
 
				$widthOfWrap = $(this).attr("data-width");
				 
				if ( $widthOfWrap.search("%") != -1 )
				{ 
					$widthOfWrap = $(this).parent().width() * parseInt($widthOfWrap)/100;
				}else if ( $widthOfWrap == '' || $widthOfWrap == 0 )
				{
					$widthOfWrap = $(this).parent().width();
				}
	 	
				$(this).css({width: $widthOfWrap});
	 
			 	$container 		= $(this).find(".js_contain_data");	
				// var $pWidth 	= $container.parent().width();
				$data 			= $container.data(); 
  
				$numOfColumns  	= typeof  $data	!= 'undefined' ? $data.columns : 0;
				$numOfColumns   = $numOfColumns != '' ? $numOfColumns : 1;
				$gutter			= typeof  $data != 'undefined' ? $data.gutter : 0;
				$gutter         = $gutter != '' ? $gutter : 0; 
				$toltalGut  	= $gutter*$numOfColumns;
				
				$widthOfItem = ( (  ( ($widthOfWrap -  $toltalGut - 2)/$numOfColumns)/$widthOfWrap )*100 );
			
				if ( $(this).find(".pi_container_mixup").length > 0 )
				{
					$(this).find(".pirates").css({width: $widthOfItem+'%', 'margin-right': $gutter});
					$(this).find(".pi-loadmore").attr({"data-width": $widthOfItem+'%', 'data-marginright': $gutter});
				}
			})
		}
		
		/* ----------------------------- */
		/* piGalleryHeader
		/* ----------------------------- */
		function piGalleryHeader()
		{
			$(".pi-gallery-header").each(function()
			{
				var $aHeaderData = $(this).data();
				var $ImgBg 		 = $aHeaderData.bgimg;
				var $ImgColor    = $aHeaderData.bgcolor;
				var $BgType 	 = $aHeaderData.bgtype;


				if ( $BgType == 'color' )
				{
					$(this).css({'background-color': $ImgColor});
				}else if ($BgType == 'image' )
				{
					$(this).css({'background': 'url('+$ImgB+') top left no-repeat'});
				}

				
				var $aDataTitle = $(this).find(".pi-gallery-title").data();
			
				var $aDataDes 	= $(this).find(".pi-gallery-des").data();
	 	
			
				
				if ( typeof $aDataDes != 'undefined' )
				{
				
					var $textAlignDes 	= $aDataDes.textalign;
					var $colorDes 		= $aDataDes.color;
					$(this).find(".pi-gallery-des").css({'text-align': $textAlignDes, color: $colorDes});
				}

				if ( typeof $aDataTitle != 'undefined' )
				{
					
					var $textAlign 	= $aDataTitle.textalign;
					var $color 		= $aDataTitle.color;
					$(this).find(".pi-gallery-title").css({'text-align': $textAlign, color: $color});
				}		
			})
		}

		/* ----------------------------- */
		/* piLightboxWrapper
		/* ----------------------------- */
		function piLightboxWrapper()
		{
			$(".pi_lightbox_wrapper").each(function()
			{
				var $aGalleryData = $(this).data();
				var $ImgBg 		 = $aGalleryData.bgimg;
				var $ImgColor    = $aGalleryData.bgcolor;
				var $BgType 	 = $aGalleryData.bgtype;

				if ( $BgType == 'color' )
				{
					$(this).css({'background-color': $ImgColor});
				}else if ($BgType == 'image' )
				{
					$(this).css({'background': 'url('+$ImgBg+') top left no-repeat'});
				}
			})
		}

		

		/* ----------------------------- */
		/* Magnific
		/* ----------------------------- */
		piInitMagnific();
		function piInitMagnific()
		{
			$('.pi-gallery-lighbox .popup-gallery').magnificPopup(
			{
				delegate: 'a',
				type: 'image',
				tLoading: 'Loading image #%curr%...',
				mainClass: 'mfp-img-mobile',
				gallery: {
					enabled: true,
					navigateByImgClick: true,
					preload: [0,1] // Will preload 0 - before current, and 1 after the current image
				}, 
				image: {
					tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
					titleSrc: function(item) {
						return item.el.attr('title');
					}
				}
			});

			$(".pi-gallery-lighbox  .popup-vimeo, .pi-gallery-lighbox  .popup-googlemap, .pi-gallery-lighbox  .popup-youtube").magnificPopup(
			{
			
				type: 'iframe',
				mainClass: 'mfp-fade',
				removalDelay: 160,
				preloader: true,
				fixedContentPos: false
			})

			var $getId = "";

			
			$(".pi-gallery-lighbox  .popup-ajax").magnificPopup(
			{
				
				type: 'ajax',
				palignTop: true,
				overflowY: 'scroll',
			 	alignTop: false,
	            fixedContentPos: true,
	            fixedBgPos: true,
	            showCloseBtn: false,
	            closeBtnInside: false, 
	            midClick: true,
	            mainClass: 'pi_writting',
			 	ajax:{
			 		settings:
				 	{
				 		// url: 'http://127.0.0.1/pi.wordpress-lastest.com/pi-gallery/post/',
		            	// type: 'POST',
		            	beforeSend: function()
		            	{
		            		// console.log($(this));
		            	},
		            	// data: {action:'pi_lightbox'},
		            	// success:function()
		            	// {
		            	// 	// $(".popup-ajax.active").removeClass("active");
		            	// }
		            }
		        },
		        callbacks:
		        {
		        	elementParse: function(item) 
	                {
	                    item.src = item.el.attr("data-url");
	                },
	                ajaxContentAdded: function() 
	                {
	                	$(".mfp-content").addClass("pi-container");
	                	pi_clear_post();
	                	var autoHeight = $(".owl-demo.pi_owl_one").hasClass(".not-autoheight") ? true : false;
	                	$(".owl-demo.pi_owl_one").owlCarousel(
				      	{
				      		navigation : true,
				      		slideSpeed : 300,
				      		singleItem : true,
				      		paginationSpeed : 400,
				      		autoHeight: true
				      	});
	               	} 
		        }

			})
		}
		

		/* ----------------------------- */
		/* Isotop
		/* ----------------------------- */
		var $piIsotop 		= $(".masonryContainer"), 
			$piFilter 		= $(".pi_filters"),
			$marginbottom   = "";

			// $gutter	   		= typeof $piIsotop.data("gutter") != 'undefined' ? $piIsotop.data("gutter") : 0,
			// $columnWidth 	= typeof $piIsotop.data("columnwidth") != 'undefined' ? $piIsotop.data("columnwidth") : 50;

		$piIsotop.each(function()
		{
		 	$marginBottom = $(this).data("marginbottom");
			$marginBottom = $marginBottom != '' ? $marginBottom + 'px' : '0px';

			$(this).children().css({'margin-bottom': $marginBottom});

			if( $(this).next(".pi-loadmorewrap").length > 0 )
			{
				$(this).next().children().attr("data-marginbottom", $marginBottom);
			}	
		})	

	 	$piIsotop.imagesLoaded(function()
	 	{
	 		$piIsotop.isotope(
	 		{ 
		 		itemSelector: ".pirates",
	 		 	masonry: {
 		 		 	
	 		 	}
			});
	 	}); 

	 	$(window).resize(function()
		{
		   piCaculateSize();
		   $piIsotop.isotope('shuffle');
		})


	 	$piFilter.on( 'click', '.filter', function() 
	 	{	
	 		$(this).siblings().removeClass("active");
	 		$(this).addClass("active");
		  	var filterValue = $(this).attr('data-filter');
		  	$piIsotop.isotope({ filter: filterValue });
		}); 

	 	

		/* ----------------------------- */
		/* Owl
		/* ----------------------------- */ 
      	$(".owl-demo.pi_owl_nav_pag").each(function()
      	{
      		var $setHeight =  $(this).data("height"),
      		 	$items     =  $(this).data("items");
      		 	$autoPlay  =  $(this).data("autoplay");
      			$items     =  $items != '' ? $items : 4;
      			$gutter    =  $(this).data("gutter");

      		$(this).owlCarousel(
      		{
  				navigation : true,
      			items: $items,
      			autoPlay:$autoPlay
      		})

      		if ( $setHeight != '' )
      		{
      			
      			$(this).find(".owl-item, .pirates, img").css({height: $setHeight});
      			$(this).find(".owl-item").css({'margin-right': $gutter});
      		}
      	}); 

      	$(".owl-demo.pi_owl_one").each(function() 
      	{
      		var autoPlay = $(this).data("autoplay");
      		var sliderSpeed = $(this).data("sliderspeed");
      		var pagiSpeed = $(this).data("pagspeed");

      		$(this).owlCarousel(
	      	{
      		 	autoPlay : autoPlay,
	      		navigation : true,
	      		slideSpeed : sliderSpeed,
	      		singleItem :true,
	      		paginationSpeed : pagiSpeed,
      		 	autoHeight : true,
	 	 	 	// transitionStyle : "fade"
	      	});   
      	})

      	// Photo Stack
			
		if ( $(".pi-photostack").length > 0 )
		{
			[].slice.call( document.querySelectorAll( '.pi-photostack' ) ).forEach( function( el ) { new Photostack( el ); } );
		}
      	
      	/* Load More */
      	$(".pi-loadmore").click(function()
      	{
      		pi_loadmore($(this));
      		return false;
      	})
		
		function pi_loadmore($this)
		{
			var getData = $this.parent().data(),
  		 	
  		 	getNumberOfItem = $this.closest(".pi-loadmorewrap").siblings(".masonryContainer").children().length,
  		 	masonryId = $this.closest(".pi_container_mixup").data("id"),
  		 	$isotope = $(masonryId),
  		 	$total = getData.total,
  		 	$showMore = getData.showmore,
  		 	$getMarginBottom = $this.attr("data-marginbottom"),
  		 	$getMarginRight  = $this.attr("data-marginright"),
  		 	$getWidth  	  	 = $this.attr("data-width");
      		
      		$this.prop("disabled", true);
      		
      		$.ajax(
      		{
      			type: "POST",
      			url: PILB_AJAX.ajaxurl,
      			data:{action: 'loadmore', info: getData, currentquantity: getNumberOfItem},
      			beforeSend: function()
      			{

      			},
      			success: function(res)
      			{	
      				// $('#container').append( $newItems ).isotope( 'addItems', $newItems );
      				var $newItems = $(res);
      				
      				// var $getWidth = $isotope.children().attr("style", "width");
      				
      				$isotope.append($newItems).isotope( 'appended', $newItems );
      				var $numberOfItemCurrently =  $isotope.children().length;
      				// $($newItems).each(function()
      				// {
      				// 	$(this).css({width: $getWidth, 'margin-right': $getMarginRight, 'margin-bottom': $getMarginBottom});
      				// })

					
					if ( $(".pi_container_mixup").length > 0 )
					{
						$this.parent().prev().children().each(function()
						{
							$(this).css({width: $getWidth, 'margin-right': $getMarginRight, 'margin-bottom': $getMarginBottom});
							// $(".pirates").css({'margin-right': $getMarginRight, 'margin-bottom': $getMarginBottom});
						})
					}

					// Solved Height
					// var getItemHeight = $("'"+masonryId+" .pirates:nt-child(1)'").outerHeight();

					$this.parent().siblings(".nav_filter").find(".pi_filter_all").trigger('click');
					// console.log($this.parent().siblings(".nav_filter").find("")attr("class"));

					$this.prop("disabled", false);
					
					if (  $total  <= $numberOfItemCurrently  )
					{
						$this.remove();
					}

					piInitMagnific();
      				
      			}
      		});
		}
		// $(".pi_filter_all").click(function(){alert("da")});

      	function pi_clear_post()
      	{
      		$(".hidden-on-edit, .hidden-in-editor, .remove_in_frontend").remove();
			
			if ( $(".pi-unwrap").parent() && $(".pi-unwrap").parent().hasClass("pi_wrap") )
			{
				$(".pi-unwrap").unwrap();
			}

			if( $("p.pi_wrap").children().length == 0)
			{
				$("p.pi_wrap").remove();
			}

			if ( $(".pi-col p").children().length == 0 && !$(".pi-col p").hasClass("is-content")  )
			{
				$(".pi-col p").remove();
			}

			$(".pi-insert_id_here").each(function()
			{
				var $getId="";
				$getId = $(this).data("customid");

				if ( $getId )
				{
					$(this).attr("id", $getId);
				}
			})

			$(".featured-box").each(function()
			{
				console.log($(this).prev().html());
				if ( $(this).closest(".col-md-3").length > 0 )
				{

					if ( $(this).prev().html() == '' )
					{
						$(this).prev().remove(); 
					}

					if ( $(this).next().html() == '' )
					{
						$(this).next().remove();
					}
				}
			})

			$(".pi-insert_class_here").each(function()
			{
				var $getClass="";
				$getClass = $(this).data("customclass");
				
				if ( $getClass )
				{
					$(this).addClass($getClass);
				}
			})
      	}

      	// console.log($.type('draggable'));
      	// Live Preview
      	var $jsLightbox = $("#pi_lighbox_form");
  	 	// console.log($('.pi-gallery-lighbox ').offset().top); 
      	$(".pi-gallery-edit").click(function()
      	{
      		var $this = $(this);

      		var $id = $(this).data("id");
      		var $shortcodes = $(this).data("shortcodes");
      		var $frameWork = '<div id="pi_lighbox_form" class="js_popup md-popup pi-on-frontend">\
								<div class="md-popup-inner">\
									<form class="pi_lightbox" action="" method="POST">\
										<div class="md-popup-inner-header">\
											<h3 class="md-popup-title js_title">Gallery Lightbox</h3>\
											<a href="#close" class="md-popup-close">X</a>\
										</div>\
										<div class="md-popup-inner-content js_insert_content">\
										</div>\
										<div class="md-popup-inner-footer">\
											<a href="#save" class="md-popup-save js_save button button-primary button-large">Save</a>\
											<a href="#preview" class="md-popup-preview js_preview button button-primary button-large">Preview</a>\
										</div>\
									</form>\
								</div>\
							</div>';

			$this.before('<button class="pi-editing"><span class="dashicons dashicons-yes"></span></button>');				
      		// $this.parent().after($frameWork);
      		$this.parent().after($frameWork);
      		$this.addClass("pi-hidden");
      		$("#pi_lighbox_form").draggable({handle: '.md-popup-inner-header, .md-popup-inner-footer'});

      		var $getTop = $("#pi_lighbox_form").parent().offset().top;

      		var $piInitAjax = $.ajax(
      		{
      			type: 'POST',
      			url: PILB_AJAX.ajaxurl,
      			data: {action: 'pi_edit_gallery', postID: $id, shortcodes: $shortcodes},
      			beforeSend: function()
      			{
      				$("#pi_lighbox_form .js_insert_content").html('<div class="js_center center"><img src="'+PILB_AJAX.loading+'preloader.gif"></div>');
      			},
      			success: function(res)
      			{
      				$("#pi_lighbox_form .js_insert_content").html(res);
      				$("#pi_lighbox_form .js_insert_content .js_center").remove();
      				$("#pi_lighbox_form").css({top: $getTop});
      			},
      			complete: function()
      			{

			      
      			}
      		})
      	})

      	$(document).on("click", ".md-popup-close", function()
      	{	
      		$(".pi-editing").remove();
      		// $(this).closest(".pi-gallery-lighbox ").children(".pi-edit").removeClass("pi-hidden");
  		 	$(this).prev().children(".pi-gallery-edit").removeClass("pi-hidden");
      		$("#pi_lighbox_form").remove();
      	})

      	piPopupHandle();
		function piPopupHandle()
		{
			$(document).on("click","#pi_lighbox_form #pi_accordion .hndle", function()
	        {
	            var p = $(this).next();

	           	$(this).toggleClass("closed");
	          
	            p.toggleClass('closed');

	            if ( !p.hasClass('closed')  )
	            {
	                p.show();
	            }
	            else if ( p.hasClass('closed') )
	            {
	             	p.hide();
	            }
	        });

	      	$(document).on("change", "#pi_lighbox_form .pi_change_settings", function()
	        {
	            // var $hideAll = $(".pi_slide");

	            var $data = typeof $(this).find("option:selected").data() != 'undefined' ? $(this).find("option:selected").data()  : '';

	            var $show = typeof $data.show != 'undefined' ? $data.show: '' ;
	            var $hide = typeof $data.hide != 'undefined' ? $data.hide : '';

	            
	            // $hideAll.hide();

	           	if ( $hide )
	            { 
	            	$($hide).hide();
	            }
	            
	            if ( $show )
	            {
	            	$($show).show();
	            }

	            // $(this).closest(".md-row-item").remove();
	        });
		}

      	$( document ).ajaxComplete(function(event, xhr, settings) 
      	{	
      		piWhenAjaxComplete(settings); 
      	})

      	// console.log(window.location.hash);
      	function piWhenAjaxComplete(settings)
      	{
      		var getData = settings.data ? settings.data : "";

      		if ( getData != "" && getData.search("pi_edit_gallery") != -1 )
      		{

      			

      			$("#pi_lighbox_form #pi_accordion .hndle").trigger("click");
      			$("#pi_lighbox_form .pi_change_settings").trigger("change");
      			// $(".pi-not-edit").remove();
      			$(".js_color_picker").wpColorPicker();


      			$("#pi_lighbox_form .js_preview").click(function()
      			{
      				var $currentSlider = $("#pi_lighbox_form .pi_change_settings").find("option:selected").val();

      				var $piPoint = $("#pi_lighbox_form").prev();
      				var $formSetting = $(this).closest(".pi_lightbox");

      				var $wrapperWidth = $formSetting.find("input[name=pi_width_of_wrapper]").val();
      					$wrapperWidth = $wrapperWidth != '' ? $wrapperWidth : '100%';

      				// var $currentSlider
  					// $customClass  = $formSetting.find(".pi_custom_class").
      				//Wrapper
      				var $aData = $formSetting.serializeArray();
     				

     				// reset Width
     				$piPoint.attr("data-width", $wrapperWidth);

     				$("#pi_lighbox_form .pi_lightbox").ajaxSubmit( 
					{
						type: 'POST',
						data: {action: 'parse_shortcode', edit: 1},
						url: PILB_AJAX.ajaxurl,
						success: function(res)
						{
							$piPoint.find(".pi-gallery-header, .pi_lightbox_wrapper").remove();

							$piPoint.append(res);

							piCaculateSize();
							piGalleryHeader();
							piLightboxWrapper();
							piInitMagnific();

							switch ( $currentSlider )
							{
								case 'masory':
									var $piMasonryContainer = $piPoint.find(".masonryContainer");							 		
									$piPoint.find(".pi_filters").on( 'click', '.filter', function() 
								 	{	
								 		$(this).siblings().removeClass("active");
								 		$(this).addClass("active");
									  	var filterValue = $(this).attr('data-filter');
									  	($piPoint.find(".masonryContainer")).isotope({ filter: filterValue });
									}); 

									$(".pi-loadmore").click(function()
							      	{
							      		pi_loadmore($(this));
							      		return false;
							      	})

							      	var $editMarginBottom = $piMasonryContainer.data("marginbottom");
									$editMarginBottom = $editMarginBottom != '' ? $editMarginBottom + 'px' : '0px';

									$piMasonryContainer.children().css({'margin-bottom': $editMarginBottom});

									if( $piMasonryContainer.next(".pi-loadmorewrap").length > 0 )
									{
										$piMasonryContainer.next().children().attr("data-marginbottom", $editMarginBottom);
									}	

									$piMasonryContainer.isotope(
							 		{ 
								 		itemSelector: ".pirates",
							 		 	// layoutMode: 'fitRows',
							 		 	masonry: {
						 		 		 	
							 		 	}
									});

									$(window).resize(function()
									{
									   piCaculateSize();
									   $piMasonryContainer.isotope('shuffle');
									   // $(".attachment-post-thumbnail.wp-post-image").css({"width":"100%"});
									})

									// $piMasonryContainer.isotope('shuffle');

								break;

								case 'one_item':
									var $target = $piPoint.find(".owl-demo.pi_owl_one");
						      		var autoPlay = $target.data("autoplay");
						      		var sliderSpeed = $target.data("sliderspeed");
						      		var pagiSpeed = $target.data("pagspeed");

						      		$target.owlCarousel(
							      	{
						      		 	autoPlay : autoPlay,
							      		navigation : true,
							      		slideSpeed : sliderSpeed,
							      		singleItem :true,
							      		paginationSpeed : pagiSpeed,
						      		 	autoHeight : true,
							 	 	 	// transitionStyle : "fade"
							      	});   
							      	
								break;

								case 'scattered_palaroinds':
									var $getID = $piPoint.find(".pi-photostack").attr("id");
									new Photostack( document.getElementById( $getID ) );
								break;

								case 'menu_slider':
									var $target    =  $piPoint.find(".owl-demo.pi_owl_nav_pag");
						      		var $setHeight =  $target.data("height"),
						      		 	$items     =  $target.data("items");
						      		 	$autoPlay  =  $target.data("autoplay");
						      			$items     =  $items != '' ? $items : 4;
						      			$gutter    =  $target.data("gutter");

						      		$target.owlCarousel(
						      		{
						  				navigation : true,
						      			items: $items,
						      			autoPlay:$autoPlay
						      		})

						      		if ( $setHeight != '' )
						      		{
						      			
						      			$target.find(".owl-item, .pirates, img").css({height: $setHeight});
						      			$target.find(".owl-item").css({'margin-right': $gutter});
						      		}
							      	
								break;	
							}

						} 
					})

      				return false;
      			})
      		}
      	}

      	$(document).on("click", "#pi_lighbox_form .js_save", function()
      	{
      		$("form.pi_lightbox").ajaxSubmit(
      		{
      			url: PILB_AJAX.ajaxurl,
      			type: "POST",
      			data:{action: 'pi_change_gallery_settings'},
      			success: function(res)
      			{
      				if ( res == 'error' )
      				{
      					window.location = "http://mp3.zing.vn/playlist/khongloi-ipirates/IO08OW90.html";
      				}else if (res == 'success')
      				{
  						location.reload(false); 
      				}

      				// setTimeout( function()
      				// {

      				// }, 200);
      				
      			}
      		})

      		return false;
      	})

	})
})(jQuery)
