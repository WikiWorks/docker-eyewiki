<?php

use Addwiki\Mediawiki\Api\Client\Action\ActionApi;
use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Mediawiki\Api\Client\Auth\UserAndPassword;
use Addwiki\Mediawiki\Api\MediawikiFactory;

# Load Composer autoload libraries
require_once( __DIR__ . '/vendor/autoload.php' );

# Verbose mode on
ini_set( 'display_errors', 1 );
error_reporting( E_ALL &~ E_DEPRECATED );

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

# Log in to a wiki
echo "Logging in to a wiki\n";

# Create an authenticated API and services
$auth = new UserAndPassword( $settings['user'], $settings['password'] );
$api = new ActionApi( $settings['siteUrl'] . '/api.php', $auth );

echo "SUCCESS\n";

$services = new MediawikiFactory( $api );

if ( $isTest ) {
	echo "-------------\n! TEST MODE IS ENABLED BY DEFAULT - USING 'Category:TestSpamList' as a recipients list !\n------------\n";
	echo "Make sure you have the following pages on the wiki:\n";
	echo "	- Category:TestSpamList\n";
	echo "	- Project:Test/SpamList/MessageRecipients\n";
	echo "	- Project:Test/SpamList/Message\n";
	echo "\n";
	echo "Use --live parameter to switch to PRODUCTION mode\n";
	echo "\n";
	$settings['spamlistCategory'] = 'Category:TestSpamList';
} else {
	echo "-------------\nLIVE MODE!\n------------\n";
}

echo "Wait 10 seconds before going further.. press CTRL+C to abort";
sleep( 10 );

echo "Getting spam-lists..\n";
try {
	$spamLists = $services->newPageListGetter()->getPageListFromCategoryName(
		$settings['spamlistCategory'], [
			'cmlimit' => 100
		]
	);
} catch ( Exception $e ) {
	die($e->getMessage());
}
$spamListsArray = $spamLists->toArray();

# Build up titles list
$titlesArray = [];
foreach ( $spamListsArray as $spamList ) {
	$titlesArray[] = $spamList->getPageIdentifier()->getTitle()->getText();
}
echo "SUCCESS, found " . count($titlesArray) . " titles\n";

foreach ( $titlesArray as $title ) {

	# Do login each cycle
	echo "Re-logging in to a wiki\n";
	$auth = new UserAndPassword( $settings['user'], $settings['password'] );
	$api = new ActionApi( $settings['siteUrl'] . '/api.php', $auth );
	echo "SUCCESS\n";
	$services = new MediawikiFactory( $api );

	# Strip "Recipients" from end of string, so we're left with "/Message"
	$messagePageName = substr( $title, 0, -10 );
	# Strip "Portal:.../MessageRecipients" from end of string
	$portalName = substr( $title, 7, -18 );
	if ( $isTest ) {
		$portalName = substr( $title, 8, -18 );
	}

	echo "\nProcessing $title";
	echo "\nPortal: $portalName";

	$message = $services->newPageGetter()
		->getFromTitle( $messagePageName )
		->getRevisions()
		->getLatest()
		->getContent()
		->getData();

	$subject = $portalName . ": " . date( 'F' ) . " " . "EyeWiki Update";

	echo "\nSubject: $subject";

	$token = $api->getToken();

	$params = array(
		'spamlist' => $title,
		'subject' => $subject,
		'message' => $message,
		'token' => $token
	);

	$ret = $api->request(
		ActionRequest::simplePost(
			'massmessage',
			$params
		)
	);

	echo "\n";
	var_dump($ret);

	echo "\nSUCCESS: Message sent to $title.\n";

	// sleep for 6 minutes to try and avoid overloading the SMTP server
	if ( !$isTest ) {
		echo "\nSleeping for 6 minutes..";
		sleep( 720 );
	}
}

echo "\nAll done.";
