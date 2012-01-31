<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');


define('CUSTOMIZE', '0.1.0');

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['generatePage'][] = array('Customize', 'extendPage');

$GLOBALS['TL_HOOKS']['getCombinedFile'][] = array('Minify', 'extendCombinedFile');

$GLOBALS['JQUERY_VERSIONS'] = array(
	'1.3' => array(
		'1.3.2'
	),
	'1.4' => array(
		'1.4.0', '1.4.1', '1.4.2', '1.4.3', '1.4.4',
	),
	'1.5' => array(
		'1.5.0', '1.5.1', '1.5.2'
	),
	'1.6' => array(
		'1.6.0', '1.6.1', '1.6.2', '1.6.3', '1.6.4'
	),
	'1.7' => array(
		'1.7.0', '1.7.1'
	),
);

$GLOBALS['JQUERYUI_VERSIONS'] = array(
	'1.7' => array(
		'1.7.0', '1.7.1', '1.7.2', '1.7.3',
	),
	'1.8' => array(
		'1.8.0', '1.8.1', '1.8.2', '1.8.4', '1.8.5', '1.8.6', '1.8.7', '1.8.8', 
		'1.8.9', '1.8.10', '1.8.11', '1.8.12', '1.8.13', '1.8.14', '1.8.15', 
		'1.8.16', '1.8.17',
	),
);

$GLOBALS['JQUERYUI_THEMES'] = array(
	'base',
	'black-tie',
	'blitzer',
	'cupertino',
	'dark-hive',
	'dot-luv',
	'eggplant',
	'excite-bike',
	'flick',
	'hot-sneaks',
	'humanity',
	'le-frog',
	'mint-choc',
	'overcast',
	'pepper-grinder',
	'redmond',
	'smoothness',
	'south-street',
	'start',
	'sunny',
	'swanky-purse',
	'trontastic',
	'ui-darkness',
	'ui-lightness',
	'vader'
);