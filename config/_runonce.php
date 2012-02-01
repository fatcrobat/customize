<?php

class CustomizeRunonce extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function run()
	{
		$this->import('Files');
		$this->loadAlljQueryVersion();
		$this->loadAlljQueryUIVersion();
		$this->loadDatepickerLocales();
	}
	
	/**
	 * download all jQuery Versions as File
	 */
	public function loadAlljQueryVersion()
	{
		$files = $this->Files;
		
		if(!is_dir(CustomizeHelper::$jqueryFolder))
		{
			$files->mkdir(CustomizeHelper::$jqueryFolder);
		}
		
		foreach ($GLOBALS['JQUERY_VERSIONS'] as $k=>$v)
		{
			foreach (array_values($v) as $version)
			{
				$target = TL_ROOT . '/' . CustomizeHelper::$jqueryFolder . 'jquery-' .  $version . '.min.js';
				if(file_exists($target))
				{
					continue;
				}
				$files->fopen($target, 'w');
				$source = file_get_contents('http://ajax.googleapis.com/ajax/libs/jquery/'.$version.'/jquery.min.js'); 
				file_put_contents($target, $source);
				$files->fclose($target);
			}
		}
	}
	
	/**
	 * download all jQuery UI Versions as File
	 */
	public function loadAlljQueryUIVersion()
	{
		$files = $this->Files;
		
		if(!is_dir(CustomizeHelper::$jqueryUIFolder))
		{
			$files->mkdir(CustomizeHelper::$jqueryUIFolder);
		}
		
		foreach ($GLOBALS['JQUERYUI_VERSIONS'] as $k=>$v)
		{
			foreach (array_values($v) as $version)
			{
				$target = TL_ROOT . '/' . CustomizeHelper::$jqueryUIFolder . 'jquery-ui-' .  $version . '.min.js';
				if(file_exists($target))
				{
					continue;
				}
				$files->fopen($target, 'w');
				$source = file_get_contents('http://ajax.googleapis.com/ajax/libs/jqueryui/'.$version.'/jquery-ui.min.js');
				file_put_contents($target, $source);
				$files->fclose($target);
			}
		}
	}
	
	public function loadDatepickerLocales()
	{
		$url = 'http://jquery-ui.googlecode.com/svn/trunk/ui/i18n/';
	
		$arrFiles = array();
	
		$listFile = file_get_contents($url);
	
		preg_match_all('/[>](jquery.ui.datepicker-.*.js)/', $listFile, $matches);
	
		$arrFiles = $matches[1];
	
		$files = $this->Files;
	
		if(!is_dir(CustomizeHelper::$jqueryUIMiscFolder))
		{
			$files->mkdir(CustomizeHelper::$jqueryUIMiscFolder);
		}
	
		if(is_array($arrFiles))
		{
			foreach($arrFiles as $fileName)
			{
				$target = TL_ROOT . '/' . CustomizeHelper::$jqueryUIMiscFolder . '/' . $fileName;
				if(file_exists($target))
				{
					continue;
				}
				$files->fopen($target, 'w');
				$source = file_get_contents($url . $fileName);
				file_put_contents($target, $source);
				$files->fclose($target);
			}
		}
	}
}

/**
 * Instantiate controller
 */
$objCustomizeRunonce = new CustomizeRunonce();
$objCustomizeRunonce->run();