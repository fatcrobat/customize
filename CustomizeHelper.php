<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

class CustomizeHelper extends System
{
	public static $jqueryFolder = 'plugins/jquery/';
	
	public static $jqueryUIFolder = 'plugins/jquery/ui/';
	
	public static $jqueryUIThemeFolder = 'plugins/jquery/ui/themes';
	
	public static function getjQueryVersion()
	{
		$objLayout = self::getPageLayout();
		
		// uncomment to update local js + css files from google cdn
// 		self::loadAlljQueryVersion(); 
// 		self::loadAlljQueryUIVersion();
		
		return $objLayout->jQueryVersion;
	}
	
	public static function getjQueryUIVersion()
	{
		$objLayout = self::getPageLayout();
		
		return $objLayout->jQueryUIVersion;
	}
	
	public static function getPageLayout()
	{
		global $objPage;
		
		$db = Database::getInstance();
		
		$objLayout = $db->prepare("SELECT l.*, t.templates FROM tl_layout l LEFT JOIN tl_theme t ON l.pid=t.id WHERE l.id=?")
								->limit(1)
								->execute($objPage->layout);
		
		// Fallback layout
		if ($objLayout->numRows < 1)
		{
			$objLayout = $db->prepare("SELECT l.*, t.templates FROM tl_layout l LEFT JOIN tl_theme t ON l.pid=t.id WHERE l.fallback=1")
									->limit(1)
									->execute();
		}
		
		// Die if there is no layout at all
		if ($objLayout->numRows < 1)
		{
			die('No layout specified');
		}
		
		return $objLayout;
	}
	
	/**
	 * Helper Function to load all jQuery Versions as File
	 */
	public static function loadAlljQueryVersion()
	{
		$files = Files::getInstance();
		
		foreach ($GLOBALS['JQUERY_VERSIONS'] as $k=>$v)
		{
			foreach (array_values($v) as $version)
			{
				$target = self::$jqueryFolder . 'jquery-' .  $version . '.min.js';
				$files->fopen($target, 'w');
				$source = file_get_contents('http://ajax.googleapis.com/ajax/libs/jquery/'.$version.'/jquery.min.js'); 
				file_put_contents($target, $source);
				$files->fclose($target);
			}
		}
	}
	
	/**
	 * Helper Function to load all jQuery UI Versions as File
	 */
	public static function loadAlljQueryUIVersion()
	{
		$files = Files::getInstance();
		
		foreach ($GLOBALS['JQUERYUI_VERSIONS'] as $k=>$v)
		{
			foreach (array_values($v) as $version)
			{
				$target = self::$jqueryUIFolder . 'jquery-ui-' .  $version . '.min.js';
				$files->fopen($target, 'w');
				$source = file_get_contents('http://ajax.googleapis.com/ajax/libs/jqueryui/'.$version.'/jquery-ui.min.js');
				file_put_contents($target, $source);
				$files->fclose($target);
			}
		}
	}
	
	public static function getCustomScripts()
	{
		$scripts = array();
		
		$objLayout = self::getPageLayout();
		
		$jqueryScripts = deserialize($objLayout->jQueryScripts);		
		
		if(is_array($jqueryScripts))
		{
			if($objLayout->jQuerycombineScripts)
			{
				$objCombiner = new Combiner();
				
				foreach($jqueryScripts as $script)
				{
					if(is_file($script))
					{
						$objCombiner->add($script, CUSTOMIZE);
					}
				}
				return array($objCombiner->getCombinedFile(TL_PLUGINS_URL));
			}
			$scripts = $jqueryScripts;
		}
		
		return $scripts;
	}

}