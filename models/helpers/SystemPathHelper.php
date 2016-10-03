<?php

namespace vommuan\filemanager\models\helpers;

use yii\base\Model;

class SystemPathHelper extends Model
{
	/**
     * Replace forward slash to DIRECTORY_SEPARATOR
     * 
     * @param string $path
     * @return string
     */
    public static function urlToPath($path)
    {
		return str_replace('/', DIRECTORY_SEPARATOR, $path);
	}
	
	/**
	 * Alias for function self::urlToPath()
	 */
	public static function u2p($path)
	{
		return self::urlToPath($path);
	}
	
	/**
     * Replace DIRECTORY_SEPARATOR to forward slash
     * 
     * @param string $path
     * @return string
     */
    public static function pathToUrl($path)
    {
		return str_replace(DIRECTORY_SEPARATOR, '/', $path);
	}
	
	/**
	 * Alias for function self::pathToUrl()
	 */
	public static function p2u($path)
	{
		return self::pathToUrl($path);
	}
}