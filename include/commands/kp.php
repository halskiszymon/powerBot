<?php
class kp
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
		global $powerBot, $ts_query, $mysql_query;

		$result = $mysql_query->query("SELECT * FROM `shop_purchased_services` WHERE `service_id` = 6;");
		foreach(mysqli_fetch_all($result, MYSQLI_ASSOC) as $channel)
		{
			$client = $powerBot->getClientInfo('client_database_id', $channel['client_database_id']);
			$buyer = $powerBot->getClientInfo('client_database_id', $channel['buyer_database_id']);
			$ts_query->sendMessage(1, $params['executor']['clid'], 'Kanał premium ([b]ID: '.$channel['id'].'[/b])');
			$ts_query->sendMessage(1, $params['executor']['clid'], '    > Status: [b]'.$channel['status'].'[b]');
			$ts_query->sendMessage(1, $params['executor']['clid'], '    > Użytkownik: [b]'.$client['client_nickname'].'[b]');
			$ts_query->sendMessage(1, $params['executor']['clid'], '    > Kupujący: [b]'.$buyer['client_nickname'].'[b]');
			$ts_query->sendMessage(1, $params['executor']['clid'], '    > Data zakupu: [b]'.date('d.m.Y, H:i:s', $channel['purcharse_time']).'[b]');
			$ts_query->sendMessage(1, $params['executor']['clid'], '    > Data wygaśnięcia: [b]'.date('d.m.Y, H:i:s', $channel['expiration_time']).'[b]');
		}

	}
}