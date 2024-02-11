<?php

$wgSitename = "EyeWiki";
#$wgServer = "https://eyewiki.org";
$wgServer = getenv( 'MW_SITE_SERVER' );
$wgLogo = "$wgScriptPath/extensions/EyeWiki/skins/chameleon/EyeWiki_Logo.png";
$wgEnableEmail = true;
$wgEnableUserEmail = true; # UPO
$wgEmergencyContact = "eyewiki@aao.org";
$wgPasswordSender = getenv( 'PASSWORD_SENDER' );
$wgEnotifUserTalk = true; # UPO
$wgEnotifWatchlist = true; # UPO
$wgEmailAuthentication = true;

$wgEnableUploads = true;
$wgUseInstantCommons = false;
$wgLanguageCode = "en";

wfLoadSkin( 'chameleon' );
wfLoadSkin( 'Vector' );
$wgDefaultSkin = "chameleon";

$wgRightsPage = "";
$wgRightsUrl = "";
$wgRightsText = "";
$wgRightsIcon = "";

$wgDiff3 = "";

# DISABLE REAL NAME
$wgHiddenPrefs[] = 'realname';
# Disable all other skins
$wgHiddenPrefs[] = 'skin';

$wgRCMaxAge = 4184000;
$wgShowExceptionDetails = true;
$wgAmericanDates = true;

# Permissions

####### see MEYE-250
// $wgGroupPermissions['reviewer'] = $wgGroupPermissions['user'];
// $wgGroupPermissions['editor'] = $wgGroupPermissions['user'];
// $wgGroupPermissions['user'] = $wgGroupPermissions['*'];
####### Code below is proper replace for the few commented lines above
$wgHooks['UserGetRights'][] = function ( \User $user, array &$aRights ) {
	$services = \MediaWiki\MediaWikiServices::getInstance();
	$groups = $services->getUserGroupManager()->getUserGroups( $user );
	$pm = $services->getPermissionManager();
	if ( in_array( 'reviewer', $groups ) ) {
		$aRights = $pm->getGroupPermissions( array_unique( array_merge( $groups, [ 'user', '*' ] ) ) );
	} elseif ( in_array( 'editor', $groups ) ) {
		$aRights = $pm->getGroupPermissions( array_unique( array_merge( $groups, [ 'user', '*' ] ) ) );
	} elseif ( in_array( 'user', $groups ) ) {
		$groups[] = '*';
		$aRights = $pm->getGroupPermissions( array_diff( $groups, [ 'user' ] ) );
		$aRights[] = 'edit';
	}
};
// Show user rights on Special:ListGroupRights page (it does not grant real rights)
$wgHooks['BeforeInitialize'][] = function () {
	global $wgGroupPermissions;
	$pm = \MediaWiki\MediaWikiServices::getInstance()->getPermissionManager();
	$wgGroupPermissions['reviewer'] = array_fill_keys( $pm->getGroupPermissions( [ 'reviewer', 'user', '*' ] ), true );
	$wgGroupPermissions['editor'] = array_fill_keys( $pm->getGroupPermissions( [ 'editor', 'user', '*' ] ), true );
	$wgGroupPermissions['user'] = array_fill_keys( $pm->getGroupPermissions( [ '*' ] ), true ) + [ 'edit' => true ];
};

#$wgGroupPermissions['*']['createaccount'] = false; #TEMP
$wgGroupPermissions['bureaucrat']['createaccount'] = true; #probably not needed
$wgNamespaceProtection[NS_TEMPLATE] = array( 'editinterface' );
$wgEmailConfirmToEdit = true;

$wgGroupPermissions['reviewer']['editportal'] = true;
$wgGroupPermissions['sysop']['editportal'] = true;
$wgGroupPermissions['sysop']['editsitejs'] = true;
$wgGroupPermissions['sysop']['editsitecss'] = true;

$wgGroupPermissions['*']['edit'] = false;

/*
 * This section restricts anyone other than members of the sysop group 
 * from creating pages in the Main namespace. [MEYE-369]
 */
wfLoadExtension( 'Lockdown' );
$wgNamespacePermissionLockdown[NS_MAIN]['create'] = [ 'sysop' ];

/*
 * This section restricts anyone other than those with the "editotherthanmyuserpage" right
 * from editing any pages other than their own user page.
 */
$wgAvailableRights[] = 'editotherthanmyuserpage';
$wgGroupPermissions['sysop']['editotherthanmyuserpage'] = true;
$wgGroupPermissions['editor']['editotherthanmyuserpage'] = true;
$wgHooks['userCan'][] = function ( Title &$title, User &$user, $action, &$result ) {
	if ( !( $action == 'edit' || $action == 'move' ) ) {
		return true;
	}
	if ( !$user->isAllowed( 'editotherthanmyuserpage' ) ) {
		$userPageTitle = Title::makeTitle( NS_USER, $user->getName() );
		if ( !$title->equals( $userPageTitle ) ) {
			$result = false;
			return false;
		}
	}
};

