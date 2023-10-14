<?php
class urodziny
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

		$result = $mysql_query->query("SELECT `birthday` FROM `clients` WHERE `client_database_id` = '".$params['executor']['client_database_id']."';");
		$record = $result->fetch_assoc();
		if($record['birthday'] !== NULL)
		{			
			$ts_query->sendMessage(1, $params['executor']['clid'], " > [b]".$params['executor']['client_nickname']."[/b], do Twojego konta jest już dodana data urodzenia: [b]".$record['birthday']."[/b]");
			$ts_query->sendMessage(1, $params['executor']['clid'], " > Jeżeli chcesz zmienić tą datę, skontaktuj się z administratorem. Wpisz [b]!cp[/b] aby przenieść na Centrum Pomocy.");
		}
		else
		{
			if(!isset($params['arguments'][1]))
				$ts_query->sendMessage(1, $params['executor']['clid'], " > Musisz podać datę urodzenia. Wpisz [b]!urodziny 00.00[/b] (dzień.miesiąc).");
			else
				if(strlen($params['arguments'][1]) != 5)
					$ts_query->sendMessage(1, $params['executor']['clid'], " > Musisz podać poprawną datę urodzenia. Wpisz [b]!urodziny 00.00[/b] (dzień.miesiąc).");
				else
				{
					if(strpos($params['arguments'][1], ".") === false)
						$ts_query->sendMessage(1, $params['executor']['clid'], " > Musisz zastosować się do wzoru daty urodzenia. Wpisz [b]!urodziny 00.00[/b] (dzień.miesiąc).");
					else
					{

						$date = explode(".", $params['arguments'][1]);
						$day = $date[0];
						$month = $date[1];
						if(!is_numeric($day) || !is_numeric($month))
							$ts_query->sendMessage(1, $params['executor']['clid'], " > W dacie możesz zawrzeć tylko liczby. Wpisz [b]!urodziny 00.00[/b] (dzień.miesiąc).");
						else
						{
							if($day > 0 && $day < 32)
								if($month > 0 && $month < 13)
								{
									$result = $mysql_query->query("UPDATE `clients` SET `birthday` = '".$day.".".$month."' WHERE `client_database_id` = ".$params['executor']['client_database_id'].";");
									if($result)
									{
										$ts_query->sendMessage(1, $params['executor']['clid'], " > Gratulacje, [b]".$params['executor']['client_nickname']."[/b]! Twoja data urodzenia została poprawnie ustawiona na [b]".$params['arguments'][1]."[/b].");
										$ts_query->sendMessage(1, $params['executor']['clid'], " > W tym dniu Twój nick będzie wyświetlany na bannerze a do Twojego konta zostanie dodana specjalna ranga, która posiada uprawnienia użytkownika premium, jako prezent od serwera.");
										$ts_query->sendMessage(1, $params['executor']['clid'], " > Jeżeli chcesz zmienić tą datę, skontaktuj się z administratorem. Wpisz [b]!cp[/b] aby przenieść na Centrum Pomocy.");
									}
									else
										$ts_query->sendMessage(1, $params['executor']['clid'], " > Wystąpił nieznany błąd. Wpisz [b]!cp[/b] aby przenieść na Centrum Pomocy.");
								}
								else
									$ts_query->sendMessage(1, $params['executor']['clid'], " > Miesiąc musi być w przedziale liczb [b]od 1 do 12[/b]. Wpisz [b]!urodziny 00.00[/b] (dzień.miesiąc).");
							else
								$ts_query->sendMessage(1, $params['executor']['clid'], " > Dzień musi być w przedziale liczb [b]od 1 do 31[/b]. Wpisz [b]!urodziny 00.00[/b] (dzień.miesiąc).");
						}
					}
				}
		}
	}
}