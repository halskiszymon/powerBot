<?php
class adverts
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

		foreach(self::$function['addons'] as $name => $addon)
			if($addon['enabled'])	
				if($name == 'ads')
					foreach($ts_data['clients'] as $client)
					{
						$result = $mysql_query->query("SELECT `receives_adverts` FROM `clients` WHERE `client_database_id` = '".$client['client_database_id']."';");
						$record = $result->fetch_assoc();
						if($record['receives_adverts'])
							foreach(self::$function['addons'][$name]['messages'] as $message)
								$ts_query->sendMessage(1, $client['clid'], $message);
					}
				if($name == 'birthday_reminder')
					foreach($ts_data['clients'] as $client)
					{
						$result = $mysql_query->query("SELECT `birthday`, `receives_adverts` FROM `clients` WHERE `client_database_id` = '".$client['client_database_id']."';");
						$record = $result->fetch_assoc();
						if($record['receives_adverts'])
							if($record['birthday'] === NULL)
								foreach(self::$function['addons'][$name]['messages'] as $message)
									$ts_query->sendMessage(1, $client['clid'], $message);
					}
	}
}