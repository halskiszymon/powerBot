<?php
class block_recording
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

		foreach($ts_data['clients'] as $client)
		{
			if(!$client['client_is_recording'])
				continue;

			foreach(self::$function['ignored_groups'] as $group)
				if($powerBot->hasGroup($client['client_servergroups'], $group))
					continue 2;

			$ts_query->clientPoke($client['clid'], 'Zostałeś zbanowany za nagrywanie dźwięku na serwerze.');		
			$ts_query->banClient($client['clid'], 120, 'Nagrywanie dźwięku.');
		}
	}
}