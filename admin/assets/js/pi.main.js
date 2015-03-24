;(function($, document, window, undefined)
{
	"use strict";

	$(document).ready(function()
	{
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

     	getFlickr();

        var $postID = $("#post_ID").val();
       

        if ( localStorage.getItem("pi_"+$postID) == 0 )
        {
            $(".js_toggle_image").text('Show Image');
            $(".pi-gallery").addClass("hidden");
        }

        $(".js_toggle_image").click(function() 
        {

            if ( $(this).text() == 'Hide Image' )
            {
                localStorage.setItem("pi_"+$postID, 0);
                $(this).text("Show Image");
            }else{
                 localStorage.setItem("pi_"+$postID, 1);
                 $(this).text("Hide Image");
            }
            $(".pi-gallery").toggleClass("hidden");
            return false;
        })

     	$(".js_get_flickr").click( function()
     	{
     		getFlickr(true);
     		return false;
     	})

     	$(".pi_flickr_changesize").change(function()
     	{
     		getFlickr(true);
     	})

     	function getFlickr(getnew)
     	{
			var $oInfo = {};
			var $aImgs = [];

     		$(".pi-flickr").find(".pi_flickr_info").each(function()
     		{	
     		
     			if ( $(this).prop("tagName") != 'INPUT' )
     			{
     				$oInfo[$(this).data("key")] = $(this).find("option:selected").val();
      			}else{
      				$oInfo[$(this).data("key")] =  $(this).val();
      			}
     		})
     		
     		if(  $oInfo.flickr_id  == '')
     		{
     			if ( getnew == true ) 
     			{
     				alert("Oop! You need to enter flickr id");
     			}
     			return;
     		}

     		$oInfo.flickr_limit = $oInfo.flickr_limit == '' ? 4 : $oInfo.flickr_limit;
     		$('#pi-show-image').html("");
     		$('#pi-show-image').jflickrfeed(
     		{
				limit: $oInfo.flickr_limit,
				qstrings: {
					id: $oInfo.flickr_id
				},
				itemTemplate: 
				'<li>' +
					'<img src="{{'+$oInfo.flickr_image_size+'}}" alt="{{title}}" />' +
				'</li>'
			}, function(data)
			{
				$.each(data.items, function(i, v)
				{
					$aImgs[i] = v[$oInfo.flickr_image_size];
				})

				$aImgs = $.grep($aImgs, function( n, i ) 
				{
					return ( typeof n != 'undefined');
				});

			
				var $imgs = $aImgs.join(",");
				$("#flickr_re_data").val($imgs);
			});
     	}

	})

})(jQuery, document, window) 