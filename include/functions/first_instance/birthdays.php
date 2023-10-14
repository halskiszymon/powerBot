<?php
class birthdays
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
		global $powerBot, $ts_query, $mysql_query;

		$today = date('d').'.'.date('m');
		$result = $mysql_query->query("SELECT `client_database_id` FROM `clients` WHERE `birthday` = '".$today."';");
		foreach(mysqli_fetch_all($result, MYSQLI_ASSOC) as $client)
			$today_birthdays[$client['client_database_id']] = $client['client_database_id'];

		foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(self::$function['group_id'])) as $client)
			if(isset($client['cldbid']))
			{
				if(!array_key_exists($client['cldbid'], $today_birthdays))
					$ts_query->serverGroupDeleteClient(self::$function['group_id'], $client['cldbid']);
				unset($today_birthdays[$client['cldbid']]);
			}

		if(isset($today_birthdays))
			foreach($today_birthdays as $index => $client)
				$ts_query->serverGroupAddClient(self::$function['group_id'], $index);
	}
}