<?php
class pomoc
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

		$ts_query->sendMessage(1, $params['executor']['clid'], ' > Witaj, [b]'.$params['executor']['client_nickname'].'[/b]. W czym mogę Ci pomóc?');
		$ts_query->sendMessage(1, $params['executor']['clid'], ' ');

		$ts_query->sendMessage(1, $params['executor']['clid'], '[b]* Komendy dla wszystkich *[/b]');

		foreach(self::$command['register_groups'] as $group)
			if(!$powerBot->hasGroup($params['executor']['client_servergroups'], $group))
			{
				$ts_query->sendMessage(1, $params['executor']['clid'], '     » | [b][u]!rejestracja[/u][/b] - zostań zarejestrowanym użytkownikiem.');
				break 1;
			}

		$ts_query->sendMessage(1, $params['executor']['clid'], '     » | [b][u]!cp[/u][/b] - przenosi na kanał Centrum Pomocy, gdzie możesz porozmawiać z administratorem.');
		$ts_query->sendMessage(1, $params['executor']['clid'], '     » | [b][u]!kanal[/u][/b] - utwórz, lub przenieś się na swój kanał prywatny.');
		$ts_query->sendMessage(1, $params['executor']['clid'], '     » | [b][u]!poziom[/u][/b] - informacje o Twoim poziomie.');
		$ts_query->sendMessage(1, $params['executor']['clid'], '     » | [b][u]!urodziny 00.00[/u][/b] (dzień.miesiąc) - ustawia datę Twoich urodzin. W tym dniu Twój nick będzie wyświetlany na bannerze a do Twojego konta zostanie dodana specjalna ranga, aby uczcić ten moment.');


		$ts_query->sendMessage(1, $params['executor']['clid'], ' ');
		$ts_query->sendMessage(1, $params['executor']['clid'], '[b]* Komendy dla użytkowników premium *[/b]');
		$ts_query->sendMessage(1, $params['executor']['clid'], '     » | [b][u]!antypoke[/u][/b] - nadaj / zabierz rangę AntyPoke.');
		$ts_query->sendMessage(1, $params['executor']['clid'], '     » | [b][u]!antymessage[/u][/b] - nadaj / zabierz rangę AntyMessage.');
	}
}