<?php

// $egScssCacheType = CACHE_NONE;

$egChameleonExternalStyleModules = [
        __DIR__ . '/skins/chameleon/flatly/_variables.scss' => 'afterFunctions',
        __DIR__ . '/skins/chameleon/flatly/_bootswatch.scss' => 'afterVariables',
        __DIR__ . '/skins/chameleon/fonts.scss' => 'afterMain',
        __DIR__ . '/skins/chameleon/eyewiki.scss' => 'afterMain',
];
$egChameleonLayoutFile = __DIR__ . '/skins/chameleon/standard.xml';
$egChameleonExternalStyleVariables = [
	'container-max-widths' => '(sm: 576px, md: 768px, lg: 992px, xl: 1280px)',
	'cmln-collapse-point' => '992px',
	'font-family-base' => '"Lato","Helvetica Neue",Helvetica,Arial,sans-serif',
	'font-size-base' => '.875rem',
	'headings-font-family' => 'Gotham SSm A',
	'headings-font-weight' => '600',
	'h1-font-size' => '1.625rem',
	'h2-font-size' => '1.375rem',
	'h3-font-size' => '1.125rem',
	'h4-font-size' => '0.875rem',
	'h5-font-size' => '0.75rem',
	'h6-font-size' => '0.75rem',
];

$wgHooks['BeforePageDisplay'][] = function( OutputPage &$out, Skin &$skin ) {
     $code = <<<'START_END_MARKER'
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
<!-- Favicons MEYE-108 -->
<link rel="apple-touch-icon" sizes="120x120" href="/w/extensions/EyeWiki/favicons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/w/extensions/EyeWiki/favicons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/w/extensions/EyeWiki/favicons/favicon-16x16.png">
<link rel="manifest" href="/w/extensions/EyeWiki/favicons/site.webmanifest">
<link rel="mask-icon" href="/w/extensions/EyeWiki/favicons/safari-pinned-tab.svg" color="#603cba">
<link rel="shortcut icon" href="/w/extensions/EyeWiki/favicons/favicon.ico">
<meta name="msapplication-TileColor" content="#603cba">
<meta name="msapplication-config" content="/w/extensions/EyeWiki/favicons/browserconfig.xml">
<meta name="theme-color" content="#ffffff">
START_END_MARKER;

     $out->addHeadItem( 'head-tags-load', $code );
     return true;
};

$wgAllowSiteCSSOnRestrictedPages = true;

// Favicons MEYE-108
$wgFavicon = "/w/extensions/EyeWiki/favicons/favicon.ico";