# Allowed upload file types
$wgFileExtensions = array_merge( $wgFileExtensions,
	[ 'avi', 'ogv', 'mp4', 'm4v', 'webm' ]
);
$wgMaxUploadSize = 1024*1024*200; #200 MB

$wgAllowHTMLEmail = true; # needed for MassMessageEmail
$wgUserEmailUseReplyTo = true;

# Custom namespaces
define( "NS_PORTAL", 500 );
define( "NS_PORTAL_TALK", 501 );
$wgExtraNamespaces[NS_PORTAL] = "Portal";
$wgExtraNamespaces[NS_PORTAL_TALK] = "Portal_talk";
$wgNamespacesWithSubpages[NS_PORTAL] = true;
$wgNamespacesToBeSearchedDefault[NS_PORTAL] = true;
$smwgNamespacesWithSemanticLinks[NS_PORTAL] = true;
$wgNamespaceProtection[NS_PORTAL] = array( 'editportal' );

define( "NS_CONTEST", 502 );
define( "NS_CONTEST_TALK", 503 );
$wgExtraNamespaces[NS_CONTEST] = "Contest";
$wgExtraNamespaces[NS_CONTEST_TALK] = "Contest_talk";
$smwgNamespacesWithSemanticLinks[NS_CONTEST] = true;
$wgContentNamespaces[] = NS_CONTEST; # Needed for PageForms autoedit

$wgContentNamespaces[] = NS_USER; # Needed for PageForms autoedit

/******************* Extensions inclusion *******************/
$extensions = [
#	'ContributorsEyewiki',
	'EyeWiki',
# 	'GoogleAPIClient', #formertly used by GoogleAnalyticsMetrics
	'SemanticDrilldown',
];
foreach ( $extensions as $extension ) {
	require_once( "$IP/extensions/$extension/$extension.php" );
}

$extensionsJSON = [
	'Arrays',
	'Bootstrap',
	'CategoryTree',
	'CategoryWatch',
	'CirrusSearch',
	'Cite',
	'CiteDrawer',
	'CookieWarning',
	'ConfirmEdit',
	'ConfirmEdit/QuestyCaptcha',
#	'ContributionsList',                  NOT COMPATIBLE WITH 1.35
#	'Contributors',
	'DataTransfer',
	'Description2',
	'DismissableSiteNotice',
	'EditAccount',
	'Elastica',
	'ExternalData',
#	'GTag',
	'GoogleAnalyticsMetrics',
	'HitCounters',
	'MassMessage',
	'MassMessageEmail',
	'MetaMaster',
	'MyVariables',
	'NewUserMessage',
	'PageForms',
	'ParserFunctions',
#	'PreferencesMaster',
#	'PreferencesList',
	'Renameuser',
	'ReplaceText',
	'SemanticExtraSpecialProperties',
	'SemanticResultFormats',
	'SemanticTasks',
	'ShowMe',
	'UserExport',
	'VisualEditor',
	'Widgets',
	'YouTube',
	'EyeWiki',
	'UserMerge'
];
wfLoadExtensions( $extensionsJSON );

/******************* Extensions configuration *******************/
$wgPageFormsAutoeditNamespaces[] = NS_CONTEST;
$wgPageFormsAutoeditNamespaces[] = NS_USER;

#CiteDrawer
$wgCiteDrawerTheme = 'dark';
$wgCiteDrawerEnableDesktop = false;

#ConfirmEdit
$wgCaptchaQuestions = [
	'How many total thumbs do 5 people have? (spell out the number)' => 'ten'
];
$wgGroupPermissions['editor']['skipcaptcha'] = true;

# Contributors
$wgContributorsLimit = $wgContributorsThreshold = 0;

# ContributorsEyewiki
//Create new group whose contributions should be hidden.
$wgGroupPermissions['hiddencontributor']['ignorecontributions'] = true;

# Description2
$wgEnableMetaDescriptionFunctions = true;

# EditAccount
$wgGroupPermissions['bureaucrat']['editaccount'] = true;

# GTag
$wgGTagAnalyticsId = 'G-SYN4FP4R7D';

# SemanticMediaWiki
$smwgToolboxBrowseLink = false;
$smwgPageSpecialProperties = array( '_MDAT', '_CDAT' ); //add Special property Creation date
$smwgEnabledQueryDependencyLinksStore = true;

# ParserFunctions
$wgPFEnableStringFunctions = true;

# UserExport
$wgGroupPermissions['bureaucrat']['userexport'] = true;

