<?php
class full_server_checker
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

		//$ignored = array(579, 562, 780, 111, 538, 403, 267, 306, 249, 163, 402, 498, 592, 548);
		//$queue = array(151, 8, 9, 10);
		$userstokick = array();
		$clientskicked = 0;
		
		if($ts_data['server']['virtualserver_clientsonline'] - $ts_data['server']['virtualserver_queryclientsonline'] >= self::$function['execute_when'])
		{
			foreach($ts_data['clients'] as $client)
			{
				$ignore = false;
				foreach(explode(",", $client['client_servergroups']) as $group)
					if(in_array($group, self::$function['ignored_groups']))
						$ignore = true;
				if(!$ignore)
					if(floor($client['client_idle_time']/1000) >= 600)
						$userstokick[$client['client_idle_time']] = $client;
			}
			krsort($userstokick);
			for($i = 0; $i < sizeof(self::$function['queue']); $i++)
				foreach($userstokick as $client)
					if($clientskicked <= self::$function['how_many_kicks'])
						if($powerBot->hasGroup($client['client_servergroups'], $queue[$i]))
						{
							$query->clientPoke($client['clid'], self::$function['kick_message']);
							$query->clientKick($client['clid'], "server", 'Serwer jest pełny! Twój czas away jest zbyt duży.');
							$clientskicked++;
						}
		}
		
	}
}