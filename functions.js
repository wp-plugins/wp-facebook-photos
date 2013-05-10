function initFacebook(appId, channelUrl) {
	window.fbAsyncInit = function() {
		FB.init({
			appId: appId,
			channelUrl: channelUrl,
			cookie: true,
			xfbml: false,
			oauth: true
		});
		FB.Event.subscribe('auth.login', function(response) {
        	window.location.reload();
        });
        FB.Event.subscribe('auth.logout', function(response) {
        	window.location.reload();
        });
	};
	(function() {
		var e = document.createElement('script'); e.async = true;
		e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
		document.getElementById('fb-root').appendChild(e);
	}());
}

var $ = jQuery;

$(document).on('click', '.images li', function() {
	var myLoop = $('.images li');
	
	if(event.shiftKey) {
		var theIndex = $(this).index();
		var anySelected = -1;
		
		myLoop.each(function() {
			if($(this).hasClass('selected'))
				anySelected = $(this).index();
		});
		
		if(anySelected != -1) {
			myLoop.each(function() {
				var myIndex = $(this).index();
				if((myIndex >= theIndex && myIndex <= anySelected) || (myIndex <= theIndex && myIndex >= anySelected))
					$(this).addClass('selected');
			});
		}
		else
			$(this).addClass('selected');
	}
	else {
		$(this).toggleClass('selected');
	}
	
	var $newGal = $('.theNewGallery');
	var newGalVal = $newGal.val();
	var temp = new Array();
	
	myLoop.each(function() {
		if($(this).hasClass('selected'))
			temp.push($(this).attr('data-photoid'));
	});
	
	newGalVal = temp.join();
	
	if(newGalVal.charAt(0) == ",")
		newGalVal = newGalVal.substr(1);
	
	$newGal.val(newGalVal);
});

$(document).on('change', '.albumSelector', function() {
	var theValue = $(this).val();
	var accessToken = $('.token').text();
	var $photos = $('.photos');
	
	$('.theNewGallery').val('');
	$('.createGalleryHold').addClass('hidden');
	$photos.fadeOut(250).html("").append('<ul class="images">');
	
	var $images = $photos.find('.images');
	
	var albumFQL = encodeURIComponent('SELECT object_id FROM album WHERE owner = "' + theValue + '" ORDER BY created DESC LIMIT 0,20');
	
	FB.api('/fql?q=' + albumFQL + '&access_token=' + accessToken, function(albums) {
		$.each(albums.data, function(index, value) {
			var photoFQL = encodeURIComponent('SELECT src_height, src_width, object_id, src FROM photo WHERE album_object_id = "' + value.object_id + '" ORDER BY created DESC LIMIT 0,200');
			
			FB.api('/fql?q=' + photoFQL + '&access_token=' + accessToken, function(photos) {
				$.each(photos.data, function(index, value) {
					var ratio = value.src_height/value.src_width;
					
					if(ratio < 1) {
						var marginTop = (130-(ratio*130))/2;
						$images.append("<li data-photoID='" + value.object_id + "'><img src='" + value.src + "' style='margin-top: " + marginTop + "px;' alt='" + value.name + "'></li>");
					}
					else
						$images.append("<li data-photoID='" + value.object_id + "'><img src='" + value.src + "' alt='" + value.name + "'></li>");
				});
			});
		});
	});
	
	$photos.append('</ul>').fadeIn(250, function() {
		$('.createGalleryHold.hidden').removeClass('hidden');
	});
});

var deselect = true;

$(document).on('click', '.selDesel', function() {
	var $newGal = $('.theNewGallery');
	var newGalVal = $newGal.val();
	var temp = new Array();
	
	if(deselect) {
		$('.images li').each(function() {
			deselect = false;
			$(this).addClass('selected');
			temp.push($(this).attr('data-photoid'));
		});
	}
	else {
		$('.images li').each(function() {
			deselect = true;
			$(this).removeClass('selected');
			temp.pop();
		});
	}
	
	newGalVal = temp.join();
	
	if(newGalVal.charAt(0) == ",")
		newGalVal = newGalVal.substr(1);
	
	$newGal.val(newGalVal);
	
	event.preventDefault();
});