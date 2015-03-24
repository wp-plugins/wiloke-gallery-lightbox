// Use full: http://stackoverflow.com/questions/3985524/getting-selected-html-content-in-tinymce-editor
// 


(function($){
	"use strict";
			
 	
	// If the shortcode use image placeholder, the name of image placeholder is matched with id of this shortcode

	$.PILB = $.PILB || {};	
 
	 
	$.PILB = function()
	{ 

		var $defaults =  
		{
			prefix : 'pilb_',
			aimgPlaceholder : [],
			simgPlaceholder : '',
			ainsertImmediately : [],
			buttonID : 'pi_lightbox_button',
			shortcodekey : 'pilb_lightbox',
			imgUrl   : PIGIMGURL
		}  
		
		var $buttons = 
		[
			{
				id: 'lightbox',
				title: 'Attacht Lightbox Gallery To Post',
				extendwp: true,
				simgPlaceholder : '',
				img: 'gallery.png',
				popup: true
			}
		]
		
		this.options = $defaults;
		this.buttons = $buttons;
		this.ed = {};

		this.init();
	}
	
	
	$.PILB.prototype = 
	{
		init: function()
		{
			
			var _self = this,
				$leng = this.buttons.length,
				allowRuning = false;

			$(".md-popup-close").click(function()
			{
				_self.close();
		 	})	
 
			this.save_data();


	   		tinymce.create("tinymce.plugins.pi_lightbox_gallery", 
	  		{
		  		init: function(ed, url)
		  		{
		  			for ( var i = 0; i < $leng ; i++ )
		  		   	{
						
		  		   		if ($leng - 1  == i )
		  		   		{
		  		   			allowRuning = true;
		  		   		}
			  			_self.ed  = ed;
			  		   // add all button into tinymce		  		   	
		  		   		_self.create_button(_self.buttons[i], ed, allowRuning);

		  		   		if ( _self.buttons[i].popup )
		  		   		{
		  		   			_self.options.aimgPlaceholder.push(_self.options.prefix+_self.buttons[i].id);
		  		   		}	
			  		   
			  		   	if ( allowRuning )
			  		   	{
		  		   			_self.setEventHandlers();	
		  		   		}

			  		   	if ( _self.options.aimgPlaceholder.length >0 )
			  		   	{
			  		   		_self.options.simgPlaceholder = _self.options.aimgPlaceholder.join("|");
			  		   	}else{
			  		   		_self.options.simgPlaceholder = "";
			  		   	}
		  		
		  			}     
	  	 		}
		  	});

		  	tinymce.PluginManager.add('pi_lightbox_gallery', tinymce.plugins.pi_lightbox_gallery);
		},
		
		create_button: function(oInfo, ed, allowRuning)
		{
			var _self = this;

			ed.addButton( this.options.prefix + oInfo.id,
			{
				title: oInfo.title,
				image: _self.options.imgUrl + oInfo.img,
				onclick: function()
				{
					_self.dialog(oInfo);
				}
			});
		},

		getTagName: function(co)
		{
			return co.replace( '<br class="clear" />', "&nbsp;");
		},

		replaceTagsWithEntities: function(co)
		{
			return co.replace( '<br class="clear" />', "&nbsp;");
		},

		replaceEntitiesWithTags: function(co)
		{
			return co.replace( '&nbsp;', '<br class="clear" />');
		},
		
		setEventHandlers: function()
		{

			var _self = this;
			var ed = _self.ed;

			if ( _self.options.aimgPlaceholder.length)
			{

				ed.onBeforeSetContent.add(function(ed, o) 
				{
					o.content = _self.replaceShortcodeWithImg(o.content, ed);
					o.content = _self.remove_double_para(o.content, ed);

				});
				
				ed.onExecCommand.add( function(ed, cmd)
				{
					if (cmd === 'mceInsertContent')
					{	
						var $getContent = tinyMCE.activeEditor.getContent();
					
						tinyMCE.activeEditor.setContent( _self.replaceShortcodeWithImg($getContent, ed) );	
					}	

					if ( cmd == 'mceReplaceContent' ) 
					{
						var $getContent = tinyMCE.activeEditor.getContent();
						
						tinyMCE.activeEditor.setContent($getContent);

					}

				});
				
				ed.onPostProcess.add(function(ed, o) 
				{
					
					if (o.get)
					{
						o.content = _self.replaceImgWithShortcode(o.content);
					}
				});

				// edit shortocde

				ed.onInit.add(function(ed)
				{
					_self.editShortcode(ed);
				})

			}
		},

		remove_empty_line: function(co)
		{
			var reg = new RegExp(/(^\s*$)*\n&nbsp;/gm);

			return replace(reg, function(match)
			{
				console.log("match");
				return "&nbsp;";
			})
		},

		remove_p_br_in_colum: function(node)
		{
			var getChild = $(node).find("p");
			if ( getChild.children().length == 0 || (getChild.children().length == 1 && getChild.children().tagName == 'BR') )
			{
				getChild.remove();
			}
		},

		remove_double_para: function(co)
		{
			var reg = new RegExp(/<p>(&nbsp;<br class=[\""]clear[\""] \/?>|<br class=[\""]clear[\""] \/?>|<br \/?>|&nbsp;<br \/?>)*<\/p>(\n?)<p>(&nbsp;<br class=[\""]clear[\""] \/?>|<br class=[\""]clear[\""] \/?>|<br \/?>|&nbsp;<br \/?>)*<\/p>/gmi);

			return co.replace(reg, function(match, removeIt, sub1, sub2)
			{
				return '&nbsp;<br data-mce-bogus="1">';
			});
		},

		remove_old_settings: function(co)
		{
			tinymce.activeEditor.dom.remove(tinyMCE.activeEditor.dom.select('div'), 'pi-remove-it');
		},

		remove_autowrap: function(co)
		{
			var $getP = "";

			($(co).find(".pi-mce-edit")).each(function()
			{
				// console.log("dadad");
				$getP = $(this).find('p');
				
				if ( $getP.length == 1 && $getP.children().prop("tagName") == 'BR' )
				{
					$getP.remove();
				}
			})

			return co;
		},

		generate_shortcode: function()
		{
		},
		
		parse_attr: function(s, n)
		{
			n = new RegExp(n + '=[\"\']([^\"\']+)[\'\"]', 'g').exec(s);
			return n ? n[1] : '';
		},

		convertStringToArray: function($str, $reg)
		{
			return;
		},	

		dialog: function($info, $getData, $edit)
		{ 
			var $piPopup 	= $(".js_popup")
				,$piInsert = $(".js_insert_content");

			$piPopup.removeClass("hidden");
			
			$.ajax(
			{
				type: "POST",
				url: ajaxurl, 
				data:{action: 'hello_ajax', success: 'oke', data: $getData, info: $info, edit: $edit},
				beforeSend: function()
				{
					$piPopup.after('<div class="md-popup-backdrop js_backdrop"></div>');
					$piInsert.html('<div class="js_center center"><img src="'+PIGIMGURL+'preloader.gif"></div>');
				},
				success: function(res)
				{
					$piInsert.html("");
					$piInsert.html(res);
				 	$(".js_color_picker").wpColorPicker();
				 	// $("#pi_accordion").accordion({collapsible: true});

			        $("#pi_accordion .hndle").click(function()
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
			        }).trigger("click");

			        if ( $("#pi_post_id").val() == '' )
			        {
			        	$("#pi_post_id").val($("#post_ID").val());
			        }

			         if ( $("#pi_post_type").val() == '' )
			        {
			        	$("#pi_post_type").val($("#post_type").val());
			        }

		         	$(".pi_change_settings").change(function()
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
			            
			        }).trigger("change");
				}
			});
		},

		close: function()
		{
			
				var $close 	= $(".md-popup-close"),
					$parent = $close.closest(".js_popup");
					$parent.next().remove();
					$parent.addClass("hidden");
					$parent.find(".js_insert_content").html("");
				return false;
			
		},
		
		heartOfPlugin: function()
		{
		
		},
		
		replaceImgWithShortcode: function(co)
		{
			// var reg = new RegExp(/(?:<p[^>]*>)*(<img[^>]+>)(?:<\/p>)*/g);
			var _self = this;

			function getAttrs(s, n)
			{
				n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
				return n ? tinymce.DOM.decode(n[1]) : '';
			};

			function getShortcode(s, n)
			{
				n = new RegExp(n + '=[\"\']([^\"\']+)[\'\"]', 'g').exec(s);
				return n ? tinymce.DOM.decode(n[1]) : '';
			}

			return co.replace(/(?:<p[^>]*>)*(<img[^>]+>)(?:<\/p>)*/g, function(match, img)
			{
			
				var curClass 	= getAttrs(img, 'class');
				var gAttrs      = getAttrs(img, 'data-shortcodes');
				var gNumberID   = getAttrs(img, 'data-numberid');
				// var shortcode   = getShortcode(img, 'data_shortcodeid');
				// return getAttrs(img, 'data-shortcodeid')
			
				if ( curClass.indexOf('pi-edit-shortcode') != -1 )
				{
					return '['+_self.options.shortcodekey+' data_shortcodes=\''+tinymce.trim(gAttrs)+'\' data_numberid=\''+tinymce.trim(gNumberID)+'\']';
				}

				return match;

			})			
		},
		
		parseAfterEdited: function(co, ed)
		{

			var test = new RegExp('<p class="pi_wrap">[^<\/p>]*<\/p>', 'g').exec(co);
			// console.log(test);
		},

		replaceShortcodeWithImg: function(co, ed)
		{

			function getAttrs(s, n)
			{
				n = new RegExp(n + '=\'([^\']+)\'', 'g').exec(s);
				return n ? tinymce.DOM.decode(n[1]) : '';
			};


			var _self = this, reg;
			//  if it's a content editor and the shortcode use image placeholder, this shortcode will be replacing with image placeholder, which has been setting

			reg = new RegExp('\\[('+_self.options.simgPlaceholder+')([^\\]]*)\\]', 'g');
			
			
				// console.log(shortocdeAttrs);

			return co.replace( reg, function(match, shortcode, attr)
			{
			
				var shortocdeAttrs = getAttrs(match, 'data_shortcodes');
				var numberID       = getAttrs(match, 'data_numberid');

				return "<p class='pi_wrap'><img src='"+PIGIMGURL+ 'placeholder/' + shortcode+".jpg' data-mce-placeholder=\'true\' class=\'pi-edit-shortcode data-mce-placeholder \' data-shortcodes=\'"+tinymce.DOM.encode(shortocdeAttrs)+"\' data-command=\'pi-editshortcode\'  data-numberid=\'"+numberID+"\'></p>";
				
			})	
		},

		//  re-open lightbox
		editShortcode: function(ed)
		{
			var _self = this, $oInfo = {};

			ed.onMouseUp.add(function(editor, e)
			{
				var $target, $getAttrs, $getData, $shortcodeId, parseData, $controller="";
				
				$target = e.target;

				if ( $target )
				{
					$controller = $($target).data("command") ? $($target).data("command") : "";

					switch ( $controller )
					{
						
						case 'pi-editshortcode':

							parseData = function(s, n)
							{
								n = new RegExp(n + '=[\"\']([^\"\']+)[\'\"]', 'g').exec(s);
								return n ? tinymce.DOM.decode(n[1]) : '';
							}
							
							if ( !$($target).data("pi-selected") )
							{
								$($target).attr("data-pi-selected", 1);
							}else{
								$getData = $($target).data("shortcodes");

								// get shortcode id  and the shortcode id also match with dialog id
								$shortcodeId = parseData($getData, 'data_shortcodeid');

								if ( !$shortcodeId ) 
								{
									
								}
								
								$shortcodeId = $shortcodeId.replace("pithemes_", "");
								$oInfo.id = $shortcodeId;

								_self.dialog($oInfo, $getData, true);
							}
						break;
					}
				}
		
			})
		},

		save_data: function()
		{
			var $triggerSave = $(".js_save"), $form = $(".pi_lightbox"), _self = this;

				$triggerSave.click(function()
				{
					$form.ajaxSubmit( 
					{
						type: 'POST',
						data: {action: 'parse_shortcode'},
						url: ajaxurl,
						success: function(res)
						{
							tinymce.activeEditor.execCommand('mceInsertContent', false,  res);
							tinymce.execCommand("mceRepaint");
							_self.close();
							$form.resetForm();	
						} 
					})

					return false;
				})
			
		},


		render_html: function($oInfo)
		{
			var $type 		= $oInfo.type ? $oInfo.type : 'single',
				$name 		= $oInfo.name,
				$title  	= $oInfo.title,
				$des 		= $oInfo.des ? $oInfo.des : '',
				$el 		= $oInfo.el ? $oInfo.el : 'input',
				$co 		= $oInfo.co ? $oInfo.co : '',
				$options 	= $oInfo.options ? $oInfo.options : '',
				$reg        = $oInfo.reg ? $oInfo.reg : ",",
				$html 		= '';

			var $containerClass = "";
			var $html = "";


			$html += '<div class="clearfix form-element-container">';
				$html  += '<div class="name-description">';
					$html += '<strong>' + $title + '</strong>';
					$html += '<span>' + $des +  '</span>';
				$html  += '</div>';
				
				if ( $el == 'input' )
				{	
					$html  += '<div class="element-type">';
						$html += '<input class="text-field" type="text"  name="'+$name+'" value="'+$co+'">';
					$html  += '</div>';
				}else if ( $el == 'textarea' )
				{
					$html  += '<div class="element-type container-textarea">';
						$html += '<textarea name="'+$name+'">'+$co+'</textarea>';
					$html  += '</div>';
				}else if ( $el == 'select' )
				{
					$html += '<div class="element-type">';
						$html += '<span class="container-select">';
							$html += '<select class="element-select white" name="enable_tabicon">';
								for ( var i in $options )
								{
									var selected = i == $co ? 'selected' : '';
									$html += '<option value="'+i+'" '+selected+'>'+$options[i]+'</option>';
								}
							$html += '</select>';
						$html += '</span>';
					$html += '</div>';

				}

			$html += '</div>';
			

			return $html;
		}
	}
	

	$(document).ready(function()
	{
		var _initLightbox = new $.PILB();
	})

})(jQuery)	
