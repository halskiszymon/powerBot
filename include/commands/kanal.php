<?php
class kanal
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

		$client_channel_id = false;
		$cgcl = $ts_query->getElement('data', $ts_query->channelGroupClientList(NULL, $params['executor']['client_database_id']));
		if(is_array($cgcl))
			foreach($cgcl as $info)
				if($info['cldbid'] == $params['executor']['client_database_id'] && $info['cgid'] == self::$command['private_channel_owner_group_id'])
				{
					$channel = $ts_query->getElement('data', $ts_query->channelInfo($info['cid']));
					if($channel['pid'] == self::$command['private_channels_zone'])
						$client_channel_id = $info['cid'];
				}

		if(!isset($params['arguments'][1]))
			if($client_channel_id != false)
			{
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Zostałeś przeniesiony na swój kanał prywatny.');
				$ts_query->clientMove($params['executor']['clid'], $info['cid']);
				return;
			}
			else
			{
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Za chwilę utworzymy Ci kanał prywatny. Poczekaj chwilkę.');
				$ts_query->clientMove($params['executor']['clid'], self::$command['get_private_channel_id']);
			}
		else if($params['arguments'][1] == 'data')
			if($client_channel_id != false)
			{
				$topic = explode(" | ", $channel['channel_topic']);
				if($topic[0] != date('d.m.Y'))
				{
					$ts_query->channelEdit($info['cid'], array('channel_topic' => date('d.m.Y').' | '.$topic[1]));
					$ts_query->sendMessage(1, $params['executor']['clid'], ' > Data na Twoim prywatnym kanale została zaaktualizowana.');
				}
				else
					$ts_query->sendMessage(1, $params['executor']['clid'], ' > Data na Twoim prywatnym kanale jest aktualna.');
			}
			else
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Nie posiadasz swojego kanału prywatnego. Wpisz [b]!kanal[/b] by go utworzyć.');
		else if($params['arguments'][1] == 'podkanaly')
			if($client_channel_id != false)
			{
				$subchannels = 0;
				foreach($ts_data['channels'] as $channel)
					if($channel['pid'] == $client_channel_id)
						$subchannels++;
				if($subchannels == self::$command['max_subchannels'])
					$ts_query->sendMessage(1, $params['executor']['clid'], ' > Twój kanał osiągnął maksymalną liczbę podkanałów.');
				else
				{
					if(!isset($params['arguments'][2]))
						$ts_query->sendMessage(1, $params['executor']['clid'], ' > Musisz wpisać ile mam utworzyć podkanałów. Do swojego pokoju możesz dodać jeszcze [b]'.(self::$command['max_subchannels'] - $subchannels).' podkanały[/b]. Wpisz [b]!kanal podkanaly <ilosc>[/b].');
					else if(!is_numeric($params['arguments'][2]))
						$ts_query->sendMessage(1, $params['executor']['clid'], ' > Wpisałeś niepoprawną wartość ilości podkanałów - musisz podać liczbę. Wpisz [b]!kanal podkanaly <ilosc (liczba)>[/b].');
					else
						if($params['arguments'][2] > (self::$command['max_subchannels'] - $subchannels))
							$ts_query->sendMessage(1, $params['executor']['clid'], ' > Nie możesz utworzyć tylu kanałów. Do swojego pokoju możesz dodać jeszcze [b]'.(self::$command['max_subchannels'] - $subchannels).' podkanały[/b]. Wpisz [b]!kanal podkanaly <ilosc>[/b].');
						else
						{
							for($i = 0; $i < $params['arguments'][2]; $i++)
							{
								$subchannels++;
								$result = $ts_query->channelCreate(array
								(
									'channel_name' => 'Podkanał nr. '.$subchannels,
									'cpid' => $client_channel_id,
									'channel_flag_semi_permanent' => 0,
									'channel_flag_permanent' => 1,
									'channel_flag_maxclients_unlimited' => 1, 
									'channel_flag_maxfamilyclients_unlimited' => 1, 
									'channel_flag_maxfamilyclients_inherited' => 0,
									'channel_maxclients' => 1,
									'channel_maxfamilyclients' => 1
								));
								$powerBot->checkErrors(self::$command['name'], 'create_subchannel', $result);
							}
							$ts_query->sendMessage(1, $params['executor']['clid'], ' > Pomyślnie dodano do Twojego kanału [b]'.$params['arguments'][2].' podkanały[/b].');
						}
				}
			}
			else
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Nie posiadasz swojego kanału prywatnego. Wpisz [b]!kanal[/b] by go utworzyć.');
	}
}