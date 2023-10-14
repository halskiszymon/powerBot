<?php
class change_server_name
{
	private static $function;
	
	public static function register($name)
	{
		global $config;
		self::$function = $config['functions'][$name];
		self::$function['name'] = $name;
		echo "    > '".$name."' - zarejestrowano\n";
	}
	
	public static function execute()
	{
		global $powerBot, $ts_query, $ts_data;

		$name = str_replace(
			array("{ONLINE_CLIENTS}", "{MAX_CLIENTS}"),
			array($ts_data['server']['virtualserver_clientsonline'] - $ts_data['server']['virtualserver_queryclientsonline'], $ts_data['server']['virtualserver_maxclients']),
			self::$function['server_name']);
		if($powerBot->checkData('server', 'virtualserver_name', $name))
			$powerBot->addToServerEditQueue(self::$function['name'], 'virtualserver_name', $name);
	}
}