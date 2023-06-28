$(".portal-title").addClass("panel-heading");
/*$(".portal-list ul").addClass("list-group");
$(".portal-list ul li").addClass("list-group-item");*/


$(".page-EyeWiki_Approve_accounts #user-list li > a.mw-userlink").not(".new").parent().hide();

/* Articles */
$("#articles div.holder").addClass("panel");
$("#articles div#featured-article div.holder").addClass("panel-featured");
$("#articles div#article-of-the-day div.holder").addClass("panel-article-day");
$("#articles div.title").addClass("panel-heading");
$("#articles div.title h2").addClass("panel-title");
$("#articles div.description").addClass("panel-body");

/* Portals */
$(".ns-500 .section-editor, .page-API .section-editor").addClass("jumbotron");

( ( function ( $ ) {

	// Detects Mobile Devices
	var isMobile = {
		Android: function () {
			return navigator.userAgent.match( /Android/i );
		},
		BlackBerry: function () {
			return navigator.userAgent.match( /BlackBerry/i );
		},
		iOS: function () {
			return navigator.userAgent.match( /iPhone|iPad|iPod/i );
		},
		Opera: function () {
			return navigator.userAgent.match( /Opera Mini/i );
		},
		Windows: function () {
			return navigator.userAgent.match( /IEMobile/i );
		},
		any: function () {
			return ( isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows() );
		}
	};

	if ( isMobile.any() ) {

		var mwOldContentsBlock = $( '#mw-content-text' ),
			mwModernContentsBlock = $( '.mw-parser-output' ),
			contentsBlock = mwModernContentsBlock.length ? mwModernContentsBlock : mwOldContentsBlock;

		function makeSectionCollapsible() {
			var $group = $();
			contentsBlock.contents().each( function () {
				if ( this.nodeType === 3 || !$( this ).is( ':header, div, p' ) ) {
					if ( this.nodeType !== 3 || this.nodeValue.trim() ) {
						$group = $group.add( this );
					}
				} else {
					$group.wrapAll( '<p />' );
					$group = $();
				}
			} );
			$group.wrapAll( '<p />' );
			contentsBlock.find( ' > h1' ).each( function () {
				$( this ).nextUntil( 'h1' ).wrapAll( '<div class="h1-section fade in"></div>' );
			} );
			contentsBlock.find( '> h1' ).addClass( 'mobile-heading-icon' ).on( 'click', function ( e ) {
				e.preventDefault();
				$( this ).next( '.h1-section' ).toggleClass( 'in' );
				$( this ).toggleClass( 'in' );
			} );
		}

		function applyBootstrapResponsivenessOnMobile() {
			$( 'img' ).addClass( 'img-responsive' );
			$( '.h1-section' ).removeClass( 'in' );
			$( 'iframe' ).attr( 'width', '100%' ).attr( 'height', '64vmin' ).css( 'width', '100%' ).css( 'height', '64vmin' );
		}

		$( document ).ready( function () {
			makeSectionCollapsible();
			applyBootstrapResponsivenessOnMobile();
			$( '.info-section.horizontal' ).filter( ':even' ).css( 'background-color', 'rgb(251, 251, 251)' );
		} );

	}

	/* Show My Portal sidebar link if it is valid */
	var myPortalHref = $( '#n-My-Portal > a' ).attr( 'href' );
	if ( myPortalHref && myPortalHref.indexOf( 'Portal' ) > 0 ) {
		$( '#n-My-Portal' ).show();
	}
} )( jQuery ) );
