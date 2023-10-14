<?php
class admin_list
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
		global $powerBot, $ts_query, $mysql_query, $ts_data;

		$admins = array();
		foreach(self::$function['channels'] as $list)
		{
			$desc = '[center][size=13][color=#4891da][b][u]Status[/u] [u]Administracji[/u]\n[u]Serwera[/u] [u]Ts3.Today[/u][/b][/color][/size][/center]\n';
			foreach($list['groups'] as $sgid)
			{
				$admin_group = $powerBot->getServerGroupInfo($sgid);
				$admin_group_clients = $ts_query->getElement('data', $ts_query->serverGroupClientList($sgid));
				$admins[$sgid]['clients'] = array();
				foreach($admin_group_clients as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$client_info = $powerBot->getClientInfo('client_database_id', $client['cldbid']);
					if($client_info['status'])
					{
						if(in_array($list['help_center_group_id'], explode(",", $client_info['client_servergroups'])))
							$client_info['help_center_status'] = true;
						$channel = $ts_query->getElement('data', $ts_query->channelInfo($client_info['cid']));
						if($client_info['client_away'] == 1 || $client_info['client_output_muted'] || $client_info['client_idle_time'] >= 240000)
						{
							$idle = floor($client_info['client_idle_time']/1000);
							if($idle < 60)
								$idle = 61;
							array_push($admins[$sgid]['clients'], array
							(
								'url' => '[url=client://'.$client_info['clid'].'/'.$client_info['client_unique_identifier'].'][color=red]'.$client_info['client_nickname'].'[/color][/url]',
								'status' => '          » | Status: [color=#c68400][b]Zaraz wracam[/b][/color]\n',
								'for' => '          » | Away od: [b]'.$powerBot->format_seconds($idle, true, true, true, false, 1).'[/b]\n',
								'channel' => '          » | Przebywa na kanale: [url=channelId://'.$client_info['cid'].'][color=blue][b]'.$channel['channel_name'].'[/b][/color][/url]\n', 
								'help_center_status' => '',
							));
						}
						else
							array_push($admins[$sgid]['clients'], array
							(
								'url' => '[url=client://'.$client_info['clid'].'/'.$client_info['client_unique_identifier'].'][color=red]'.$client_info['client_nickname'].'[/color][/url]',
								'status' => '          » | Status: [color=#55aa00][b]Dostępny/a[/b][/color]\n',
								'for' => '',
								'channel' => '          » | Przebywa na kanale: [url=channelId://'.$client_info['cid'].'][color=blue][b]'.$channel['channel_name'].'[/b][/color][/url]\n',
								'help_center_status' => '          » | Centrum Pomocy: '.(isset($client_info['help_center_status']) ? '[color=#319400][b]Chętnie pomogę![/b][/color]\n' : '[color=#ffaa00][b]Przepraszam, lecz jestem zajęty.[/b][/color]\n'),
							));
					}
					else
					{
						$result = $mysql_query->query("SELECT `last_update` FROM `clients` WHERE `client_database_id` = ".$client['cldbid'].";");
						if($result->num_rows == 1)
						{
							$record = $result->fetch_assoc();
							$lastcon = $record['last_update'];
						}
						else
							$lastcon = $client_info['client_lastconnected'];
						array_push($admins[$sgid]['clients'], array
						(
							'url' => '[url=client://0/'.$client_info['client_unique_identifier'].'][color=red]'.$client_info['client_nickname'].'[/color][/url]',
							'status' => '          » | Status: [color=red][b]Niedostępny/a[/b][/color]\n',
							'for' => '          » | Offline od: [b]'.$powerBot->format_seconds((time() - $lastcon), true, true, true, false, 1).'[/b]\n',
							'channel' => ' ',
							'help_center_status' => '', 
						));
					}
				}
				$desc .= '[hr]\n[size=11][b]['.$admin_group['name'].'][b][/size]\n';
				$count = count($admins[$sgid]['clients']);
				if($count > 0)
				{
					$desc .= 'Administratorów w grupie: [b]'.$count.'[/b]\n[size=10]';
					foreach($admins[$sgid]['clients'] as $admin)
						$desc .= '\n      '.$admin['url'].':\n'.$admin['status'].''.$admin['for'].''.$admin['help_center_status'].''.$admin['channel'];
					$desc .= '[/size]';
				}
				else
					$desc .= 'Administratorów w grupie: [b]0[/b]\n[size=10]\n     » | Brak administratorów w tej grupie.[/size]\n';
			}
			$desc .= $powerBot->insertFooter();
			if($powerBot->checkData($list['channel_id'], 'channel_description', $desc))
				$powerBot->checkErrors(self::$function['name'], "write_admin_list", $ts_query->channelEdit($list['channel_id'], array('channel_description' => $desc)));
		}
	}
}