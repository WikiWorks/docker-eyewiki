<?php

class EyeWiki {

	public static function init() {
		global $wgScriptPath, $egChameleonExternalStyleModules, $egChameleonLayoutFile;
		/******************* Skin configuration *******************/
		# Chameleon
//		$egChameleonExternalStyleModules = [
//			__DIR__ . '/skins/chameleon/flatly/_variables.scss' => 'afterFunctions',
//			__DIR__ . '/skins/chameleon/flatly/_bootswatch.scss' => 'afterMain',
//			__DIR__ . '/skins/chameleon/eyewiki.scss' => 'afterMain',
//		];
//		$egScssCacheType = CACHE_NONE;
//		$egChameleonLayoutFile = __DIR__ . '/skins/chameleon/standard.xml';
//		$egChameleonExternalStyleVariables = [
//			'font-family-base' => '"Lato","Helvetica Neue",Helvetica,Arial,sans-serif',
//			'headings-font-family' => 'GothamPro',
//		];
		/*********************************************************/
	}

	public static function onBeforePageDisplay( OutputPage &$out, Skin &$skin ) {
		if ( $skin->getUser()->isAnon() ) {
			$content = '0';
		} else {
			$content = '1';
		}
		$out->addMeta( 'WT.seg_1', $content );

		$out->addMeta( 'google-site-verification',
			'mtQvMIGqpWB4oyYM5mux15MHU-3qWI0bWtyRa2b5rlk' );

		$out->addModuleStyles( 'ext.eyeWiki' );
		$out->addModules( 'ext.eyeWiki' );
		//$out->addModuleScripts( 'ext.eyeWiki.webtrends' );
		$out->addModules( 'ext.eyeWiki.slideshow' );
		//$out->addHtml( self::getSmartSource() );

		$out->addHeadItem('script1', self::getHeadScript() );

//		// For Chameleon skin
//		global $wgDefaultSkin;
//		if ( $wgDefaultSkin == "chameleon" ) {
//			// Add basic MediaWiki styling. See Resources.php.
//			// $out->addModuleStyles( 'mediawiki.skinning.elements' ); // Disabled since currently this is too overbearing
//		}
	}

	private static function getHeadScript()
	{
		return <<<END
<script> window.interdeal = { "sitekey": "81c701489fb8bb4ab90ca1cb0931f9c5", "Position": "Left", "Menulang": "EN", "domains": { "js": "https://cdn.equalweb.com/", "acc": "https://access.equalweb.com/" }, "btnStyle": { "vPosition": [ "80%", null ], "scale": [ "0.8", "0.8" ], "color": { "main": "#1876c9" }, "icon": { "type": 1, "outline": false } } }; (function(doc, head, body){ var coreCall = doc.createElement('script'); coreCall.src = 'https://cdn.equalweb.com/core/4.3.8/accessibility.js'; coreCall.defer = true; coreCall.integrity = 'sha512-u6i35wNTfRZXp0KDwSb3TntaIKI2ItCt/H/KcYIsBeVbaVMerEQLBnU5/ztfBg9aSW1gg7AN4CqCu9a455jkUg=='; coreCall.crossOrigin = 'anonymous'; coreCall.setAttribute('data-cfasync', true ); body? body.appendChild(coreCall) : head.appendChild(coreCall); })(document, document.head, document.body); </script>
END;
	}

	private static function getSmartSource() {
		return <<<END
<!-- START OF SmartSource Data Collector TAG -->
<!-- Copyright (c) 1996-2010 WebTrends Inc.  All rights reserved. -->
<!-- Version: 8.6.2 -->
<!-- Tag Builder Version: 3.0  -->
<!-- Created: 6/28/2010 9:08:10 PM -->
<!-- ----------------------------------------------------------------------------------- -->
<!-- Warning: The two script blocks below must remain inline. Moving them to an external -->
<!-- JavaScript include file can cause serious problems with cross-domain tracking.      -->
<!-- ----------------------------------------------------------------------------------- -->
<script type="text/javascript">
//<![CDATA[
var _tag=new WebTrends();
_tag.dcsGetId();
//]]>>
</script>
<script type="text/javascript">
//<![CDATA[
// Add custom parameters here.
//_tag.DCSext.param_name=param_value;
_tag.dcsCollect();
//]]>>
</script>
<noscript>
<div><img alt="DCSIMG" id="DCSIMG" width="1" height="1" src="http://wtcollecting.aao.org/dcshdq4k100000s9uz8l4n21p_5t7x/njs.gif?dcsuri=/nojavascript&amp;WT.js=No&amp;WT.tv=8.6.2"/></div>
</noscript>
<!-- END OF SmartSource Data Collector TAG -->

END;
	}

	public static function getAdManagerCode() {
		return <<<END
	<!-- Begin -->
	<script language="javascript"  type="text/javascript">
	<!--
	var browName = navigator.appName;
	var SiteID = 1;
	var ZoneID = $1;
	var browDateTime = (new Date()).getTime();
	if ( browName=='Netscape' ) {
		document.write('<s'+'cript lang' + 'uage="jav' + 'ascript" src="http://aaoads.aao.org/banmanpro/a.aspx?ZoneID=' + ZoneID + '&amp;Task=Get&amp;IFR=False&amp;Browser=NETSCAPE4&amp;SiteID=' + SiteID + '&amp;Random=' + browDateTime  + '">'); document.write('</'+'scr'+'ipt>');
	}
	if ( browName != 'Netscape' ) {
		document.write('<s'+'cript lang' + 'uage="jav' + 'ascript" src="http://aaoads.aao.org/banmanpro/a.aspx?ZoneID=' + ZoneID + '&amp;Task=Get&amp;IFR=False&amp;SiteID=' + SiteID + '&amp;Random=' + browDateTime  + '">'); document.write('</'+'scr'+'ipt>');
	}
	// -->
	</script>
	<noscript>
		<a href="http://aaoads.aao.org/banmanpro/a.aspx?ZoneID=$1&amp;Task=Click&amp;Mode=HTML&amp;SiteID=1" target="_blank">
		<img src="http://aaoads.aao.org/banmanpro/a.aspx?ZoneID=$1&amp;Task=Get&amp;Mode=HTML&amp;SiteID=1" border="0"  alt=""></a>
	</noscript>
	<!-- End  -->
END;
	}
}
