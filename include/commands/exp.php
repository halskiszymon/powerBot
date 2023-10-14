<?php
class exp
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
			$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użycie komendy: [b]!exp <dodaj/usun/wyzeruj/ustaw/mnoznik> <client_database_id> [wartość]');
		else
		{
			$result = $mysql_query->query("SELECT `exp_bonus`,`client_nickname` FROM `clients` WHERE `client_database_id` = ".$params['arguments'][2].";");
			if($result->num_rows > 0)
			{
				$result = $result->fetch_assoc();
				if($params['arguments'][1] == 'dodaj')
				{
					if(is_numeric($params['arguments'][3]))
					{
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = ".($result['exp_bonus'] + $params['arguments'][3])." WHERE `client_database_id` = ".$params['arguments'][2].";");
						$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=green][b]Sukces:[/b][/color] Poprawnie dodano exp. Stan konta użytkownika [b]'.$result['client_nickname'].'[/b] wynosi teraz [b]'.($result['exp_bonus'] + $params['arguments'][3]).' expa[/b].');
						$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$params['arguments'][2].'| > Dnia [b]'.date('d.m.Y').'[/b] o godzinie [b]'.date('H:i').'[/b] do Twojego konta zostało dodane [b]'.$params['arguments'][3].' expa[/b]. Twój aktualny stan konta wynosi [b]'.($result['exp_bonus'] + $params['arguments'][3]).' dodatkowego expa[/b]. Wpisz [b]!poziom[/b] po więcej informacji.');
						$powerBot->log('[EXP] '.$params['executor']['client_nickname'].': '.$result['client_nickname'].' (DBID: '.$params['arguments'][2].'): +'.$params['arguments'][3], '', false, true);
					}
					else
						$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=red][b]Błąd:[/b][/color] Ilość expa musi być liczbą.');
				}
				else if($params['arguments'][1] == 'usun')
				{
					if(is_numeric($params['arguments'][3]))
					{
						if($result['exp_bonus'] - $params['arguments'][3] >= 0)
						{
							$mysql_query->query("UPDATE `clients` SET `exp_bonus` = ".($result['exp_bonus'] - $params['arguments'][3])." WHERE `client_database_id` = ".$params['arguments'][2].";");
							$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=green][b]Sukces:[/b][/color] Poprawnie usunięto exp. Stan konta użytkownika [b]'.$result['client_nickname'].'[/b] wynosi teraz [b]'.($result['exp_bonus'] - $params['arguments'][3]).' expa[/b].');
							$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$params['arguments'][2].'| > Dnia [b]'.date('d.m.Y').'[/b] o godzinie [b]'.date('H:i').'[/b] z Twojego konta zostało usunięte [b]'.$params['arguments'][3].' expa[/b]. Twój aktualny stan konta wynosi [b]'.($result['exp_bonus'] - $params['arguments'][3]).' dodatkowego expa[/b]. Wpisz [b]!poziom[/b] po więcej informacji.');
							$powerBot->log('[EXP] '.$params['executor']['client_nickname'].': '.$result['client_nickname'].' (DBID: '.$params['arguments'][2].'): -'.$params['arguments'][3], '', false, true);
						}
						else
							$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=red][b]Błąd:[/b][/color] Nie można usunąć tyle expa, gdyż stan konta użytkownika [b]'.$result['client_nickname'].'[/b] będzie ujemny.');
					}
					else
						$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=red][b]Błąd:[/b][/color] Ilość expa musi być liczbą.');
				}
				else if($params['arguments'][1] == 'wyzeruj')
				{
					$mysql_query->query("UPDATE `clients` SET `exp_bonus` = 0 WHERE `client_database_id` = ".$params['arguments'][2].";");
					$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=green][b]Sukces:[/b][/color] Poprawnie wyzerowano stan konta użytkownika [b]'.$result['client_nickname'].'[/b].');
					$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$params['arguments'][2].'| > Dnia [b]'.date('d.m.Y').'[/b] o godzinie [b]'.date('H:i').'[/b] Twój dodatkowy exp został wyzerowany.');
					$powerBot->log('[EXP] '.$params['executor']['client_nickname'].': '.$result['client_nickname'].' (DBID: '.$params['arguments'][2].'): empty', '', false, true);
				}
				else if($params['arguments'][1] == 'ustaw')
				{
					if(is_numeric($params['arguments'][3]))
					{
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = ".$params['arguments'][3]." WHERE `client_database_id` = ".$params['arguments'][2].";");
						$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=green][b]Sukces:[/b][/color] Poprawnie ustawiono liczbę expa. Stan konta użytkownika [b]'.$result['client_nickname'].'[/b] wynosi teraz [b]'.$params['arguments'][3].' expa[/b].');
						$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$params['arguments'][2].'| > Dnia [b]'.date('d.m.Y').'[/b] o godzinie [b]'.date('H:i').'[/b] stan Twojego konta ustawiono na [b]'.$params['arguments'][3].' expa[/b]. Wpisz [b]!poziom[/b] po więcej informacji.');
						$powerBot->log('[EXP] '.$params['executor']['client_nickname'].': '.$result['client_nickname'].' (DBID: '.$params['arguments'][2].'): set '.$params['arguments'][3], '', false, true);
					}
					else
						$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=red][b]Błąd:[/b][/color] Ilość expa musi być liczbą.');
				}
				else if($params['arguments'][1] == 'mnoznik')
				{
					if(is_numeric($params['arguments'][3]))
					{
						$mysql_query->query("UPDATE `clients` SET `exp_multiplier` = ".$params['arguments'][3]." WHERE `client_database_id` = ".$params['arguments'][2].";");
						$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=green][b]Sukces:[/b][/color] Poprawnie ustawiono mnożnik expa konta użytkownika [b]'.$result['client_nickname'].'[/b] na [b]'.$params['arguments'][3].'[/b].');
						$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$params['arguments'][2].'| > Dnia [b]'.date('d.m.Y').'[/b] o godzinie [b]'.date('H:i').'[/b] mnożnik expa Twojego konta ustawiono na [b]'.$params['arguments'][3].'[/b]. Wpisz [b]!poziom[/b] po więcej informacji.');
						$powerBot->log('[EXP] '.$params['executor']['client_nickname'].': '.$result['client_nickname'].' (DBID: '.$params['arguments'][2].'): multiplier set '.$params['arguments'][3], '', false, true);
					}
					else
						$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=red][b]Błąd:[/b][/color] Mnożnik musi być liczbą.');
				}
			}
			else
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=red][b]Błąd:[/b][/color] Nie znaleziono takiego użytkownika w bazie danych.');
		}
	}
}