<?php
class top_week
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
		global $powerBot, $ts_query, $mysql_query;

		$cache = array();
		$tmp = array();
		$count = 0;

		if(file_exists('include/cache/'.self::$function['name'].'.json'))
			$cache = json_decode(file_get_contents('include/cache/'.self::$function['name'].'.json'), true);

		if(isset($cache['week']))
		{
			$week = $cache['week'];
			if($week != date('W'))
			{
				$week = date('W');
				$randoms = array_rand($cache['top'], 3);

				foreach($cache['last_winners']['main'] as $client)
					$ts_query->serverGroupDeleteClient(self::$function['reward_group_id'], $client['client_database_id']);

				for($i = 0; $i < 3; $i++)
				{
					$cache['last_winners']['main'][$i]['client_database_id'] = $randoms[$i]['client_database_id'];
					$cache['last_winners']['main'][$i]['time'] = $randoms[$i]['time'];
					$cache['last_winners']['main'][$i]['idle'] = $randoms[$i]['idle'];
					$ts_query->serverGroupAddClient(self::$function['reward_group_id'], $randoms[$i]['client_database_id']);
					$powerBot->request('second_instance', "sendOfflineMessage|1|".$randoms[$i]['client_database_id']."| > Gratulacje! Zostałeś wybrany przez nas jako stały bywalec minionego tygodnia! Otrzymujesz specjalną rangę. W opisie kanału z rankingiem, sprawdzisz swój zdobyty wynik. Ciesz się nagordą i dziękujemy za tyle spędzonego czasu na serwerze! <3");
				}


				foreach($cache['last_winners']['others'] as $client)
					$ts_query->serverGroupDeleteClient(self::$function['other_reward_group_id'], $client['client_database_id']);

				$i = 0;
				foreach($cache['top'] as $client)
				{
					if($client['client_database_id'] != $randoms[0]['client_database_id'] && $client['client_database_id'] != $randoms[1]['client_database_id'] && $client['client_database_id'] != $randoms[2]['client_database_id'])
					{
						$cache['last_winners']['others'][$i]['client_database_id'] = $client['client_database_id'];
						$ts_query->serverGroupAddClient(self::$function['other_reward_group_id'], $client['client_database_id']);
						$powerBot->request('second_instance', "sendOfflineMessage|1|".$randoms[$i]['client_database_id']."| > Cześć, niestety w tym tygodniu nie zostałeś wybrany przez nas na stałego bywalca. Mimo to, otrzymujesz nagrodę pocieszenia, gydż jesteś w pierwszej dwudziestce.");
					}
					$i++;
				}
				/*foreach($cache['top'] as $client)
				{
					if(isset($cache['last_winner']['client_database_id']))
					{
						if($cache['last_winner']['client_database_id'] != $client['client_database_id'])
						{
							$ts_query->serverGroupDeleteClient(self::$function['reward_group_id'], $cache['last_winner']['client_database_id']);
							$powerBot->request('second_instance', "sendOfflineMessage|1|".$cache['last_winner']['client_database_id']."| > Niestety, w tym tygodniu nie zostałeś najaktywniejszym użytkownikiem tygodnia. Ranga [b]Użytkownik Premium[/b] została odebrana. Zachęcamy do dalszego udziału w naszej zabawie! <3");
						}
					}

					$result = $mysql_query->query("SELECT * FROM `shop_purchased_services` WHERE `client_database_id` = ".$client['client_database_id']." AND `status` = 'active' AND `service_id` = 7;");

					$ts_query->serverGroupAddClient(self::$function['reward_group_id'], $client['client_database_id']);

					//dodawanie nagrody

					
					pamietać aby zabierało rangi anty i jak ktoś kupił usera premium to ma mu nie zabierać rangi
					if(isset($cache['last_winner']['client_database_id']))
						$ts_query->serverGroupDeleteClient(self::$function['reward_group_id'], $cache['last_winner']['client_database_id']);

					$ts_query->serverGroupAddClient(self::$function['reward_group_id'], $cache['last_winner']['client_database_id']);
					$powerBot->request('second_instance', "sendOfflineMessage|1|".$client['client_database_id']."| > Gratulacje! Zostałeś użytkownikiem tygodnia! W opisie kanału z rankingiem, sprawdzisz swój zdobyty wynik. Ciesz się nagordą i dziękujemy za tyle spędzonego czasu na serwerze! <3");
					$cache['last_winner']['client_database_id'] = $client['client_database_id'];
					$cache['last_winner']['time'] = $client['time'];
					$cache['last_winner']['idle'] = $client['idle'];

					empty($cache['top']);
					break;
				}*/
			}
		}
		else
			$week = date('W');
		$cache['week'] = $week;


		$desc = '[size=13]Na naszym serwerze najaktywniejsi użytkownicy są nagradzani. Co tydzień, użytkownik z największym spędzonym czasem na serwerze otrzymuje [b]rangę Premium[/b] na okres [b]7 dni[/b].';
		if(isset($cache['last_winner']))
		{
			//$percent = floor((100*$cache['last_winner']['idle'])/$cache['last_winner']['time']);
			$diff = $cache['last_winner']['time'] - $cache['last_winner']['idle'];
			$client_info = $powerBot->getClientInfo('client_database_id', $cache['last_winner']['client_database_id']);
			$desc .= ' Ostatnio nagrodzony został [url=client://0/'.$client_info['client_unique_identifier'].'][color=red]'.$client_info['client_nickname'].'[/color][/url] (Spędzony czas: [b]'.$powerBot->format_seconds($diff, true, true, true, false).'[/b]).';
		}
		$desc .= '[/size]\n\n[size=8][b]Pamiętajmy![/b] Aktywność oznacza [b]rozmowę[/b] z innymi użytkownikami. Aby każdy miał równe szanse, użytkownicy, którzy będą stosować nieuczciwe praktyki w celu zdobycia wyższych miejsc w rankingu, będą z niego [b]usuwani w danym tygodniu[/b] po uprzednim ostrzeżeniu. Rankingi są tylko [b]formą zabawy[/b] urozmaicającą spędzanie czasu na naszym TeamSpeaku, nie służą one do udowodnienia czegokolwiek. Decyzje administratorów [b]nie podlega dyskusji[/b].\n[i]W razie jakichkolwiek pytań, prosimy kierować je do administracji naszego TeamSpeaka.[/i][/size]\n\n[hr][size=9]';

		$result = $mysql_query->query('SELECT `client_database_id`,`week_idle_time_spent`,`week_time_spent` FROM `clients` WHERE `week` = '.$week.' ORDER BY `week_time_spent` - `week_idle_time_spent` DESC LIMIT 100;');
		foreach(mysqli_fetch_all($result, MYSQLI_ASSOC) as $client)
		{
			$count++;
			if($count > 20)
				break;
			if($client['week_time_spent'] == 0)
				continue;
			$diff = $client['week_time_spent'] - $client['week_idle_time_spent'];
			//$percent = floor((100*$client['week_idle_time_spent'])/$client['week_time_spent']);
			$client_info = $powerBot->getClientInfo('client_database_id', $client['client_database_id']);
			$desc .= '\n\n     [b]» | Miejsce #'.$count.'[/b]: [url=client://0/'.$client_info['client_unique_identifier'].'][color=red]'.$client_info['client_nickname'].'[/color][/url] - [url=https://ts3.today/?profile&id='.$client['client_database_id'].']profil użytownika[/url] (Spędzony czas: [b]'.$powerBot->format_seconds($diff, true, true, true, true).'[/b])';
			$cache['top'][$count]['client_database_id'] = $client['client_database_id'];
			$cache['top'][$count]['time'] = $client['week_time_spent'];
			$cache['top'][$count]['idle'] = $client['week_idle_time_spent'];
		}
		$desc .= '[/size]\n'.$powerBot->insertFooter();
		file_put_contents('include/cache/'.self::$function['name'].'.json', json_encode($cache));
		$powerBot->checkErrors(self::$function['name'], "write_top_week_clients", $ts_query->channelEdit(self::$function['channel_id'], array('channel_description' => $desc)));

	}
}