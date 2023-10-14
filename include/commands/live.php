<?php
class live
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

		$count = 0;
		foreach($ts_data['clients'] as $client)
		{
			$ts_query->sendMessage(1, $client['clid'], 'Cześć! Z tej strony [b]CorinMear[/b], streamer [color=#5d008b][b]Twitch[/b][/color]. Jeżeli masz ochotę to wbij na mojego live: [url=twitch.tv/corinmear][b]kliknij tutaj![/b][/url]');
			$count++;
		}
		$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=green][b]Sukces![/b][/color] Pomyślnie powiadomiono [b]'.$count.' osób[/b]');
	}
}