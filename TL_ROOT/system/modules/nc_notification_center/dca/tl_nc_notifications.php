<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2015 Leo Feyer
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
 * Table tl_nc_notifications
 */
$GLOBALS['TL_DCA']['tl_nc_notifications'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'closed'                      => true,
		'notEditable'                 => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

	// List
	'list' => array
	(
        'sorting' => array
        (
            'mode'                    => 1,
            'mode'                    => 7,
			'fields'                  => array('tstamp DESC'),
			'panelLayout'             => 'filter;search,limit'
        ),
        'label' => array
        (
			'fields'                  => array('id'),
            'label_callback'          => array('tl_nc_notifications', 'getNotificationLabel')
        ),
        'operations' => array
        (
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_nc_notifications']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'     => array('tl_nc_notifications', 'getDeleteButton')
            ),
			'goto' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_nc_notifications']['goto'],
				'icon'                => 'system/modules/nc_notification_center/assets/link.gif',
				'button_callback'     => array('tl_nc_notifications', 'getGotoButton')
			)
        )
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'sorting'                 => true,
			'flag'                    => 7
		),
		'source' => array
		(
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'sid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'href' => array
		(
			'sql'                     => "varchar(255) NOT NULL default ''"
		)
	)
);


/**
 * Class tl_nc_notifications
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @package   NC Notification Center
 * @author    Marcel Mathias Nolte
 * @copyright Marcel Mathias Nolte 2015
 */
class tl_nc_notifications extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
	
	
	/**
	 * Return the "delete" button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function getDeleteButton($arrRow, $href, $label, $title, $icon, $attributes)
	{
		if (substr($arrRow['source'], 0, 9) == 'tl_member') 
		{
			$href .= '&id=' . $arrRow['id'];
        	return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
		}
		return '';
	}
	
	
	/**
	 * Return the "goto" button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function getGotoButton($arrRow, $href, $label, $title, $icon, $attributes)
	{
		if (isset($GLOBALS['TL_HOOKS']['getNotificationLabel'][$arrRow['source']]) && (is_array($GLOBALS['TL_HOOKS']['getNotificationLabel'][$arrRow['source']]) || is_callable($GLOBALS['TL_HOOKS']['getNotificationLabel'][$arrRow['source']]))) 
		{
			return '<a href="'.$GLOBALS['TL_CONFIG']['websitePath'].'contao/'.$arrRow['href'].'&rt='.REQUEST_TOKEN.'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
		}
		return '';
	}
	

	/**
	 * Generate notification label
	 * @return array
	 */
	public function getNotificationLabel($arrRow)
	{
		if (isset($GLOBALS['TL_HOOKS']['getNotificationLabel'][$arrRow['source']])) 
		{
			$callback = $GLOBALS['TL_HOOKS']['getNotificationLabel'][$arrRow['source']];	
			if (is_array($callback))
			{
				$this->import($callback[0]);
				return $this->$callback[0]->$callback[1]($arrRow['sid']);
			}
			elseif (is_callable($callback))
			{
				return $callback($arrRow['sid']);
			}
		}
		return '--- module for [' . $arrRow['source'] . '] not installed ---';
	}
}