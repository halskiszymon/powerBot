<?php
class private_channels_manager
{
	private static $function;
	
	public static function register($name)
	{
		global $config;
		self::$function = $config['functions'][$name];
		self::$function['name'] = $name;
		echo "    > '".$name."' - zarejestrowano\n";
	}

	private static function getPreviousChannel($number)
	{
		global $mysql_query;
		$result = $mysql_query->query('SELECT * FROM `private_channels` WHERE `channel_number` = '.$number.';');
		if($result->num_rows == 0)
			return 0;
		else
		{
			$channel = $result->fetch_assoc();
			return $channel['channel_id'];
		}
	}

	private static function addOwnerToChannel($cid, $dbid)
	{
		global $ts_query;
		$channel_data = $ts_query->getElement('data', $ts_query->channelInfo($cid));
		$topic = explode(" | ", $channel_data['channel_topic']);
		$ts_query->channelEdit($cid, array('channel_topic' => $topic[0].' | '.$topic[1].','.$dbid));
		$ts_query->channelGroupAddClient(self::$function['groups']['owner'], $cid, $dbid);
	}

	private static function getChannelDate($cid)
	{
		global $ts_query;
		$channel_data = $ts_query->getElement('data', $ts_query->channelInfo($cid));
		$topic = explode(" | ", $channel_data['channel_topic']);
		return $topic[0];
	}

	private static function createFreeChannel($number)
	{
		global $powerBot, $ts_query;
		$order = self::getPreviousChannel($number - 1);
		$result = $ts_query->channelCreate(array
		(
			'channel_name' => $number.'. Kanał prywatny - wolny',
			'channel_topic' => '#free#',
			'cpid' => self::$function['channels_zone'],
			'channel_flag_semi_permanent' => 0,
			'channel_flag_permanent' => 1,
			'channel_flag_maxclients_unlimited' => 0,
			'channel_flag_maxfamilyclients_unlimited' => 0,
			'channel_flag_maxfamilyclients_inherited' => 0,
			'channel_maxclients' => 0,
			'channel_maxfamilyclients' => 0,
			'channel_description' => '[center][size=15][b]Ten kanał jest wolny.[/b][/size]\n\n[size=12]Jeżeli chcesz otrzymać swoje prywatne miejsce do rozmów, wejdź na odpowiedni kanał na centrum pomocy i poczekaj aż nasz system automatycznie przydzieli Ci jeden z wolnych kanałów. Pamiętaj, że musisz być zarejestrowanym użytkownikiem. Więcej informacji uzykasz pisząc [b]!kanał[/b] do głównego bota.[/size][/center]'.$powerBot->insertFooter(),
			'channel_order' => $order
		));
		$powerBot->checkErrors(self::$function['name'], 'create_free_channel', $result);
		return $result['data']['cid'];
	}

	private static function addChannelToDatabase($number, $channel)
	{
		global $mysql_query;
		$result = $mysql_query->query('SELECT * FROM `private_channels` WHERE `channel_number` = '.$number.';'); 
		if($result->num_rows > 0)
			$mysql_query->query('DELETE FROM `private_channels` WHERE `channel_number` = '.$number.';');
		$type = 0;
		if($channel['channel_topic'] == '#free#')
			$type = 1;
		$mysql_query->query('INSERT INTO `private_channels`(`channel_number`, `channel_id`, `channel_type`) VALUES ('.$number.', '.$channel['cid'].', '.$type.');');
	}
	
