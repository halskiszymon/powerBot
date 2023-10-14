<?php
class tokeny
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
			$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użycie komendy: [b]!tokeny <stan/dodaj/usun/wyzeruj/ustaw> <client_database_id> [wartość]');
		else
		{
			$result = $mysql_query->query("SELECT `shop_tokens`,`client_nickname` FROM `clients` WHERE `client_database_id` = ".$params['arguments'][2].";");
			if($result->num_rows > 0)
			{
				$result = $result->fetch_assoc();
				if($params['arguments'][1] == 'stan')
					$ts_query->sendMessage(1, $params['executor']['clid'], ' > Stan konta użytkownika [b]'.$result['client_nickname'].'[/b] wynosi [b]'.$result['shop_tokens'].' tokenów[/b].');
				else if($params['arguments'][1] == 'dodaj')
				{
					if(is_numeric($params['arguments'][3]))
					{
						$mysql_query->query("UPDATE `clients` SET `shop_tokens` = ".($result['shop_tokens'] + $params['arguments'][3])." WHERE `client_database_id` = ".$params['arguments'][2].";");
						$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=green][b]Sukces:[/b][/color] Poprawnie dodano tokeny. Stan konta użytkownika [b]'.$result['client_nickname'].'[/b] wynosi teraz [b]'.($result['shop_tokens'] + $params['arguments'][3]).' tokenów[/b].');
						$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$params['arguments'][2].'| > Dnia [b]'.date('d.m.Y').'[/b] o godzinie [b]'.date('H:i').'[/b] do Twojego konta zostało dodanych [b]'.$params['arguments'][3].' tokenów[/b]. Twój aktualny stan konta wynosi [b]'.($result['shop_tokens'] + $params['arguments'][3]).' tokenów[/b].');
						$powerBot->log($params['executor']['client_nickname'].': '.$result['client_nickname'].' (DBID: '.$params['arguments'][2].'): +'.$params['arguments'][3], '', false, true);
					}
					else
						$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=red][b]Błąd:[/b][/color] Ilość tokenów musi być liczbą.');
				}
				else if($params['arguments'][1] == 'usun')
				{
					if(is_numeric($params['arguments'][3]))
					{
						if($result['shop_tokens'] - $params['arguments'][3] > 0)
						{
							$mysql_query->query("UPDATE `clients` SET `shop_tokens` = ".($result['shop_tokens'] - $params['arguments'][3])." WHERE `client_database_id` = ".$params['arguments'][2].";");
							$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=green][b]Sukces:[/b][/color] Poprawnie usunięto tokeny. Stan konta użytkownika [b]'.$result['client_nickname'].'[/b] wynosi teraz [b]'.($result['shop_tokens'] - $params['arguments'][3]).' tokenów[/b].');
							$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$params['arguments'][2].'| > Dnia [b]'.date('d.m.Y').'[/b] o godzinie [b]'.date('H:i').'[/b] z Twojego konta zostało usuniętych [b]'.$params['arguments'][3].' tokenów[/b]. Twój aktualny stan konta wynosi [b]'.($result['shop_tokens'] - $params['arguments'][3]).' tokenów[/b].');
							$powerBot->log($params['executor']['client_nickname'].': '.$result['client_nickname'].' (DBID: '.$params['arguments'][2].'): -'.$params['arguments'][3], '', false, true);
						}
						else
							$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=red][b]Błąd:[/b][/color] Nie można usunąć tylu tokenów, gdyż stan konta użytkownika [b]'.$result['client_nickname'].'[/b] będzie ujemny.');
					}
					else
						$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=red][b]Błąd:[/b][/color] Ilość tokenów musi być liczbą.');
				}
				else if($params['arguments'][1] == 'wyzeruj')
				{
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = 0 WHERE `client_database_id` = ".$params['arguments'][2].";");
					$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=green][b]Sukces:[/b][/color] Poprawnie wyzerowano stan konta użytkownika [b]'.$result['client_nickname'].'[/b].');
					$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$params['arguments'][2].'| > Dnia [b]'.date('d.m.Y').'[/b] o godzinie [b]'.date('H:i').'[/b] Twoje tokeny zostały wyzerowane.');
					$powerBot->log($params['executor']['client_nickname'].': '.$result['client_nickname'].' (DBID: '.$params['arguments'][2].'): empty', '', false, true);
				}
				else if($params['arguments'][1] == 'ustaw')
				{
					if(is_numeric($params['arguments'][3]))
					{
						$mysql_query->query("UPDATE `clients` SET `shop_tokens` = ".$params['arguments'][3]." WHERE `client_database_id` = ".$params['arguments'][2].";");
						$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=green][b]Sukces:[/b][/color] Poprawnie ustawiono liczbę tokenów. Stan konta użytkownika [b]'.$result['client_nickname'].'[/b] wynosi teraz [b]'.$params['arguments'][3].' tokenów[/b].');
						$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$params['arguments'][2].'| > Dnia [b]'.date('d.m.Y').'[/b] o godzinie [b]'.date('H:i').'[/b] stan Twojego konta ustawiono na [b]'.$params['arguments'][3].' tokenów[/b].');
						$powerBot->log($params['executor']['client_nickname'].': '.$result['client_nickname'].' (DBID: '.$params['arguments'][2].'): set '.$params['arguments'][3], '', false, true);
					}
					else
						$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=red][b]Błąd:[/b][/color] Ilość tokenów musi być liczbą.');
				}
			}
			else
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=red][b]Błąd:[/b][/color] Nie znaleziono takiego użytkownika w bazie danych.');
		}
	}
}