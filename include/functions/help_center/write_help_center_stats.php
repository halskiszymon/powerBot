<?php
class write_help_center_stats
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

		if(file_exists('include/cache/help_center_notifier.json'))
			$cache = json_decode(file_get_contents('include/cache/help_center_notifier.json'), true);

		foreach(self::$function['channels'] as $stat => $channel)
		{
			unset($desc);
			if($stat == 'served_users')
			{
				$count = 0;
				if(!isset($cache['history']['served'][date('Y')][date('m')][date('j')]))
				{
					$name = str_replace("{SERVED_USERS}", 0, $channel['channel_name']); 
					if($powerBot->checkData($channel['channel_id'], 'channel_name', $name))
						$powerBot->checkErrors(self::$function['name'], "write_served_users", $ts_query->channelEdit($channel['channel_id'], array('channel_description' => '[size=10]» | Obsłużone osoby: brak[/size]'.$powerBot->insertFooter(), 'channel_name' => $name)));
					continue;
				}
				$this_day_served = $cache['history']['served'][date('Y')][date('m')][date('j')];
				foreach($this_day_served as $client)
				{
					$client_info = $powerBot->getClientInfo('client_database_id', $client['client_database_id']);
					if(isset($desc))
						$desc .= ', [b]'.$client_info['client_nickname'].'[/b]';
					else
						$desc = '[b]'.$client_info['client_nickname'].'[/b]';
					$count++;
				}
				$name = str_replace("{SERVED_USERS}", $count, $channel['channel_name']);
				if($powerBot->checkData($channel['channel_id'], 'channel_name', $name))
					$powerBot->checkErrors(self::$function['name'], "write_served_users", $ts_query->channelEdit($channel['channel_id'], array('channel_description' => '[size=10]» | Obsłużone osoby: '.$desc.'[/size]'.$powerBot->insertFooter(), 'channel_name' => $name)));
			}
			if($stat == 'help_center_stats')
			{
				if(!$powerBot->isTimeForModule('help_center_stats', $channel['interval']))
					continue;
				$stats = array();
				$result = $mysql_query->query('SELECT * FROM `help_center_time_spent` WHERE `client_database_id` = 0;');
				$default = $result->fetch_assoc();
				foreach(explode(",", $default['days']) as $day)
				{
					$date = explode(".", $day);
					if(isset($cache['stats'][$date[2]][$date[1]][$date[0]]))
						foreach($cache['stats'][$date[2]][$date[1]][$date[0]] as $admin => $clients)
							if(isset($stats[$admin]))
								$stats[$admin] += $clients;
							else
								$stats[$admin] = $clients;
					/*$day = (int) $day;
					echo $day;
					if(checkdate($month, $day, $year))
					{
						echo '-tak1 ';
						if(isset($cache['stats'][$year][$month][$day]))
							foreach($cache['stats'][$year][$month][$day] as $admin => $clients)
								if(isset($stats[$admin]))
									$stats[$admin] += $clients;
								else
									$stats[$admin] = $clients;
					}
					else if(checkdate($month+1, $day, $year))
					{
						echo '-tak2 ';
						$month++;
						foreach($cache['stats'][$year][$month][$day] as $admin => $clients)
							if(isset($stats[$admin]))
								$stats[$admin] += $clients;
							else
								$stats[$admin] = $clients;
					}
					else if(checkdate($month+1, $day, $year+1))
					{
						echo '-tak3 ';
						$month++;
						$year++;
						foreach($cache['stats'][$year][$month][$day] as $admin => $clients)
							if(isset($stats[$admin]))
								$stats[$admin] += $clients;
							else
								$stats[$admin] = $clients;
					}*/
				}
				$result = $mysql_query->query('SELECT * FROM `help_center_time_spent`;');
				$desc = '[hr]\n[size=13]Statystyki z Centrum Pomocy:[/size]\n[size=10]';
				$admins = '';
				foreach(mysqli_fetch_all($result, MYSQLI_ASSOC) as $client)
				{
					if($client['client_database_id'] == 0)
						continue;
					$client_info = $powerBot->getClientInfo('client_database_id', $client['client_database_id']);
					$admins .= '\n\n     » | [url=client://0/'.$client_info['client_unique_identifier'].'][color=red]'.$client_info['client_nickname'].'[/color][/url] spędził [b]'.$powerBot->format_seconds($client['time'], true, true, true, true).'[/b] na randze. W tym czasie obsłużył';
					if(isset($stats[$client['client_database_id']]))
						$admins .= ' [b]'.$stats[$client['client_database_id']].' osób[/b].';
					else
						$admins .= ' [b]0 osób[/b].';
				}
				$desc .= $admins.'[/size]';
				$desc .= $powerBot->insertFooter();
				if($powerBot->checkData($channel['channel_id'], 'channel_description', $desc))
					$powerBot->checkErrors(self::$function['name'], "write_help_center_stats", $ts_query->channelEdit($channel['channel_id'], array('channel_description' => $desc)));
			}
		}
		
	}
}