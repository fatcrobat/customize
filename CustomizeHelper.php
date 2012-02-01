<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

class CustomizeHelper extends System
{
	public static $root = TL_ROOT;
	
	public static $jqueryFolder = 'plugins/jquery/';
	
	public static $jqueryUIFolder = 'plugins/jquery/ui/';
	
	public static $jqueryUIThemeFolder = 'plugins/jquery/ui/themes';
	
	public static $jqueryUIMiscFolder = 'plugins/jquery/ui/misc';
	
	public static function getjQueryVersion()
	{
		$objLayout = self::getPageLayout();
		
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
	
	public static function getCustomScripts()
	{
		$scripts = array();
		
		$objLayout = self::getPageLayout();
		
		$scripts = deserialize($objLayout->jQueryScripts);		
		
		if(is_array($scripts) && $objLayout->jQuerycombineScripts)
		{
			$objCombiner = new Combiner();
			
			foreach($scripts as $script)
			{
				if(is_file($script))
				{
					$objCombiner->add($script, CUSTOMIZE);
				}
			}
			return array($objCombiner->getCombinedFile(TL_PLUGINS_URL));
		}
		
		return $scripts;
	}	
}