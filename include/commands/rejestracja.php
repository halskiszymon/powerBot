<?php
class rejestracja
{
	private static $command;
	
	public static function register($name)
	{
		global $config;
		self::$command = $config['commands'][$name];
		self::$command['name'] = $name;
		echo "    > '".$name."' - zarejestrowano\n";
	}
	
	public static function execute($params)
	{
		global $powerBot, $ts_query, $ts_data;

		$ts_query->sendMessage(1, $params['executor']['clid'], ' > Zarejestruj się by otrzymać o wiele więcej możliwości!');
		$ts_query->sendMessage(1, $params['executor']['clid'], ' > W tym celu wpisz [b][u]!cp[/u][/b] a następnie zaczekaj na administratora.');
		
	}
}