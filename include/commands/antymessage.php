<?php
class antymessage
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
		global $powerBot, $ts_query;

		if($powerBot->hasGroup($params['executor']['client_servergroups'], self::$command['group_id']))
		{
			$ts_query->serverGroupDeleteClient(self::$command['group_id'], $params['executor']['client_database_id']);
			$ts_query->sendMessage(1, $params['executor']['clid'], ' > Ranga [b]AntyMessage[/b] została odebrana.');
		}
		else
		{
			$ts_query->serverGroupAddClient(self::$command['group_id'], $params['executor']['client_database_id']);
			$ts_query->sendMessage(1, $params['executor']['clid'], ' > Ranga [b]AntyMessage[/b] została nadana. Jeżeli chcesz ją usunąć, wpisz ponownie [b]!antymessage[/b].');
		}
		
	}
}