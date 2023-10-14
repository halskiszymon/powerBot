<?php
class pokeall
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

		if(!isset($params['arguments'][1]))
		{
			$ts_query->sendMessage(1, $params['executor']['clid'], ' > Nie podano wiadomości do wysłania. Wpisz [b]!pokeall <wiadomość>[/b].');
			return;
		}

		$count = 0;
		foreach($ts_data['clients'] as $client)
		{
			foreach(self::$command['ignored_groups'] as $group)
				if($powerBot->hasGroup($client['client_servergroups'], $group))
					continue 2;
			$ts_query->clientPoke($client['clid'], $params['arguments'][1]);
			$count++;
		}
		$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=green][b]Sukces![/b][/color] Pomyślnie powiadomiono [b]'.$count.' osób[/b] z wiadmością: '.$params['arguments'][1]);
	}
}