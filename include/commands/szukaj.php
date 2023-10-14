<?php
class szukaj
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

		if(!isset($params['arguments'][1]))
			$ts_query->sendMessage(1, $params['executor']['clid'], ' > Podaj nick, który mam wyszukać. Wpisz [b]!szukaj <nick/client_database_id>[/b].');
		else
		{
			if(is_numeric($params['arguments'][1]))
			{
				$result = $mysql_query->query("SELECT * FROM `clients` WHERE `client_database_id` = ".$params['arguments'][1].";");
				if($result->num_rows == 0)
					$ts_query->sendMessage(1, $params['executor']['clid'], ' > Nie znaleziono takiego użytkownika.');
				else
				{
					$record = $result->fetch_assoc();
					$ts_query->sendMessage(1, $params['executor']['clid'], '[b][u] > Historia nicków użytkownika '.$record['client_nickname'].':[/u][/b]');
					foreach(json_decode($record['all_nicks'], true) as $time => $nick)
						$ts_query->sendMessage(1, $params['executor']['clid'], '     - '.date('d-m-Y, H:i:s',$time).': [b]'.$nick.'[/b]');
				}
			}
			else
			{
				$current_nicks = array();
				$history_nicks = array();

				$result = $mysql_query->query("SELECT * FROM `clients`;");
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Trwa przeszukiwanie [b]'.$result->num_rows.' użytkowników[/b]. Proszę czekać...');
				$records = mysqli_fetch_all($result, MYSQLI_ASSOC);
				foreach($records as $record)
				{
					if($record['client_nickname'] == $params['arguments'][1])
					{
						$client_info = $powerBot->getClientInfo('client_database_id', $record['client_database_id']);
						array_push($current_nicks, array('client_nickname' => $record['client_nickname'], 'client_database_id' => $record['client_database_id'], 'client_unique_identifier' => $client_info['client_unique_identifier']));
					}
					if(strpos($record['all_nicks'], $params['arguments'][1]) !== false)
						foreach(json_decode($record['all_nicks'], true) as $time => $nick)
							if($nick == $params['arguments'][1])
							{
								$client_info = $powerBot->getClientInfo('client_database_id', $record['client_database_id']);
								array_push($history_nicks, array('client_nickname' => $nick, 'client_database_id' => $record['client_database_id'], 'client_unique_identifier' => $client_info['client_unique_identifier'], 'change_time' => $time));
							}
				}

				$ts_query->sendMessage(1, $params['executor']['clid'], '[b][u] > Aktualnie taki nick posiadają:[/u][/b]');
				if(sizeof($current_nicks) > 0)
					foreach($current_nicks as $client)
						$ts_query->sendMessage(1, $params['executor']['clid'], '     - [url=client://0/'.$client['client_unique_identifier'].']'.$client['client_nickname'].'[/url] ([b]ID w bazie danych:[/b] '.$client['client_database_id'].')');
				else
					$ts_query->sendMessage(1, $params['executor']['clid'], '     > Nie znaleziono żadnych użytkowników, którzy aktualnie mają taki nick.');

				$ts_query->sendMessage(1, $params['executor']['clid'], '[b][/b]');

				$ts_query->sendMessage(1, $params['executor']['clid'], '[b][u] > Historia zmian na taki nick:[/u][/b]');
				if(sizeof($history_nicks) > 0)
					foreach($history_nicks as $client)
						$ts_query->sendMessage(1, $params['executor']['clid'], '     - [url=client://0/'.$client['client_unique_identifier'].']'.$client['client_nickname'].'[/url] ([b]Data zmiany:[/b] '.date('d-m-Y, H:i:s', $client['change_time']).', [b]ID w bazie danych:[/b] '.$client['client_database_id'].')');
				else
					$ts_query->sendMessage(1, $params['executor']['clid'], '     > Nie znaleziono żadnych użytkowników, którzy kiedyś mieli taki nick.');
			}
		}
	}
}