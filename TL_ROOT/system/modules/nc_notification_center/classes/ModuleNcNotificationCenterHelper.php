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

namespace NC;


/**
 * Helper class for hook callbacks.
 *
 * @package   NC Contact Form
 * @author    Marcel Mathias Nolte
 * @copyright Marcel Mathias Nolte 2015
 */
class ModuleNcNotificationCenterHelper extends \Backend
{
	
	protected static $objInstance = false;
	
	
	/**
	 * Get a singleton instance
	 * @return NC\ModuleNcNotificationCenterHelper
	 */
	public static function getInstance()
	{
		if (self::$objInstance === false) {
			self::$objInstance = new ModuleNcNotificationCenterHelper();
		}
		return self::$objInstance;
	}
	
	
	/**
	 * Get the amount of notifications
	 * @return string
	 */
	public function getNotificationCount()
	{
		return $this->Database->prepare("SELECT COUNT(*) as count FROM tl_nc_notifications")->execute()->next()->count;
	}
	

	/**
	 * Add notification after a new user was created
	 * @param integer
	 * @param array
	 * @param object 
	 */
	public function userCreate($intId, $arrData, $objModule)
	{
		$this->Database->prepare("INSERT INTO tl_nc_notifications (tstamp, sid, source, href) VALUES (?, ?, ?, ?)")->execute(time(), $intId, 'tl_member:1', 'main.php?do=member&act=show&id=' . $intId);
	}
	
	
	/**
	 * Add notification after a user was activated
	 * @param object
	 * @param object 
	 */
	public function userActivate($objUser, \ModuleRegistration $objRegistration)
	{
		$arrData = $objUser[0]->row();
		$this->Database->prepare("DELETE FROM tl_nc_notifications WHERE source=? AND sid=?")->execute('tl_member:1', $arrData['id']);
		$this->Database->prepare("INSERT INTO tl_nc_notifications (tstamp, sid, source, href) VALUES (?, ?, ?, ?)")->execute(time(), $arrData['id'], 'tl_member:2', 'main.php?do=member&act=show&id=' . $arrData['id']);
	}
	
	
	/**
	 * Add notification after a user was deleted
	 * @param object
	 * @param object 
	 */
	public function userClose($intId, $strMode, $objModule)
	{
    	if ($strMode == 'close_delete')
    	{
			$this->Database->prepare("INSERT INTO tl_nc_notifications (tstamp, sid, source, href) VALUES (?, ?, ?, ?)")->execute(time(), $intId, 'tl_member:3', '');
	   	} else {
			$this->Database->prepare("INSERT INTO tl_nc_notifications (tstamp, sid, source, href) VALUES (?, ?, ?, ?)")->execute(time(), $intId, 'tl_member:3', 'main.php?do=member&act=show&id=' . $intId);
	   	}
	}
	
	
	/**
	 * Add notification after a user was changed
	 * @param object
	 * @param array
	 * @param object 
	 */
	public function userChanged($objUser, $arrData, $objModule)
	{
		$this->Database->prepare("INSERT INTO tl_nc_notifications (tstamp, sid, source, href) VALUES (?, ?, ?, ?)")->execute(time(), $objUser->id, 'tl_member:4', 'main.php?do=member&act=show&id=' . $objUser->id);
	}
	
	
	/**
	 * Add notification label after a user was created
	 * @param int
	 */
	public function getMemberCreateNotificationLabel($intId)
	{
		$objResult = $this->Database->prepare("SELECT * FROM tl_member WHERE id=?")->execute($intId);
		if ($objResult->next()) {
			$token = array(
				'###date###' => date($GLOBALS['TL_CONFIG']['datimFormat'], $objResult->tstamp)
			);
			foreach ($objResult->row() as $key => $value) {
				$token['###' . $key . '###'] = $value;
			}
			return strtr($GLOBALS['TL_LANG']['MSC']['NOTIFICATION']['ncNotificationCenter']['userCreated'], $token);
		}
		return '--- item not found ---';
	}
	
	
	/**
	 * Add notification label after a user was activated
	 * @param int
	 */
	public function getMemberActivateNotificationLabel($intId)
	{
		$objResult = $this->Database->prepare("SELECT * FROM tl_member WHERE id=?")->execute($intId);
		if ($objResult->next()) {
			$token = array(
				'###date###' => date($GLOBALS['TL_CONFIG']['datimFormat'], $objResult->tstamp)
			);
			foreach ($objResult->row() as $key => $value) {
				$token['###' . $key . '###'] = $value;
			}
			return strtr($GLOBALS['TL_LANG']['MSC']['NOTIFICATION']['ncNotificationCenter']['userActivated'], $token);
		}
		return '--- item not found ---';
	}
	
	
	/**
	 * Add notification label after a useraccount was deleted
	 * @param int
	 */
	public function getMemberCloseNotificationLabel($intId)
	{
		$objResult = $this->Database->prepare("SELECT * FROM tl_member WHERE id=?")->execute($intId);
		if ($objResult->next()) {
			$token = array(
				'###date###' => date($GLOBALS['TL_CONFIG']['datimFormat'], $objResult->tstamp)
			);
			foreach ($objResult->row() as $key => $value) {
				$token['###' . $key . '###'] = $value;
			}
			return strtr($GLOBALS['TL_LANG']['MSC']['NOTIFICATION']['ncNotificationCenter']['userClosed'], $token);
		}
		return '--- item not found ---';
	}
	
	
	/**
	 * Add notification label after a user has changed it's personal data
	 * @param int
	 */
	public function getMemberChangedNotificationLabel($intId)
	{
		$objResult = $this->Database->prepare("SELECT * FROM tl_member WHERE id=?")->execute($intId);
		if ($objResult->next()) {
			$token = array(
				'###date###' => date($GLOBALS['TL_CONFIG']['datimFormat'], $objResult->tstamp)
			);
			foreach ($objResult->row() as $key => $value) {
				$token['###' . $key . '###'] = $value;
			}
			return strtr($GLOBALS['TL_LANG']['MSC']['NOTIFICATION']['ncNotificationCenter']['userChanged'], $token);
		}
		return '--- item not found ---';
	}
}