	public static function execute()
	{
		global $powerBot, $ts_query, $mysql_query, $ts_data;

		$private_channels = array();

		//inicjalizacja kanałów prywatnych

		foreach($ts_data['channels'] as $channel)
			if($channel['pid'] == self::$function['channels_zone'])
				$private_channels[] = $channel;

		//sprawdzanie numerku kanału i innych wartości

		$number = 0;
		foreach($private_channels as $index => $channel)
		{
			$number++;
			$result = $mysql_query->query('SELECT * FROM `private_channels` WHERE `channel_id` = '.$channel['cid'].';');
			if($result->num_rows == 0)
				self::addChannelToDatabase($number, $channel);
			else
			{
				$channel_data = $result->fetch_assoc();
				if($channel_data['channel_type'])
					if($channel['channel_topic'] != '#free#')
						$mysql_query->query('UPDATE `private_channels` SET `channel_type` = 0 WHERE `channel_id` = '.$channel['cid'].';');
				else
					if($channel['channel_topic'] == '#free#')
						$mysql_query->query('UPDATE `private_channels` SET `channel_type` = 1 WHERE `channel_id` = '.$channel['cid'].';');
				if(strpos($channel['channel_name'], $channel_data['channel_number'].'. ') === false || strpos($channel['channel_name'], $channel_data['channel_number'].'. ') > 0)
				{
					$ts_query->channelEdit($channel['cid'], array('channel_name' => $channel_data['channel_number'].'. <nieprawidłowa nazwa kanału>'));
					$private_channels[$index]['channel_name'] = $channel_data['channel_number'].'. <nieprawidłowa nazwa kanału>';
					$owners = explode(" | ", $channel['channel_topic']);
					foreach(explode(",", $owners[1]) as $dbid)
					{
						$client = $powerBot->getClientInfo('client_database_id', $dbid);
						if(isset($client['clid']))
							$powerBot->request('second_instance', "sendMessage|1|".$client['clid']."| > [b]".$client['client_nickname']."[/b], Twój kanał posiada złą nazwę. Pamiętaj, że w nazwie kanału musi znajdować się jego numer.\n > Zmieniając nazwę kanału, zastosuj się do wzoru: '".$channel_data['channel_number'].". <nazwa kanału>'.");
					}
				}
			}
		}

		//aktualizowanie daty jeżeli ktoś jest na kanale
		
		$number = 0;
		foreach($private_channels as $index => $channel)
		{
			$number++;
			if(strpos($channel['channel_topic'], '#free#') !== false)
				continue;

			if(strpos($channel['channel_topic'], 'urlop') !== false)
				continue;

			$channel_date = self::getChannelDate($channel['cid']);

			if($channel_date != date('d.m.Y'))
			{
				$clients_on_channel = $ts_query->getElement('data', $ts_query->channelClientList($channel['cid']));
				foreach($clients_on_channel as $client)
					foreach(self::$function['groups'] as $group)
						if($powerBot->hasChannelGroup($channel['cid'], $group, $client['client_database_id']))
						{
							$channel_data = $ts_query->getElement('data', $ts_query->channelInfo($channel['cid']));
							$topic = explode(" | ", $channel_data['channel_topic']);
							$ts_query->channelEdit($channel['cid'], array('channel_topic' => date('d.m.Y').' | '.$topic[1]));
							$private_channels[$index]['channel_topic'] = date('d.m.Y').' | '.$topic[1];
							break 2;
						}
			}

			//sprawdzanie daty kanału

			$channel_date = explode(".", $channel_date);
			$channel_time = mktime(0, 0, 0, $channel_date[1], $channel_date[0], $channel_date[2]);
			if($channel_time > time())
			{
				$channel_data = $ts_query->getElement('data', $ts_query->channelInfo($channel['cid']));
				$topic = explode(" | ", $channel_data['channel_topic']);
				$ts_query->channelEdit($channel['cid'], array('channel_topic' => date('d.m.Y').' | '.$topic[1]));
			}
			$time['warning'] = mktime(0, 0, 0, $channel_date[1], ($channel_date[0] + 5), $channel_date[2]);
			$time['delete'] = mktime(0, 0, 0, $channel_date[1], ($channel_date[0] + 7), $channel_date[2]);

			if(time() >= $time['delete'])
			{
				$ts_query->channelDelete($channel['cid']);
				$mysql_query->query('DELETE FROM `private_channels` WHERE `channel_id` = '.$channel['cid'].';');

				$cid = self::createFreeChannel($number);
				$channel_data = $ts_query->getElement('data', $ts_query->channelInfo($cid));
				$channel_data['cid'] = $cid;
				self::addChannelToDatabase($number, $channel_data);

				$owners = explode(" | ", $channel['channel_topic']);
				foreach(explode(",", $owners[1]) as $dbid)
					$powerBot->request('second_instance', "sendOfflineMessage|1|".$dbid."| > Dnia [b]".date('d.m.Y')."[/b] o godzinie [b]".date('H:i')."[/b] Twój kanał prywatny o numerze [b]".$number."[/b] został usunięty, gdyż jego data nie była aktualizowana.\n > Jeżeli chcesz założyć nowy kanał prywatny napisz na tym czacie [b]!kanał załóż[/b].");
				unset($private_channels[$index]);
			}
			else if(time() >= $time['warning'] && strpos($channel['channel_name'], self::$function['warning_text']) === false)
			{
				if((mb_strlen($channel['channel_name']) + mb_strlen(self::$function['warning_text'])) > 40)
					$name = mb_substr($channel['channel_name'], 0, (mb_strlen($channel['channel_name']) - mb_strlen(self::$function['warning_text']))).self::$function['warning_text'];
				else
					$name = $channel['channel_name'].self::$function['warning_text'];

				if($ts_query->getElement('success', $ts_query->channelEdit($channel['cid'], array('channel_name' => $name))))
				{
					$private_channels[$index]['channel_name'] = $name;

					$owners = explode(" | ", $channel['channel_topic']);
					foreach(explode(",", $owners[1]) as $dbid)
						$powerBot->request('second_instance', "sendOfflineMessage|1|".$dbid."| > Dnia [b]".date('d.m.Y')."[/b] o godzinie [b]".date('H:i')."[/b] nasz system wystawił ostrzeżenie dotyczące Twojego kanału prywatnego o numerze [b]".$number."[/b].\n > Zaaktualizuj jego datę wpisując na tym czacie [b]!kanał data[/b] bądź udaj się na niego i poczekaj chwilkę.");
				}
			}
			else if(time() < $time['warning'] && strpos($channel['channel_name'], self::$function['warning_text']) !== false)
			{
				$name = substr($channel['channel_name'], 0, mb_strlen($channel['channel_name']) - mb_strlen(self::$function['warning_text']));
				$ts_query->channelEdit($channel['cid'], array('channel_name' => $name));
				$private_channels[$index]['channel_name'] = $name;
			}
		}

		//tworzenie kanału jeżeli jakiegoś gdzieś nie ma

		$powerBot->refreshData('channels');
		for($i = 0; $i < count($private_channels); $i++)
		{
			$private_channels = array();
			foreach($ts_data['channels'] as $channel)
				if($channel['pid'] == self::$function['channels_zone'])
					$private_channels[] = $channel;

			$channel = $private_channels[$i];
			$channel_number = $i + 1;
			if(strpos($channel['channel_name'], $channel_number.'. ') === false)
			{
				$cid = self::createFreeChannel($channel_number);
				$channel_data = $ts_query->getElement('data', $ts_query->channelInfo($cid));
				$channel_data['cid'] = $cid;
				self::addChannelToDatabase($channel_number, $channel_data);
				$i = 0;
			}
		}

		//tworzenie kolejnych wolnych kanałów

		$free_channels = 0;
		$all_channels = sizeof($private_channels);

		foreach($private_channels as $channel)
			if(strpos($channel['channel_topic'], '#free#') !== false)
				$free_channels++;

		if($free_channels < self::$function['min_free_channels'])
			for($i = 0; $free_channels < self::$function['min_free_channels']; $i++)
			{
				$all_channels++;
				$free_channels++;
				$cid = self::createFreeChannel($all_channels);
				$channel_data = $ts_query->getElement('data', $ts_query->channelInfo($cid));
				$channel_data['cid'] = $cid;
				self::addChannelToDatabase($all_channels, $channel_data);
			}
	}
}