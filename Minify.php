<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

class Minify
{
	/**
	 * 
	 * @param String $strContent - Combined File Content
	 * @param String $strKey - Combinded File Name
	 * @param String $strMode - CSS or JS File
	 */
	public function extendCombinedFile($strContent, $strKey, $strMode)
	{
		if($strMode == Combiner::JS)
		{
			return JSMin::minify($strContent);
		}
		return $strContent;
	}
}