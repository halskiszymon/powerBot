<?php
class generate_banner_cache
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
		global $powerBot, $ts_query, $mysql_query, $ts_data;

		$cache = array();
		foreach($ts_data['clients'] as $client)
		{
			if($client['client_type'])
				continue;

			$result = $mysql_query->query("SELECT `level` FROM `clients` WHERE `client_database_id` = ".$client['client_database_id'].";");
			if($result->num_rows > 0)
			{
				$record = $result->fetch_assoc();
				$level = $record['level'];
			}
			else
				$level = 0;

			if(isset($cache['clients'][$client['connection_client_ip']]))
			{
				$index = sizeof($cache['clients'][$client['connection_client_ip']]) + 1;
				$cache['clients'][$client['connection_client_ip']][$index] = $client;
				$cache['clients'][$client['connection_client_ip']][$index]['level'] = $level;
			}
			else
			{
				$cache['clients'][$client['connection_client_ip']][0] = $client;
				$cache['clients'][$client['connection_client_ip']][0]['level'] = $level;
			}
		}
		file_put_contents(self::$function['dir'].'clients-data.json', json_encode($cache));

		$cache = array();
		$today = date('d').'.'.date('m');
		$result = $mysql_query->query("SELECT `client_nickname` FROM `clients` WHERE `birthday` = '".$today."';");
		foreach(mysqli_fetch_all($result, MYSQLI_ASSOC) as $client)
			$cache['today_birthdays'][] = $client['client_nickname'];
		file_put_contents(self::$function['dir'].'birthdays.json', json_encode($cache));
	}
}