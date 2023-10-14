<?php
class help_center_notifier
{
	private static $function;
	
	public static function register($name)
	{
		global $config;
		self::$function = $config['functions'][$name];
		self::$function['name'] = $name;
		echo "    > '".$name."' - zarejestrowano\n";
	}

	private static function clientKick($clid, $reason)
	{
		global $ts_query;
		if($reason == 'client_away')
			$msg = "Jeżeli chcesz otrzymać pomoc, proszę wyłącz status away i wróć na ten kanał.";
		else if($reason == 'client_input_muted')
			$msg = "Jeżeli chcesz otrzymać pomoc, proszę włącz swój mikrofon i wróć na ten kanał.";
		else if($reason == 'client_output_muted')
			$msg = "Jeżeli chcesz otrzymać pomoc, proszę odcisz swoje głośniki i wróć na ten kanał.";
		$ts_query->clientPoke($clid, $msg);
		$ts_query->clientKick($clid, 'channel');
	}
	
	private static function getAverageWaitTime($cache)
	{
		global $powerBot;
		$average = array();
		$total = 0;
		$count = 0;
		$this_day_served = $cache['history']['served'][date('Y')][date('m')][date('j')];
		$this_day_notserved = $cache['history']['notserved'][date('Y')][date('m')][date('j')];
		foreach($this_day_served as $client)
			$average[] = $client['wait'];
		foreach($this_day_notserved as $client)
			$average[] = $client['wait'];
		foreach($average as $time)
		{
			$count++;
			$total += $time;
		}
		if($count == 0)
			return '<brak danych>';
		return $powerBot->format_seconds(floor($total/$count), false, false, true, true);
	}

