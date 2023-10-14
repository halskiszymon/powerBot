<?php
class get_private_channel
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
			$msg = "Jeżeli chcesz otrzymać kanał, proszę wyłącz status away i wróć na ten kanał.";
		else if($reason == 'client_input_muted')
			$msg = "Jeżeli chcesz otrzymać kanał, proszę włącz swój mikrofon i wróć na ten kanał.";
		else if($reason == 'client_output_muted')
			$msg = "Jeżeli chcesz otrzymać kanał, proszę odcisz swoje głośniki i wróć na te`n kanał.";
		else if($reason == 'ignored_group')
			$msg = "Nie posiadasz odpowiedniej grupy, aby móc otrzymać kanał prywatny.";
		$ts_query->clientPoke($clid, $msg);
		$ts_query->clientKick($clid, 'channel');
	}
	
	public static function execute()
	{
		global $powerBot, $ts_query, $ts_data;

		foreach($ts_query->getElement('data', $ts_query->channelClientList(self::$function['channel'], '-uid -away -voice -groups')) as $client)
		{
			$powerBot->refreshData('channels');

			if(!isset($client['client_database_id']))
				break;

			foreach(self::$function['ignored_groups'] as $group)
					if($powerBot->hasGroup($client['client_servergroups'], $group)) { self::clientKick($client['clid'], 'ignored_group'); continue 2; }

			/*if($client['client_away']) { self::clientKick($client['clid'], 'client_away'); continue; }
			else if($client['client_input_muted']) { self::clientKick($client['clid'], 'client_input_muted'); continue; }
			else if($client['client_output_muted']) { self::clientKick($client['clid'], 'client_output_muted'); continue; }*/

			foreach($ts_query->getElement('data', $ts_query->channelGroupClientList(NULL, $client['client_database_id'])) as $info)
				if($info['cldbid'] == $client['client_database_id'] && $info['cgid'] == self::$function['groups']['owner'])
				{
					$channel = $ts_query->getElement('data', $ts_query->channelInfo($info['cid']));
					if($channel['pid'] == self::$function['channels_zone'])
					{
						$ts_query->clientPoke($client['clid'], 'Posiadasz już swój prywatny kanał. Zostałeś na niego przeniesiony.');
						$ts_query->clientMove($client['clid'], $info['cid']);
						continue 2;
					}
				}

			$number = 0;
			foreach($ts_data['channels'] as $channel)
				if($channel['pid'] == self::$function['channels_zone'])
				{
					$number++;
					if($channel['channel_topic'] == '#free#')
					{
						$ts_query->channelEdit($channel['cid'], array(
							'channel_name'	=> $number.'. '.$client['client_nickname'],
							'channel_topic' => date('d.m.Y').' | '.$client['client_database_id'],
							'channel_description' => '[size=13]Kanał prywatny numer [b]#'.$number.'[/b].[/size]\n[size=11]     » | Data założenia kanału: [b]'.date('d.m.Y').'[/b]\n     » | Właściciel: [URL=client://0/'.$client['client_unique_identifier'].']'.$client['client_nickname'].'[/url][/size]'.$powerBot->insertFooter(),
							'channel_flag_maxclients_unlimited' => 1, 
							'channel_flag_maxfamilyclients_unlimited' => 1, 
							'channel_flag_maxfamilyclients_inherited' => 0,
							'channel_maxclients' => 1,
							'channel_maxfamilyclients' => 1,
							'channel_password' => 'ts3.today'
						));
						$ts_query->clientMove($client['clid'], $channel['cid']);
						$ts_query->setClientChannelGroup(self::$function['groups']['owner'], $channel['cid'], $client['client_database_id']);
						$ts_query->clientPoke($client['clid'], 'Utworzyliśmy Ci kanał. Sprawdź wiadomość prywatną po więcej szczegółów.');
						$powerBot->request('second_instance', "sendMessage|1|".$client['clid']."| > [b]".$client['client_nickname']."[/b], utworzyliśmy Ci kanał prywatny.\n > Domyślne hasło na głównym kanale to [b]ts3.today[/b]. Przypominamy, aby jak najszybciej je zmienić.\n[b][/b]\n > Data na tym kanale będzie automatycznie aktualizowana, gdy Ty bądź moderatorzy kanału będą na nim przebywać bądź jeżeli napiszesz do mnie wiadomość [b]!kanal data[/b].\n > Jeżeli data będzie przestarzała o [b]5 dni[/b] - otrzymasz ostrzeżenie. Po [b]7 dniach[/b] Twój kanał zostanie usunięty.\n[b][/b]\n > Przestrzegaj zasad netykiety, ogólnego regulaminu serwera oraz regulaminu kanałów prywatnych.\n > Jeżeli chcesz otrzymać dodatkowe podkanały, wpisz [b]!kanal podkanaly[/b].\n > Jeżeli masz jakieś pytania, wpisz [b]!pomoc[/b], postaram się udzielić odpowiedzi na wszystkie pytania.\n[b][/b]\n > Pozdrawiamy i życzmy przyjemnych rozmów.");
						continue 2;
					}
				}
		}
	}
}