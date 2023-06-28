jQuery( document ).ready( function ( $ ) {
	var items = $( ".carousel-inner > div.item" ).length;
	for( var i = 0 ; i < items ; i++ ) {
		$( '<li data-target="#carousel-main" data-slide-to="' + i + '"></li>' ).appendTo( '.carousel-indicators' );
	}
	$( '.item' ).first().addClass( 'active' );
	$( '.carousel-indicators > li' ).first().addClass( 'active' );
});