# VisualEditor
#wfLoadExtension( 'Parsoid', "$IP/vendor/wikimedia/parsoid/extension.json" );
$wgDefaultUserOptions['visualeditor-enable'] = 1; // Enable by default for everybody
$wgVisualEditorDisableForAnons = true; // Except anons (non-logged in users)
$wgVisualEditorNamespaces = array_merge(
	$wgContentNamespaces,
	[ NS_USER ]
);

# CirrusSearch
$wgSearchType = 'CirrusSearch';
$wgCirrusSearchClusters = [
	'default' => [ 'elasticsearch' ],
];
#!$wgDisableSearchUpdate = true;
$wgCirrusSearchPhraseSuggestConfidence = 1.0;
$wgCirrusSearchPhraseSuggestUseText = true;

# CookieWarning
$wgCookieWarningEnabled = true;

# MassMessage - There is a bug in MassMessage that does  not allow it to send messages
# where  wgEmailConfirmToEdit == true
# 20230127 MassMessage has similar code, most likely this hook should be removed
$wgHooks['EmailConfirmed'][] = function ( $user, &$confirmed ) {
	if ( in_array( 'bot', \MediaWiki\MediaWikiServices::getInstance()->getPermissionManager()->getGroupPermissions( $user->getGroups() ) ) ) {
		$confirmed = true;
		return false; // No further check
	}
	return true;
};
$wgGroupPermissions['bot']['massmessage'] = true;

# SemanticTasks
$wgSemanticTasksNotifyIfUnassigned = true;

# SemanticExtraSpecialProperties
$GLOBALS['sespSpecialProperties'] = [ '_USEREDITCNT', '_EUSER' ];
$sespgEnabledPropertyList[] = '_EUSER';
$sespgEnabledPropertyList[] = '_USEREDITCNT';
$sespgExcludeBotEdits = true;
$sespgLabelCacheVersion = '2020.11';

# SemanticMediaWiki
$smwgEnabledQueryDependencyLinksStore = true;

#$egScssCacheType = CACHE_NONE;

// See MEYE-250 and https://www.mediawiki.org/wiki/Manual:$wgSessionCacheType
// If $wgMainCacheType is set to CACHE_ACCEL and this is left at its default CACHE_ANYTHING,
// the cache used may not meet these requirements.
// The solution is to set this to an appropriate cache, such as CACHE_DB.
$wgSessionCacheType = CACHE_DB;
// Increase up to 3 hours (1 hour by default)
$wgObjectCacheSessionExpiry = 10800;

# There was an issue with job queue not running. This is default in MW1.27.2+
$wgRunJobsAsync = false; #changed to true temp

$wgJobRunRate = 1;
$wgJobTypeConf['default']['claimTTL'] = 60*mt_rand(13,17);

$wgMaxShellMemory = 1024*1000*2;
#ini_set('memory_limit', '512MB');
$wgMaxImageArea = 10e7;

wfLoadExtension( 'WikiEditor' );
wfLoadExtension( 'CodeMirror' );
wfLoadExtension( 'RightFunctions' );

# GoogleAnalyticsMetrics
$wgGoogleAnalyticsMetricsAllowed = [ 'pageviews' ];
$wgLocaltimezone = "America/Los_Angeles"; # Pacific, was America/New_York

$wgGroupPermissions['sysop']['usermerge'] = true;
$smwgQMaxInlineLimit = 100000;

wfLoadExtension( 'SemanticDependencyUpdater' );
$wgSDUProperty = 'Semantic Dependency';

$wgGroupPermissions['sysop']['editaccount'] = true;

$stgNotificationFromSystemAddress = true;
$wgSDUUseJobQueue = false;

wfLoadExtension('Variables');

$wgGoogleAnalyticsMetricsExpiry = 3600*24;

$wgWidgetsCompileDir = "$IP/images/widgets/";

# Fixes multiple CVEs https://www.mediawiki.org/wiki/2021-12_security_release/FAQ
$wgActions['mcrundo'] = false;
$wgActions['mcrrestore'] = false;
$wgWhitelistRead = [];
$wgWhitelistReadRegexp = [];

#!wfLoadExtension('Sentry');

require_once("$IP/extensions/EyeWiki/SkinSettings.php");

#### Varnish related settings
$wgUseCdn = true;
$wgCdnServers = [ 'varnish:80' ];
$wgUsePrivateIPs = true;
# Use HTTP protocol for internal connections like PURGE request to Varnish
if ( strncasecmp( $wgServer, 'https://', 8 ) === 0 ) {
	$wgInternalServer = 'http://' . substr( $wgServer, 8 ); // Replaces HTTPS with HTTP
}
# see EC23-1
$wgRightFunctionsAllowCaching = true;

# Adds Universal Analytics snippet
$wgHooks['SkinAfterBottomScripts'][] = function( Skin $skin, &$text ) {
	$text .= <<<EOT
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5QGG6SG');</script>
<!-- End Google Tag Manager -->
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5QGG6SG"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
EOT;

};

#$wgGTagHonorDNT = false;
