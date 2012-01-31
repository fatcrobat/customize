<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');


$GLOBALS['TL_DCA']['tl_layout']['palettes']['default'] = str_replace('head;', 'head;{jQuery_legend},addjQuery;', $GLOBALS['TL_DCA']['tl_layout']['palettes']['default']);

array_push($GLOBALS['TL_DCA']['tl_layout']['palettes']['__selector__'], 'addjQuery', 'addjQueryUI');

$GLOBALS['TL_DCA']['tl_layout']['subpalettes']['addjQuery'] = 'jQueryVersion,jQuerySource,jQueryScripts,jQuerycombineScripts,addjQueryUI';
$GLOBALS['TL_DCA']['tl_layout']['subpalettes']['addjQueryUI'] = 'jQueryUIVersion, jQueryUISource, jQueryUITheme';


$GLOBALS['TL_DCA']['tl_layout']['fields']['addjQuery'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['addjQuery'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true, 'tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['jQueryVersion'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['jQueryVersion'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'				=> array('tl_layout_customize', 'getjQueryVersionOptions'),
	'eval'										=> array('tl_class' => 'w50 clr')
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['jQuerySource'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['jQuerySource'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'									=> array('google', 'microsoft', 'jquery', 'local'),
	'reference'								=> &$GLOBALS['TL_LANG']['tl_layout']['jQuerySourceOptions'],
	'eval'										=> array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['jQueryScripts'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['jQueryScripts'],
	'exclude'                 => true,
	'inputType'               => 'checkboxWizard',
	'options_callback'        => array('tl_layout_customize', 'getjQueryScripts'),
	'eval'                    => array('multiple'=>true, 'tl_class' => 'clr')
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['jQuerycombineScripts'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['jQuerycombineScripts'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['addjQueryUI'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['addjQueryUI'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true, 'tl_class' => 'clr w50')
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['jQueryUIVersion'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['jQueryUIVersion'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'				=> array('tl_layout_customize', 'getjQueryUIVersionOptions'),
	'eval'										=> array('tl_class' => 'clr w50')
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['jQueryUISource'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['jQueryUISource'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'									=> array('google', 'microsoft', 'local'),
	'reference'								=> &$GLOBALS['TL_LANG']['tl_layout']['jQueryUISourceOptions'],
	'eval'										=> array('tl_class' => 'w50', 'submitOnChange'=>true)
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['jQueryUITheme'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['jQueryUITheme'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'				=> array('tl_layout_customize', 'getjQueryUIThemes'),
	'reference'								=> &$GLOBALS['TL_LANG']['tl_layout']['jQueryUIThemeOptions'],
	'eval'										=> array('tl_class' => 'w50', 'includeBlankOption' => true)
);


class tl_layout_customize extends Backend
{
	public static $JSMODE = 'js'; 
	
	public static $CSSMODE = 'css';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getjQueryVersionOptions(DataContainer $dc)
	{
		return $this->getVersionOptionGroup($GLOBALS['JQUERY_VERSIONS']);
	}
	
	public function getjQueryUIVersionOptions(DataContainer $dc)
	{
		return $this->getVersionOptionGroup($GLOBALS['JQUERYUI_VERSIONS']);
	}
	
	public function getjQueryUIThemes(DataContainer $dc)
	{
		$themes = array();
		
		if($dc->activeRecord->jQueryUISource == 'local')
		{
			$themes = $this->getCSSFiles($dc);
			return $themes;
		}
		
		foreach($GLOBALS['JQUERYUI_THEMES'] as $theme)
		{
			$themes[] = $theme;
		}
		
		return $themes;
	}
	
	private function getVersionOptionGroup($dc)
	{
		$versions = array();
		
		foreach (array_reverse($dc) as $k=>$v)
		{
			foreach (array_reverse(array_values($v)) as $kk)
			{
				$versions[$k][] = $kk;
			}
		}
		
		return $versions;
	}
	
	public function getCSSFiles(DataContainer $dc)
	{
		$css = array();
		
		$objTheme = $this->Database->prepare('SELECT * FROM tl_theme WHERE id = ?')->limit(1)->execute($dc->activeRecord->pid);
		
		if($objTheme->numRows < 1)
		{
			return $css;
		}
		
		$strFolders = deserialize($objTheme->folders);
		
		if(is_array($strFolders))
		{
			$css = $this->collectFiles($strFolders, $css, self::$CSSMODE);
		}
		
		return $css;
	}
	
	public function getjQueryScripts(DataContainer $dc)
	{
		$scripts = array();
		
		$objTheme = $this->Database->prepare('SELECT * FROM tl_theme WHERE id = ?')->limit(1)->execute($dc->activeRecord->pid);
		
		if($objTheme->numRows < 1)
		{
			return $scripts;
		}
		
		$strFolders = deserialize($objTheme->folders);
		
		if(is_array($strFolders))
		{
			$scripts = $this->collectFiles($strFolders, $scripts, self::$JSMODE);
		}
		
		return $scripts;
	}
	
	/**
	 * collect all js files recursively
	 * @param array $strFolders
	 * @param array $scripts
	 */
	
	private function collectFiles($strFolders, $files = array(), $mode)
	{
		$childFolders = array();
		
		foreach($strFolders as $strFolder)
		{
			foreach (scandir(TL_ROOT . '/' . $strFolder) as $strFile)
			{
				if ($strFile == "." || $strFile == "..") {
					continue;
				}
				
				if(is_dir(TL_ROOT . '/' . $strFolder. '/' . $strFile))
				{
					$childFolders[] = $strFolder. '/' . $strFile; 					
				}
				
				if(preg_match('/\.'.$mode.'$/', $strFile))
				{
					$files[] = $strFolder. '/' . $strFile;
				}
			}
		}
		
		if(count($childFolders) > 0)
		{
			$files = $this->collectFiles($childFolders, $files, $mode);
		}
		
		return $files;
	}

}