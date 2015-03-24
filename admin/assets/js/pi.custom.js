// created by pirateS - wiloke.com - piratesmorefun@gmail.com
;(function($, window, document, undefined)
{
    wp.media = wp.media || {};

    $(document).ready(function()
    {

        "use strict"; 

        var winHeight = $("#wpwrap").height(); 

		var _self, file_frame, teInsertTo= "", mdGallery= "", isMultile=true, liveInsertMe=false, $getData="", $realTime="", $jsUpload = ".js_upload", $caseSpecial="", $jsAddVideo=".js_add_video", $getControl={};
        // console.log(uploadType);
        /* Multiple or single Upload */

        $(document).on("click", $jsUpload, function(event)
        {
            event.preventDefault();

            _self = $(this);
            $getData = _self.data();
            // var $sliderid = _self.closest($jsPane lSetting).data("sliderid");
 
            if ( !$(this).hasClass("multiple") )
            {
                isMultile = false;
            }else{
                isMultile = true; 
            }

            $caseSpecial = typeof $getData.special != 'undefined' ? $getData.special : '';

            /* get id of input that will insert image src or images id to */
            teInsertTo =  $(this).siblings("input[type='text']").length > 0 ? $(this).siblings("input[type='text']") : $(this).siblings("input.pi_pretty_input");
            // console.log(typeof $(this).siblings("input[type='text']"));

            /* Create the media frame */
            file_frame = wp.media.frames.file_frame = wp.media(
            {
                title: jQuery( this ).data( 'uploader_title' ),
                button: {
                    text: jQuery(this).data('uploader_button_title'),
                },
                multiple: isMultile,

            })

            /* When an image or many images is selected, run a callback */
            file_frame.on("select", function()
            {
                var selection = file_frame.state().get('selection');

                switch (isMultile)
                {
                    case true:

                        var ids = [], urls = [], idsurls = [];

                        selection.map(function(attachment)
                        {
                            attachment 	= attachment.toJSON();
                            ids.push(attachment.id);

                            if (typeof attachment.sizes.thumbnail !== 'undefined')
                            {
                                urls.push(attachment.sizes.thumbnail.url);
                            }else{
                                urls.push(attachment.url);
                            }
                        });

                        var oldids = $.map(teInsertTo.val().split(","), function(value)
                        {
                            if (value != "")
                                return parseInt(value, 10);
                        });
                        var $imgs = "", $slider="", $piImages="";
                        for (var j = 0; j < ids.length; j++)
                        {
                            oldids.push(ids[j]);

                            $imgs += '<li class="attachment img-item width-300" data-id="'+ids[j]+'">';
                                $imgs += '<img  src="' + urls[j] + '" />';
                                $imgs += '<a class="pi_remove" href="#">';
                                    $imgs += '<i class="fa fa-times"></i>';
                                $imgs += '</a>';
                            $imgs += '</li>';
                        }


                        teInsertTo.val(oldids);

                        _self.closest(".bg-action")[$getData.func]($getData.target).html($imgs);

                        break;

                    case false:


                            var logoUrl, origionUrl, getId;
                            selection.map(function(attachment)
                            {
                                attachment = attachment.toJSON();
                                getId 	   =  attachment.id;

                                logoUrl = attachment.url;
                       
                                origionUrl = attachment.url;
                            });

                            teInsertTo.val(getId);

                            var img = '<div class="dashicons dashicons-no js_delete_img"></div><img width="260" height="270" src="'+logoUrl+'">';
                            
                            if ($caseSpecial == 'logo')
                            {
                                img = '<span>' + img + '</span>';
                            }

                            _self.parent()[$getData.func]($getData.target)[$getData.method](img);


                        
                        break;
                }
                // $.piPT_Behavior.multi_event.call(_self,"","");
            })

            /* Finally,  open the modal */
            file_frame.open();
        })
    
        $(document).on("click", $jsAddVideo, function(event)
        {
            event.preventDefault();
            var $that = $(this);
            var $url = "",
                $img = "",
                $url = $(this).prev().val();

                $that.html("Adding ...");

                if ( is_youtube($url) )
                {
                    $img = get_background_youtube($url);

                    if ( $img )
                    {
                        $(this).closest(".bg-action").siblings(".pi-gallery").html('<li class="attachment img-item width-300" data-id="">'+$img.image+'</li>');
                        $(this).siblings(".video_type").val("youtube");
                        $(this).siblings(".video_id").val($img.id);
                        $(this).siblings(".video_placeholder").val($img.poster);
                        $that.html("Add Video");
                    }

                }else if( is_vimeo($url) )
                {
                    var id_vimeo,m = $url.match(/^.+vimeo.com\/(.*\/)?([^#\?]*)/),ivalue={},img='', vimeo;
                    id_vimeo = m ? m[2] || m[1] : null;

                    if(id_vimeo!=undefined)
                    {
                        $.ajax(
                        {
                            async: false,
                            dataType : 'json',
                            url: 'http://www.vimeo.com/api/v2/video/' + id_vimeo + '.json?callback=?',
                            success: function(data) 
                            {
                                ivalue={'type':'vimeo','id':id_vimeo,'image':data[0].thumbnail_large};
                            
                                img =  [
                                        '<img width="150" height="150" src="'+ivalue.image+'">',
                                       ];
                                
                                vimeo = ivalue.id;

                                $img = {image:img.join(""), id: vimeo, type: 'vimeo', poster: ivalue.image};

                                $that.closest(".bg-action").siblings(".pi-gallery").html('<li class="attachment img-item width-300" data-id="">'+$img.image+'</li>');
                                $that.siblings(".video_type").val("vimeo");
                                $that.siblings(".video_id").val($img.id);
                                $that.siblings(".video_placeholder").val($img.poster);

                                $that.html("Add Video");
                            },
                            statusCode: {
                                404: function() {
                                    alert("Please check your link");
                                }
                            }
                        })
                    }else alert('Can not get Vimeo ID');
                }else{
                    alert("You must be enter to video link");
                    return null;
                }


                

                function get_background_youtube(url) 
                {
                    var id = url.match("[\\?&]v=([^&#]*)"),ivalue={},img='', data;

                    ivalue={'type':'youtube','id':id[1],'image':'http://i.ytimg.com/vi/'+id[1]+'/hqdefault.jpg'};
                    
                    img = [
                          '<img width="150" height="150" src="'+ivalue['image']+'">',
                          ];
                  

                    data = {image:img.join(""), id: id[1], type: 'youtube', poster: ivalue.image};
                    
                    return data;
                }



                function is_youtube(url)
                {
                    var matches = url.match(/youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)/);
                    if (url.indexOf('youtube.com') > -1)
                        return true;
                    if (url.indexOf('youtu.be') > -1)
                        return true;
                    return false;
                }
                
                function is_vimeo(url)
                {
                    if(url.indexOf('vimeo.com') > -1)
                        return true
                    return false;
                }
        })

        $(".js_popup_contenttype").change(function()
        {
            // var $getControl = "";
                $getControl = $(this).find("option:selected").data();
                // console.log($getControl);
                var $hide = $getControl.hide;
                var $show = $getControl.target;

                $($hide).addClass('pi-hidden');
                $($show).removeClass('pi-hidden hide-if-js');
                
                var $editorheight = $("#postdivrich_wrap").height();
                var $editorPost   = $("#postdivrich_wrap").position().top;
                    
                // $show == '#postdivrich_wrap'
                if ( $show.search("postdivrich_wrap") != -1 )
                {
                    // console.log(-winHeight-800);
                    $(window).scrollTop(150);
                    console.log($editorheight + $editorPost - 300);
                }

        }).trigger("change"); 

        /* Remove Image */

        $(document).on("click", ".pi_remove", function(event)
        {
            // console.log("a");
            event.preventDefault();
          
            var getVal = $(this).closest(".pi_block").find(".pi_pretty_input");
 
            var index = $(this).parent().data("id"),
            oldids = $.map(getVal.val().split(','), function(value)
            {
                if (value != "")
                    return parseInt(value);
            });

            if (oldids.length > 1)
            {
              oldids = pi_removeId(oldids, index);
              getVal.val(oldids.join(","));
            }else{
              getVal.val(""); 
            }
            
            if ($(this).parent().length > 0)
            {
               $(this).parent().fadeOut('slow', function() 
               {
                  $(this).remove();
               });
            }
       
        });
    

        function pi_removeId(arr, id)
        {
            for(var i=0; i<arr.length; i++)
            {
               if (arr[i] == id || typeof arr[i] == id)
               {
                  arr.splice(i, 1);
               }
            }
          
            return arr;
        }
    })       
})(jQuery, window, document);