	public static function execute()
	{
		global $powerBot, $ts_query, $ts_data;

		if(file_exists('include/cache/'.self::$function['name'].'.json'))
			$cache = json_decode(file_get_contents('include/cache/'.self::$function['name'].'.json'), true);

		if(!isset($cache['clients_on_channel']))
			$cache['clients_on_channel'] = array();

		if(!isset($cache['history']['notserved'][date('Y')][date('m')][date('j')]))
			$cache['history']['notserved'][date('Y')][date('m')][date('j')] = array();

		if(!isset($cache['history']['served'][date('Y')][date('m')][date('j')]))
			$cache['history']['served'][date('Y')][date('m')][date('j')] = array();

		if(!isset($cache['stats'][date('Y')][date('m')][date('j')]))
			$cache['stats'][date('Y')][date('m')][date('j')] = array();

		foreach(self::$function['channels'] as $channel)
		{
			$help_rooms = array();
			foreach($channel['help_rooms'] as $room)
				$help_rooms[$room] = $ts_query->getElement('data', $ts_query->channelClientList($room, '-groups'));
			$clients_on_channel = $ts_query->getElement('data', $ts_query->channelClientList($channel['channel_id'], '-uid -away -voice -groups'));
			if(sizeof($clients_on_channel) == 0)
			{
				foreach($cache['clients_on_channel'] as $dbid => $client)
				{
					foreach($help_rooms as $id => $room_clients)
					{
						foreach($room_clients as $room_client)
							if(!isset($room_client['client_database_id']))
								break 1;
							else if($room_client['client_database_id'] == $dbid)
							{
								$founded_room = $room_clients;
								break 2;
							}
					}
					if(isset($founded_room))
					{
						foreach($founded_room as $room_client)
							foreach($channel['groups_to_poke'] as $group)
								if($powerBot->hasGroup($room_client['client_servergroups'], $group))
								{
									array_push($cache['history']['served'][date('Y')][date('m')][date('j')], array('client_database_id' => $dbid, 'wait' => $client['wait']));
									if(!isset($cache['stats'][date('Y')][date('m')][date('j')][$room_client['client_database_id']]))
										$cache['stats'][date('Y')][date('m')][date('j')][$room_client['client_database_id']] = 0;
									$cache['stats'][date('Y')][date('m')][date('j')][$room_client['client_database_id']]++;
								}
					}
					else
						array_push($cache['history']['notserved'][date('Y')][date('m')][date('j')], array('client_database_id' => $dbid, 'wait' => $client['wait'], 'time' => time()));
					unset($cache['clients_on_channel'][$dbid]);
				}
				continue;
			}
			foreach($clients_on_channel as $client)
			{
				if($client['client_type'])
					continue;

				if($client['client_away']) { self::clientKick($client['clid'], 'client_away'); continue; }
				else if($client['client_input_muted']) { self::clientKick($client['clid'], 'client_input_muted'); continue; }
				else if($client['client_output_muted']) { self::clientKick($client['clid'], 'client_output_muted'); continue; }

				foreach($channel['ignored_groups_on_channel'] as $group)
					if($powerBot->hasGroup($client['client_servergroups'], $group))
						continue;

				$admins = array();
				$admins_count = 0;
				foreach($channel['groups_to_poke'] as $group)
				{
					$clients_in_group = $ts_query->getElement('data', $ts_query->serverGroupClientList($group));
					foreach($clients_in_group as $client_to_poke)
					{
						if(!isset($client_to_poke['cldbid']))
							continue;
						$client_info = $powerBot->getClientInfo('client_database_id', $client_to_poke['cldbid']);
						if(!$client_info['status'])
							continue;

						foreach($channel['ignored_channels_to_poke'] as $ignoredchannel)
							if($ignoredchannel == $client_info['cid'])
								continue;

						$ts_query->clientPoke($client_info['clid'], "Poniższy użytkownik czeka na pomoc. (kliknij na niego i znajdź w drzewie kanałósw)");
						$ts_query->clientPoke($client_info['clid'], "    > [url=client://1/".$client['client_unique_identifier']."]".$client['client_nickname']."[/url]");

						foreach($channel['admin_groups'] as $admin_group)
							if($powerBot->hasGroup($client_info['client_servergroups'], $admin_group))
							{
								$group_info = $powerBot->getServerGroupInfo($admin_group);
								$admins[$group_info['name']] = array();
								array_push($admins[$group_info['name']], array('client_nickname' => $client_info['client_nickname'], 'client_unique_identifier' => $client_info['client_unique_identifier']));
							}
						$admins_count++;
					}
				}
				if(!isset($cache['clients_on_channel'][$client['client_database_id']]))
				{
					$ts_query->sendMessage(1, $client['clid'], "Witaj [b]".$client['client_nickname']."[/b] na kanale pomocy.");
					if($admins_count == 0)
					{
						$ts_query->sendMessage(1, $client['clid'], "    Aktualnie nie ma żadnego administratora, który mógłby Ci pomóc.");
						$ts_query->sendMessage(1, $client['clid'], "    Zapraszamy później i przepraszamy za problemy.");
						$ts_query->clientKick($client['clid'], 'channel');
						continue;
					}
					else if($admins_count == 1)
						$ts_query->sendMessage(1, $client['clid'], "Aktualnie jest [b]jeden administrator[/b] który może Ci pomóc.");
					else
						$ts_query->sendMessage(1, $client['clid'], "Aktualnie jest [b]".$admins_count." administratorów[/b] który może Ci pomóc.");
					foreach($admins as $name => $admins)
					{
						unset($msg);
						foreach($admins as $admin)
							if(!isset($msg))
								$msg = "[url=client://1/".$admin['client_unique_identifier']."]".$admin['client_nickname']."[/url]";
							else
								$msg = ", [url=client://1/".$admin['client_unique_identifier']."]".$admin['client_nickname']."[/url]";
						$ts_query->sendMessage(1, $client['clid'], "    > [b][".$name."][/b]: ".$msg);
					}
					if($admins_count == 1)
						$ts_query->sendMessage(1, $client['clid'], "Został on powiadomiony o Twoim pobycie. Średni czas oczekiwania: [b]".self::getAverageWaitTime($cache)."[/b]");
					else
						$ts_query->sendMessage(1, $client['clid'], "Zostali oni powiadomieni o Twoim pobycie. Średni czas oczekiwania: [b]".self::getAverageWaitTime($cache)."[/b]");

					$cache['clients_on_channel'][$client['client_database_id']]['join'] = time();
					$cache['clients_on_channel'][$client['client_database_id']]['wait'] = 0;
				}
				else
				{
					if($cache['clients_on_channel'][$client['client_database_id']]['wait'] == 10)
						$ts_query->sendMessage(1, $client['clid'], " > Ktoś napewno zaraz przyjdzie. Musisz jeszcze chwilkę poczekać.");
					else if($cache['clients_on_channel'][$client['client_database_id']]['wait'] == 30)
					{
						$ts_query->sendMessage(1, $client['clid'], " > Hmmm... Nie mogę znaleźć wolnego administratora. Najwidoczniej wszyscy są zajęci.");
						$ts_query->sendMessage(1, $client['clid'], " > Możesz tu poczekać, albo wrócić za jakiś czas. Przepraszamy za problemy.... :(");
					}
					else if($cache['clients_on_channel'][$client['client_database_id']]['wait'] == 60)
					{
						$ts_query->sendMessage(1, $client['clid'], " > Nadal nikogo nie znalazłem. Bardzo przepraszam za tą sytuację! :(");
						$ts_query->sendMessage(1, $client['clid'], " > Możesz tu poczekać, albo wrócić za jakiś czas. Przepraszamy za problemy...");
					}
					$cache['clients_on_channel'][$client['client_database_id']]['wait'] += $powerBot->getFunctionInterval(self::$function['interval']);
				}
			}
		}
		file_put_contents('include/cache/'.self::$function['name'].'.json', json_encode($cache));
	}
}