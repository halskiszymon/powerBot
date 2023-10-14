<?php
class change_host_message
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

		$message = str_replace("{ONLINE_CLIENTS}", $ts_data['server']['virtualserver_clientsonline'] - $ts_data['server']['virtualserver_queryclientsonline'], self::$function['host_message']);
		if($powerBot->checkData('server', 'virtualserver_hostmessage', $message))
			$powerBot->addToServerEditQueue(self::$function['name'], 'virtualserver_hostmessage', $message);
	}
}