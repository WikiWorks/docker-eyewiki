<?php

use Addwiki\Mediawiki\Api\Client\Action\ActionApi;
use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Mediawiki\Api\Client\Auth\UserAndPassword;
use Addwiki\Mediawiki\Api\MediawikiFactory;

# Load Composer autoload libraries
require_once( __DIR__ . '/vendor/autoload.php' );

# Verbose mode on
ini_set( 'display_errors', 1 );
error_reporting( E_ALL  &~ E_DEPRECATED );

# Require private settings
require_once( __DIR__ . '/settings.php' );

# Print date
$date = date( 'm/d/Y h:i:s a', time() );
echo "[" . $date . "] ";

# Check if we're running TEST mode (default) or
$isTest = true;
if ( isset( $argv[1] ) && $argv[1] == '--live' ) {
	# in PRODUCTION mode
	$isTest = false;
}

# Create an authenticated API and services
$auth = new UserAndPassword( $settings['user'], $settings['password'] );
$api = new ActionApi( $settings['siteUrl'] . '/api.php', $auth );
$services = new MediawikiFactory( $api );

$title = 'Project:Stale pages/Notification list';
if ( $isTest ) {
	echo "-------------\n! TEST MODE IS ENABLED - USING 'Project:TestSpamList' as a recipients list !\n------------\n";
	$title = 'Project:TestSpamList';
} else {
	echo "-------------\nLIVE MODE!\n------------\n";
}

echo "Wait 10 seconds before going further.. press CTRL+C to abort";
sleep( 10 );

$message =
	$services->newPageGetter()
		->getFromTitle( 'Project:Stale pages/Message' )
		->getRevisions()
		->getLatest()
		->getContent()
		->getData();

$subject = 'Urgent: 6 month editorial review required';

$api->clearTokens();
$token = $api->getToken();

$params = array(
	'spamlist' => $title,
	'subject' => $subject,
	'message' => $message,
	'token' => $token
);

$api->request(
	ActionRequest::simplePost(
		'massmessage',
		$params
	)
);

echo "SUCCESS: Message sent to $title.\n";
