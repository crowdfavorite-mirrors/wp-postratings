var post_id=0;var post_rating=0;var is_being_rated=false;ratingsL10n.custom=parseInt(ratingsL10n.custom);ratingsL10n.max=parseInt(ratingsL10n.max);ratingsL10n.show_loading=parseInt(ratingsL10n.show_loading);ratingsL10n.show_fading=parseInt(ratingsL10n.show_fading);function current_rating(id,rating,rating_text){if(!is_being_rated){post_id=id;post_rating=rating;if(ratingsL10n.custom&&ratingsL10n.max==2){document.images["rating_"+post_id+"_"+rating].src=eval("ratings_"+rating+"_mouseover_image.src")}else{for(i=1;i<=rating;i++){if(ratingsL10n.custom){document.images["rating_"+post_id+"_"+i].src=eval("ratings_"+i+"_mouseover_image.src")}else{document.images["rating_"+post_id+"_"+i].src=eval("ratings_mouseover_image.src")}}}if(jQuery("#ratings_"+post_id+"_text").length){jQuery("#ratings_"+post_id+"_text").show();jQuery("#ratings_"+post_id+"_text").html(rating_text)}}}function ratings_off(b,c,a){if(!is_being_rated){for(i=1;i<=ratingsL10n.max;i++){if(i<=b){if(ratingsL10n.custom){document.images["rating_"+post_id+"_"+i].src=ratingsL10n.plugin_url+"/images/"+ratingsL10n.image+"/rating_"+i+"_on."+ratingsL10n.image_ext}else{document.images["rating_"+post_id+"_"+i].src=ratingsL10n.plugin_url+"/images/"+ratingsL10n.image+"/rating_on."+ratingsL10n.image_ext}}else{if(i==c){if(ratingsL10n.custom){document.images["rating_"+post_id+"_"+i].src=ratingsL10n.plugin_url+"/images/"+ratingsL10n.image+"/rating_"+i+"_half"+(a?"-rtl":"")+"."+ratingsL10n.image_ext}else{document.images["rating_"+post_id+"_"+i].src=ratingsL10n.plugin_url+"/images/"+ratingsL10n.image+"/rating_half"+(a?"-rtl":"")+"."+ratingsL10n.image_ext}}else{if(ratingsL10n.custom){document.images["rating_"+post_id+"_"+i].src=ratingsL10n.plugin_url+"/images/"+ratingsL10n.image+"/rating_"+i+"_off."+ratingsL10n.image_ext}else{document.images["rating_"+post_id+"_"+i].src=ratingsL10n.plugin_url+"/images/"+ratingsL10n.image+"/rating_off."+ratingsL10n.image_ext}}}}if(jQuery("#ratings_"+post_id+"_text").length){jQuery("#ratings_"+post_id+"_text").hide();jQuery("#ratings_"+post_id+"_text").empty()}}}function set_is_being_rated(a){is_being_rated=a}function rate_post_success(a){if(ratingsL10n.show_loading){jQuery("#post-ratings-"+post_id+"-loading").hide()}if(ratingsL10n.show_fading){jQuery("#post-ratings-"+post_id).fadeIn("def",function(){set_is_being_rated(false)})}else{jQuery("#post-ratings-"+post_id).show()}jQuery("#post-ratings-"+post_id).html(a)}function rate_post(){if(!is_being_rated){set_is_being_rated(true);if(ratingsL10n.show_fading){jQuery("#post-ratings-"+post_id).fadeOut("def",function(){if(ratingsL10n.show_loading){jQuery("#post-ratings-"+post_id+"-loading").show()}jQuery.ajax({type:"GET",url:ratingsL10n.ajax_url,data:"pid="+post_id+"&rate="+post_rating,cache:false,success:rate_post_success})})}else{jQuery("#post-ratings-"+post_id).hide();if(ratingsL10n.show_loading){jQuery("#post-ratings-"+post_id+"-loading").show()}jQuery.ajax({type:"GET",url:ratingsL10n.ajax_url,data:"pid="+post_id+"&rate="+post_rating,cache:false,success:rate_post_success})}}else{alert(ratingsL10n.text_wait)}};