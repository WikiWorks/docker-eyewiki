<?php

$settings['siteUrl'] = getenv( 'MASSMESSAGE_SITE_URL' );
$settings['user'] = getenv( 'MASSMESSAGE_BOT_USER' );
$settings['password'] = getenv( 'MASSMESSAGE_BOT_PASSWORD' );
if ( !$settings['password'] ) {
	$botPasswordFile = getenv( 'MASSMESSAGE_BOT_PASSWORD_FILE' );
	if ( $botPasswordFile && is_readable( $botPasswordFile ) ) {
		$settings['password'] = rtrim( file_get_contents( $botPasswordFile ) );
	}
}
$settings['spamlistCategory'] = getenv( 'MASSMESSAGE_SPAMLIST_CATEGORY' );
