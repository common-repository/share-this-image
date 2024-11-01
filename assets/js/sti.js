(function($){
    "use strict";

    var selector = sti_vars.selector;

	$.fn.sti = function( options ) {
	
		var opts = $.extend({
			selector: sti_vars.selector,
			title: sti_vars.title,
			summary: sti_vars.summary,
			minWidth: sti_vars.minWidth,
			minHeight: sti_vars.minHeight,
			fb_app: sti_vars.fb_app,
            fb_type: sti_vars.fb_type,
			sharer: sti_vars.sharer,
			is_mobile: sti_vars.is_mobile,
			always_show: sti_vars.always_show,
			primary_menu: sti_vars.primary_menu,
			twitterVia: sti_vars.twitterVia
        }, options );
		
		var methods = {
		
			setStyle: function(e) {
				var output = "",
					value,
					cssStyles = [ 'margin-top', 'margin-bottom', 'margin-left', 'margin-right', 'position', 'top', 'bottom', 'left', 'right', 'float', 'max-width', 'width', 'height' ];
							  
				for ( var i=0;i<cssStyles.length;i++ ) {		
					var style = cssStyles[i];
					
					if ( style === "position" && e.css( style ) === "static" ){ 
						value = "relative";
					}
					else if ( style === "display" && e.css( style ) === "inline" ) {
						value = "inline-block";
					}
					else if ( style === "display" && e.css( style ) === "none" ) {
						return;
					}
					else if ( style === "width" ) {
						value = '' + e.outerWidth() + 'px';
					}
					else if ( style === "height" ) {
						value = '' + e.outerHeight() + 'px';
					}
					else { 
						value = e.css( style );
					}
					
					output += style + ':' + value + ';';
				}
			
				return output;
			},

			createImgHash: function( str ) {				
				var character,
					hash,
					i;
							
				if( !str ) { return ""; }
						
				hash = 0;
						
				if ( str.length === 0 ) { return hash; }
						
				for( i=0;i<str.length;i++ ) {
					character = str[i];
					hash = methods.hashChar( str,character,hash );
				}

                hash = Math.abs( hash ) * 1.1 + "";
						
				return hash.substring(0,5);
						
			},
		
			hashChar: function( str,character,hash ) {				
				hash = ( hash<<5 ) - hash + str.charCodeAt( character );					
				return hash&hash;					
			},
			
			shareButtons: function() {
			
				var buttonsList = '';
			
				for ( var i=0;i<opts.primary_menu.length;i++ ) {
					var network = opts.primary_menu[i];
					buttonsList += '<div class="sti-btn sti-' + network +'-btn" data-network="' + network + '" rel="nofollow"></div>';
				}
				
				return buttonsList;
				
			},
			
			showMobile: function(el) {
			
				var e = $(el);

				if ( e.width() < opts.minWidth || e.height() < opts.minHeight ) return false;
				if ( e.closest('.sti').length > 0 ) return false;
				
				e.addClass('sti_reset');
				e.wrap('<div class="sti style-flat-small sti-mobile" style="' + methods.setStyle(e) + '"></div>');
				e.after('<span class="sti-mobile-btn" style="top:00px;left:00px;"></span>');
				e.after('<span class="sti-share-box" style="top:00px;left:00px;">' + methods.shareButtons() + '</span>');
			
			},
			
			showShare: function(el) {
			
				var e = $(el);

				if ( e.width() < opts.minWidth || e.height() < opts.minHeight ) return false;
				if ( e.closest('.sti').length > 0 ) return false;
				
				e.addClass('sti_reset');
				e.wrap('<div class="sti style-flat-small" style="' + methods.setStyle(e) + '"></div>');
				e.after('<div class="sti-share-box" style="top:00px;left:00px;">' + methods.shareButtons() + '</div>');
					
			},
			
			hideShare: function(el) {
			
				var e = $(el);
	
				e.find('.sti-share-box').remove();
				e.find('.sti_reset').unwrap().removeClass('sti_reset');
				
			},

            replaceVariables: function( data, sstring ) {
                return sstring.replace('{{image_link}}', data.media)
                      .replace('{{page_link}}', data.link)
                      .replace('{{title}}', data.title)
                      .replace('{{summary}}', data.summary);
            },

			windowSize: function( network ) {
			
				switch( network ) { 			
					case "facebook" : return "width=670,height=320";
					break;
					
					case "twitter" : return "width=626,height=252";
					break;
					
					case "google" : return "width=520,height=550";
					break;
					
					case "linkedin" : return "width=620,height=450";
					break;
					
					case "delicious" : return "width=800,height=600";
					break;
					
					default: return "width=800,height=350";
					
				}	
				
			},
			
			replaceChars: function(string) {
				return string.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
			},

			shareData: function(el, network) {
			
				var data    = {},
					e       =  $(el).closest('.sti').find('.sti_reset');
					
				data.w_size   =  methods.windowSize( network );	
				data.media    =  e.data('media') ? e.data('media') : e[0].src;
				data.hash     =  '';
                data.title    =  e.data('title') ? e.data('title') : ( e.attr('title') ? e.attr('title') : ( opts.title ? opts.title : document.title ) );
                data.summary  =  e.data('summary') ? e.data('summary') : ( e.attr('alt') ? e.attr('alt') : ( opts.summary ? opts.summary : '' ) );
				data.local    =  location.href.replace(/\?img.*$/, '').replace(/\&img.*$/, '').replace(/#.*$/, '');
				data.schar    =  ( data.local.indexOf("?") != -1 ) ? '&' : '?';
				data.ssl      =  data.media.indexOf('https://') >= 0 ? '&ssl=true' : '';
				data.link     =  e.data('url') ? e.data('url') : data.local + data.hash;
				data.page     =  opts.sharer ? opts.sharer + '?url=' + encodeURIComponent(data.link) + '&img=' + data.media.replace(/^(http?|https):\/\//,'') + '&title=' + encodeURIComponent(methods.replaceChars(data.title)) + '&desc=' + encodeURIComponent(methods.replaceChars(data.summary)) + '&network=' + network + data.ssl + data.hash :
											   data.local + data.schar + 'img=' + data.media.replace(/^(http?|https):\/\//,'') + '&title=' + encodeURIComponent(methods.replaceChars(data.title)) + '&desc=' + encodeURIComponent(methods.replaceChars(data.summary)) + '&network=' + network + data.ssl + data.hash;

                return data;

			},
			
			share: function(network, data) {	
				
				var url = '';
					
				switch( network ) {
				
					case "facebook" :
						if ( opts.fb_app && opts.fb_type === 'feed' ) {
                            url += 'https://www.facebook.com/dialog/feed?';
                            url += 'app_id=' + opts.fb_app;
                            url += '&display=popup';
                            url += '&link=' + encodeURIComponent(data.link);
                            url += '&picture=' + encodeURIComponent(data.media);
                            url += '&name=' + encodeURIComponent(data.title);
                            url += '&description=' + encodeURIComponent(data.summary);
                            url += '&redirect_uri=' + encodeURIComponent(data.local+data.schar+'close=1');
						}
                        else if ( opts.fb_app && opts.fb_type === 'share' ) {
                            url += 'https://www.facebook.com/dialog/share?';
                            url += 'app_id=' + opts.fb_app;
                            url += '&display=popup';
                            url += '&href=' + encodeURIComponent(data.page);
                            url += '&redirect_uri=' + encodeURIComponent(data.local+data.schar+'close=1');
                        }
                        else {
                            url += 'http://www.facebook.com/sharer.php?s=100';
                            url += '&p[url]=' + encodeURIComponent(data.page);
						}
					break;	

					case "google" :
						url += 'https://plus.google.com/share?';
						url += 'url=' + encodeURIComponent(data.page);
					break;
					
					case "linkedin" :
						url += 'http://www.linkedin.com/shareArticle?mini=true';
						url += '&url=' + encodeURIComponent(data.page);
					break;		
					
					case "vkontakte" :
						url += 'http://vk.com/share.php?';
						url += 'url=' + encodeURIComponent(data.link);
						url += '&title=' + encodeURIComponent(data.title);
						url += '&description=' + encodeURIComponent(data.summary);
						url += '&image=' + encodeURIComponent(data.media);
						url += '&noparse=true';
					break;
					
					case "odnoklassniki" :
						url += 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1';
						url += '&st.comments=' + encodeURIComponent(data.title);
						url += '&st._surl=' + encodeURIComponent(data.page);
					break;
					
					case "twitter" :
						url += 'https://twitter.com/intent/tweet?';
						url += 'text=' + encodeURIComponent(data.title);
						url += '&url=' + encodeURIComponent(data.page);
						if (opts.twitterVia) {
						url += '&via=' + opts.twitterVia;
						}
					break;

					case "pinterest" :
						url += 'http://pinterest.com/pin/create/button/?';
						url += 'url=' + encodeURIComponent(data.link);
						url += '&media=' + encodeURIComponent(data.media);
						url += '&description=' + encodeURIComponent(data.title);
					break;	
					
					case "tumblr" :
						url += 'http://www.tumblr.com/share/photo?';
						url += 'source=' + encodeURIComponent(data.media);
						url += '&caption=' + encodeURIComponent(data.summary);
						url += '&click_thru=' + encodeURIComponent(data.link);
					break;	
					
					case "reddit" :
						url += 'http://reddit.com/submit?';
						url += 'url=' + encodeURIComponent(data.link);
						url += '&title=' + encodeURIComponent(data.title);
						url += '&text=' + encodeURIComponent(data.summary);
					break;	
					
					case "digg" :
						url += 'http://digg.com/submit?phase=2&';
						url += 'url=' + encodeURIComponent(data.link);
						url += '&title=' + encodeURIComponent(data.title);
						url += '&bodytext=' + encodeURIComponent(data.summary);
					break;
					
					case "delicious" :
						url += 'http://delicious.com/post?';
						url += 'url=' + encodeURIComponent(data.link);
						url += '&title=' + encodeURIComponent(data.title);
					break;
					
				}
				
				methods.openPopup(url, data.w_size);
				
			},
			
			openPopup: function(url, w_size) {		
				window.open( url, 'Share This Image', w_size + ',status=0,toolbar=0,menubar=0,scrollbars=1' );
			}
			
		};
		
		if ( !opts.is_mobile ) {
			
			if ( opts.always_show ) {
			
				this.each(function() {		
					methods.showShare(this);
				});
			
			} else {
			
				$(document).on('mouseenter', opts.selector, function(e) {
					e.preventDefault();
					methods.showShare(this);
				});
					
				$(document).on('mouseleave', '.sti', function(e) {
					e.preventDefault();
					methods.hideShare(this);
				});
				
			}
		
		} else {
		
			this.each(function() {		
				methods.showMobile(this);
			});
			
			$('.sti-mobile-btn').on('click', function(e) {
				e.preventDefault();
				$(this).closest('.sti').addClass('sti-mobile-show');
			});
			
			$(opts.selector).on('click', function(e) {
				$(this).closest('.sti').removeClass('sti-mobile-show');
			});
		
		}
		
		$(document).on('click', '.sti-btn', function(e) {
			e.preventDefault();
            e.stopPropagation();
			
			var network = $(this).data('network');
            var data = methods.shareData(this, network);

            methods.share(network, data);

        });
	
	};

    // Call plugin method
    $(window).load(function() {
        $(selector).sti();
    });

})( jQuery );