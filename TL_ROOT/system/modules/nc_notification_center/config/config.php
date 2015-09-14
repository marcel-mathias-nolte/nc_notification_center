<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package   NC Notification Center
 * @author    Marcel Mathias Nolte
 * @copyright Marcel Mathias Nolte 2015
 * @website	  https://www.noltecomputer.com
 * @license   <marcel.nolte@noltecomputer.de> wrote this file. As long as you retain this notice you
 *            can do whatever you want with this stuff. If we meet some day, and you think this stuff 
 *            is worth it, you can buy me a beer in return. Meanwhile you can provide a link to my
 *            homepage, if you want, or send me a postcard. Be creative! Marcel Mathias Nolte
 */


/**
 * Back end modules
 */
if (!isset($GLOBALS['BE_MOD']['messages'])) {
	array_insert($GLOBALS['BE_MOD'], 2, array('messages' => array()));
}
array_insert($GLOBALS['BE_MOD']['messages'], 0, array('ncNotificationCenter' => array(
	'tables' => array('tl_nc_notifications'),
	'icon' => 'system/modules/nc_notification_center/assets/notification.png'
)));


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['createNewUser'][]                     = array('ModuleNcNotificationCenterHelper', 'userCreate');
$GLOBALS['TL_HOOKS']['activateAccount'][]                   = array('ModuleNcNotificationCenterHelper', 'userActivate');
$GLOBALS['TL_HOOKS']['closeAccount'][]                      = array('ModuleNcNotificationCenterHelper', 'userClose');
$GLOBALS['TL_HOOKS']['updatePersonalData'][]                = array('ModuleNcNotificationCenterHelper', 'userChanged');
$GLOBALS['TL_HOOKS']['getNotificationLabel']['tl_member:1'] = array('ModuleNcNotificationCenterHelper', 'getMemberCreateNotificationLabel');
$GLOBALS['TL_HOOKS']['getNotificationLabel']['tl_member:2'] = array('ModuleNcNotificationCenterHelper', 'getMemberActivateNotificationLabel');
$GLOBALS['TL_HOOKS']['getNotificationLabel']['tl_member:3'] = array('ModuleNcNotificationCenterHelper', 'getMemberCloseNotificationLabel');
$GLOBALS['TL_HOOKS']['getNotificationLabel']['tl_member:4'] = array('ModuleNcNotificationCenterHelper', 'getMemberChangedNotificationLabel');