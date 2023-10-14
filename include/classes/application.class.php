<?php
class application
{	
	private static $cache = array();
	
	private function setFunctionCache($function, $value)
	{
		self::$cache[$function] = $value;
	}
	
	private function deleteFunctionCache($function)
	{
		unset(self::$cache[$function]);
	}
	
	private function getFunctionCache($function)
	{
		return self::$cache[$function];
	}
	
	private function isFunctionInCache($function)
	{
		if(array_key_exists($function, self::$cache))
			return true;
		else
			return false;
	}
	
	public function isTimeForFunction($function, $iv = 0)
	{
		global $config;
		if($iv == 0)
		{
			if(!isset($config['functions'][$function]['interval']))
				return false;
			$interval = $config['functions'][$function]['interval'];
		}
		else
			$interval = $iv;
		if(self::isFunctionInCache($function))
		{
			$timetoexec = self::getFunctionCache($function);
			if(time() >= $timetoexec)
			{
				$timetoexec = time() + $interval['seconds'] + ($interval['minutes'] * 60) + ($interval['hours'] * 60 * 60) + ($interval['days'] * 24 * 60 * 60);
				self::deleteFunctionCache($function);
				self::setFunctionCache($function, $timetoexec);
				return true;
			}
			else
				return false;
		}
		else
		{
			$timetoexec = time() + $interval['seconds'] + ($interval['minutes'] * 60) + ($interval['hours'] * 60 * 60) + ($interval['days'] * 24 * 60 * 60);
			self::setFunctionCache($function, $timetoexec);
			return true;
		}
	}
}