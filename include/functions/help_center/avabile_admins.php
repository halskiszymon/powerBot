<?php
class avabile_admins
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
		global $powerBot, $ts_query, $ts_data;

		$admins = array();
		foreach(self::$function['channels'] as $channel)
		{
			$admins[$channel['channel_id']]['clients'] = array();
			$admin_group_clients = $ts_query->getElement('data', $ts_query->serverGroupClientList($channel['help_center_group_id']));
			foreach($admin_group_clients as $client)
			{
				if(!isset($client['cldbid']))
					break;
				$client_info = $powerBot->getClientInfo('client_database_id', $client['cldbid']);
				if($client_info['status'])
					if($client_info['client_away'] != 1 || !$client_info['client_output_muted'] || $client_info['client_idle_time'] < 240000)
						array_push($admins[$channel['channel_id']]['clients'], array
						(
							'url' => '[url=client://'.$client_info['clid'].'/'.$client_info['client_unique_identifier'].'][color=red]'.$client_info['client_nickname'].'[/color][/url]',
						));
			}
			$count = count($admins[$channel['channel_id']]['clients']);
			$desc = '[hr]\n[size=13]Administratorzy, którzy mogą ci pomóc ('.$count.'):[/size]\n[size=10]';
			if($count > 0)
			{
				foreach($admins[$channel['channel_id']]['clients'] as $admin)
					$desc .= '\n     » | '.$admin['url'].' jest [color=#55aa00][b]dostępny/a[/b][/color].\n';
				$desc .= '[/size]';
			}
			else
				$desc .= '     » | Niestety, ale aktualnie żaden z administratorów nie jest w stanie Ci pomóc.\n[/size]';
			$desc .= $powerBot->insertFooter();
			$name = str_replace("{AVABILE_ADMINS}", $count, $channel['channel_name']);
			if($powerBot->checkData($channel['channel_id'], 'channel_name', $name))
				$powerBot->checkErrors(self::$function['name'], "write_avabile_admins", $ts_query->channelEdit($channel['channel_id'], array('channel_description' => $desc, 'channel_name' => $name)));
		}
	}
}