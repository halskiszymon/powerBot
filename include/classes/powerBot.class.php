<?php
class powerBot
{
	private static $queue = array();

	public static function format_seconds($seconds, $showdays, $showhours, $showminutes, $showseconds, $mode = 0)
	{
		$uptime = [];
		$uptime['days'] = floor($seconds / 86400);
		$uptime['hours'] = floor(($seconds - ($uptime['days'] * 86400)) / 3600);
		$uptime['minutes'] = floor(($seconds - (($uptime['days'] * 86400)+($uptime['hours']*3600))) / 60);
		$uptime['seconds'] = floor(($seconds - (($uptime['days'] * 86400)+($uptime['hours']*3600)+($uptime['minutes'] * 60))));
		if($showdays)
			if($mode == 0)
			{
				if($uptime['days'] > 0)
					$uptime_text = $uptime['days'].' '.($uptime['days'] == 1 ? 'dzień' : 'dni');
			}
			else if($mode == 1)
			{
				if($uptime['days'] > 0)
					$uptime_text = $uptime['days'].' '.($uptime['days'] == 1 ? 'dnia' : 'dni');
			}

		if($showhours)
			if($mode == 0)
			{
				if($uptime['hours'] > 0)
					if($uptime['hours'] == 1)
						if(!isset($uptime_text))
							$uptime_text = $uptime['hours'].' godzinę';
						else
							$uptime_text .= ', '.$uptime['hours'].' godzinę';
					else if($uptime['hours'] == 2 || $uptime['hours'] == 3 || $uptime['hours'] == 4 || $uptime['hours'] == 22 || $uptime['hours'] == 23)
						if(!isset($uptime_text))
							$uptime_text = $uptime['hours'].' godziny';
						else
							$uptime_text .= ', '.$uptime['hours'].' godziny';
					else
						if(!isset($uptime_text))
							$uptime_text = $uptime['hours'].' godzin';
						else
							$uptime_text .= ', '.$uptime['hours'].' godzin';
			}
			else if($mode == 1)
			{
				if($uptime['hours'] > 0)
					if($uptime['hours'] == 1)
						if(!isset($uptime_text))
							$uptime_text = $uptime['hours'].' godziny';
						else
							$uptime_text .= ', '.$uptime['hours'].' godziny';
					else
						if(!isset($uptime_text))
							$uptime_text = $uptime['hours'].' godzin';
						else
							$uptime_text .= ', '.$uptime['hours'].' godzin';
			}

		if($showminutes)
			if($mode == 0)
			{
				if($uptime['minutes'] > 0)
					if($uptime['minutes'] == 1)
						if(!isset($uptime_text))
							$uptime_text = $uptime['minutes'].' minutę';
						else
							$uptime_text .= ', '.$uptime['minutes'].' minutę';
					else if($uptime['minutes'] == 2 || $uptime['minutes'] == 3 || $uptime['minutes'] == 4 || $uptime['minutes'] == 22 || $uptime['minutes'] == 23 || $uptime['minutes'] == 24 || $uptime['minutes'] == 32 || $uptime['minutes'] == 33 || $uptime['minutes'] == 34 || $uptime['minutes'] == 42 || $uptime['minutes'] == 43 || $uptime['minutes'] == 44 || $uptime['minutes'] == 52 || $uptime['minutes'] == 53 || $uptime['minutes'] == 54)
						if(!isset($uptime_text))
							$uptime_text = $uptime['minutes'].' minuty';
						else
							$uptime_text .= ', '.$uptime['minutes'].' minuty';
					else
						if(!isset($uptime_text))
							$uptime_text = $uptime['minutes'].' minut';
						else
							$uptime_text .= ', '.$uptime['minutes'].' minut';
			}
			else if($mode == 1)
			{
				if($uptime['minutes'] > 0)
					if($uptime['minutes'] == 1)
						if(!isset($uptime_text))
							$uptime_text = $uptime['minutes'].' minuty';
						else
							$uptime_text .= ', '.$uptime['minutes'].' minuty';
					else
						if(!isset($uptime_text))
							$uptime_text = $uptime['minutes'].' minut';
						else
							$uptime_text .= ', '.$uptime['minutes'].' minut';
			}

		if($showseconds)
			if($mode == 0)
			{
				if($uptime['seconds'] > 0)
					if($uptime['seconds'] == 1)
						if(!isset($uptime_text))
							$uptime_text = $uptime['seconds'] . ' sekundę';
						else
							$uptime_text .= ', '.$uptime['seconds'] . ' sekundę';
					else if($uptime['seconds'] == 2 || $uptime['seconds'] == 3 || $uptime['seconds'] == 4 || $uptime['seconds'] == 22 || $uptime['seconds'] == 23 || $uptime['seconds'] == 24 || $uptime['seconds'] == 32 || $uptime['seconds'] == 33 || $uptime['seconds'] == 34 || $uptime['seconds'] == 42 || $uptime['seconds'] == 43 || $uptime['seconds'] == 44 || $uptime['seconds'] == 52 || $uptime['seconds'] == 53 || $uptime['seconds'] == 54)
						if(!isset($uptime_text))
							$uptime_text = $uptime['seconds'] . ' sekundy';
						else
							$uptime_text .= ', '.$uptime['seconds'] . ' sekundy';
					else
						if(!isset($uptime_text))
							$uptime_text = $uptime['seconds'] . ' sekund';
						else
							$uptime_text .= ', '.$uptime['seconds'] . ' sekund';
			}
			else if($mode == 1)
			{
				if($uptime['seconds'] > 0)
					if($uptime['seconds'] == 1)
						if(!isset($uptime_text))
							$uptime_text = $uptime['seconds'] . ' sekundy';
						else
							$uptime_text .= ', '.$uptime['seconds'] . ' sekundy';
					else
						if(!isset($uptime_text))
							$uptime_text = $uptime['seconds'] . ' sekund';
						else
							$uptime_text .= ', '.$uptime['seconds'] . ' sekund';
			}

		if(isset($uptime_text))
			return $uptime_text;
		else
			return '0 sekund';
	}

	public static function isTimeForModule($module, $interval)
	{
		global $app;
		if($app->isTimeForFunction($module, $interval))
			return true;
		return false;
	}

	public static function getFunctionInterval($interval)
	{
		return $interval['seconds'] + ($interval['minutes'] * 60) + ($interval['hours'] * 60 * 60) + ($interval['days'] * 24 * 60 * 60);
	}

	public static function getInstance()
	{
		global $instance;
		return $instance['name'];
	}

	public static function insertFooter()
	{
		return '\n[hr][right][img]https://i.imgur.com/0JxQImB.png[/img][/right]';
	}

	public static function addToServerEditQueue($function, $what, $edit)
	{
		array_push(self::$queue, array(
			'function' => $function,
			'what' => $what,
			'edit' => $edit,
		));
	}

	public static function checkServerEditQueue()
	{
		global $ts_query;
		$serverdata = array();
		foreach(self::$queue as $action)
			if($action['function'] == 'change_host_message' || $action['function'] == 'change_server_name')
				$serverdata[$action['what']] = $action['edit'];
		self::checkErrors('global', 'edit_server', $ts_query->serverEdit($serverdata));
		empty(self::$queue);
	}

	public static function request($instance, $arguments)
	{
		$cache = array();
		if(file_exists('include/cache/requests.json'))
			$cache = json_decode(file_get_contents('include/cache/requests.json'), true);
		if(!isset($cache[$instance]))
			$cache[$instance] = array();
		array_push($cache[$instance], array('arguments' => $arguments, 'time' => time()));
		file_put_contents('include/cache/requests.json', json_encode($cache));
	}

	public static function checkRequests()
	{
		global $instance, $ts_query;
		$cache = array();
		$change = false;
		if(file_exists('include/cache/requests.json'))
			$cache = json_decode(file_get_contents('include/cache/requests.json'), true);
		if(!isset($cache[$instance['name']]))
			return;
		if(sizeof($cache[$instance['name']]) > 0)
		{
			foreach($cache[$instance['name']] as $index => $request)
			{
				$arguments = explode("|", $request['arguments']);
				if($arguments[0] == 'sendMessage')
				{
					$arguments[3] = explode("\n", $arguments[3]);
					foreach($arguments[3] as $message)
						$ts_query->sendMessage($arguments[1], $arguments[2], $message);
					unset($cache[$instance['name']][$index]);
					$change = true;
				}
				if($arguments[0] == 'sendOfflineMessage')
				{
					if((time() - $request['time']) >= 604800)
					{
						unset($cache[$instance['name']][$index]);
						$change = true;
					}
					else
					{
						$client = self::getClientInfo('client_database_id', $arguments[2]);
						if(isset($client['clid']))
						{
							$arguments[3] = explode("\n", $arguments[3]);
							foreach($arguments[3] as $message)
								$ts_query->sendMessage($arguments[1], $client['clid'], $message);
							unset($cache[$instance['name']][$index]);
							$change = true;
						}
					}
				}
			}
		}
		if($change)
			file_put_contents('include/cache/requests.json', json_encode($cache, JSON_UNESCAPED_UNICODE));
	}

	public static function checkActions()
	{
		global $mysql_query, $ts_query, $ts_data;
		$result = $mysql_query->query("SELECT * FROM `website_actions`;");
		if($result->num_rows > 0)
			foreach(mysqli_fetch_all($result, MYSQLI_ASSOC) as $action)
			{
				$arguments = explode(";", $action['arguments']);
				if($arguments[0] == "sendMessage")
				{
					self::request('second_instance', 'sendMessage|'.$arguments[1].'|'.$arguments[2].'|'.$arguments[3]);
					$mysql_query->query("INSERT INTO `website_actions_replies`(`id_for`, `success`, `code`) VALUES (".$action['id'].", true, 200)");
					$mysql_query->query("DELETE FROM `website_actions` WHERE `id` = ".$action['id'].";");
				}
				if($arguments[0] == "editGroup")
				{
					$ts_query->serverGroupRename($arguments[1], $arguments[2]);
					/*$icon = $ts_query->getElement('data', $ts_query->uploadIcon($arguments[3]));
					$perms = array('i_icon_id' => (int)str_replace("/icon_", "", $icon[0]['name']));
					$ts_query->serverGroupAddPerm($arguments[1], $perms);*/
					$mysql_query->query("INSERT INTO `website_actions_replies`(`id_for`, `success`, `code`) VALUES (".$action['id'].", true, 200)");
					$mysql_query->query("DELETE FROM `website_actions` WHERE `id` = ".$action['id'].";");
				}
				if($arguments[0] == "changeClientGroups")
				{
					if(strlen($arguments[2]) > 0)
					{
						foreach(explode(",", $arguments[2]) as $group_id)
							$ts_query->serverGroupDeleteClient($group_id, $arguments[1]);
					}
					if(strlen($arguments[3]) > 0)
					{
						foreach(explode(",", $arguments[3]) as $group_id)
							$ts_query->serverGroupAddClient($group_id, $arguments[1]);
					}
					$mysql_query->query("INSERT INTO `website_actions_replies`(`id_for`, `success`, `code`) VALUES (".$action['id'].", true, 200)");
					$mysql_query->query("DELETE FROM `website_actions` WHERE `id` = ".$action['id'].";");
				}
				if($arguments[0] == "getOnlineClient")
				{
					if($arguments[1] == 'client_database_id') 
					{
						$return = false;
						$error = false;
						foreach($ts_data['clients'] as $client)
							if($client['client_database_id'] == $arguments[2])
							{
								$return = $client;
								break;
							}
						if($return != false)
						{
							$result = $mysql_query->query("SELECT * FROM `website_clients_data` WHERE `client_database_id` = ".$client['client_database_id'].";");
							if($result->num_rows == 1)
								$mysql_query->query("DELETE FROM `website_clients_data` WHERE `client_database_id` = ".$client['client_database_id'].";");
							$result = $mysql_query->query("INSERT INTO `website_clients_data`(
								`client_database_id`, `clid`, `cid`, `client_isonline`, `client_nickname`, `client_unique_identifier`, `client_away`, `client_away_message`, `client_servergroups`, `client_version`, `client_platform`, `client_icon_id`, `client_country`, `connection_client_ip`, `client_idle_time`, `client_created`, `client_lastconnected`, `time`
							) VALUES (
								".$client['client_database_id'].", ".$client['clid'].", ".$client['cid'].", true, '".$client['client_nickname']."', '".$client['client_unique_identifier']."', ".$client['client_away'].", '".$client['client_away_message']."', '".$client['client_servergroups']."', '".$client['client_version']."', '".$client['client_platform']."', ".$client['client_icon_id'].", '".$client['client_country']."', '".$client['connection_client_ip']."', ".$client['client_idle_time'].", ".$client['client_created'].", ".$client['client_lastconnected'].", ".time()."
							)");
							if(!$result)
							{
								$mysql_query->query("INSERT INTO `website_actions_replies`(`id_for`, `success`, `code`) VALUES (".$action['id'].", false, 111)");
								$error = true;
							}
							if(!$error)
								$mysql_query->query("INSERT INTO `website_actions_replies`(`id_for`, `success`, `code`) VALUES (".$action['id'].", true, 200)");
						}
						else
							$mysql_query->query("INSERT INTO `website_actions_replies`(`id_for`, `success`, `code`) VALUES (".$action['id'].", false, 204)");
						$mysql_query->query("DELETE FROM `website_actions` WHERE `id` = ".$action['id'].";");
					}
				}
				else if($arguments[0] == "getClients")
				{
					$return = array();
					$error = false;
					foreach($ts_data['clients'] as $client)
						if($client['connection_client_ip'] == $arguments[1])
							$return[] = $client;
					if(sizeof($return) > 0)
					{
						foreach($return as $client)
						{
							$result = $mysql_query->query("SELECT * FROM `website_clients_data` WHERE `client_database_id` = ".$client['client_database_id'].";");
							if($result->num_rows == 1)
								$mysql_query->query("DELETE FROM `website_clients_data` WHERE `client_database_id` = ".$client['client_database_id'].";");
							$result = $mysql_query->query("INSERT INTO `website_clients_data`(
								`client_database_id`, `clid`, `cid`, `client_isonline`, `client_nickname`, `client_unique_identifier`, `client_away`, `client_away_message`, `client_servergroups`, `client_version`, `client_platform`, `client_icon_id`, `client_country`, `connection_client_ip`, `client_idle_time`, `client_created`, `client_lastconnected`, `time`
							) VALUES (
								".$client['client_database_id'].", ".$client['clid'].", ".$client['cid'].", true, '".$client['client_nickname']."', '".$client['client_unique_identifier']."', ".$client['client_away'].", '".$client['client_away_message']."', '".$client['client_servergroups']."', '".$client['client_version']."', '".$client['client_platform']."', ".$client['client_icon_id'].", '".$client['client_country']."', '".$client['connection_client_ip']."', ".$client['client_idle_time'].", ".$client['client_created'].", ".$client['client_lastconnected'].", ".time()."
							)");
							if(!$result)
							{
								$mysql_query->query("INSERT INTO `website_actions_replies`(`id_for`, `success`, `code`) VALUES (".$action['id'].", false, 111)");
								$error = true;
								break;
							}
						}
						if(!$error)
							$result = $mysql_query->query("INSERT INTO `website_actions_replies`(`id_for`, `success`, `code`) VALUES (".$action['id'].", true, 200)");
					}
					else
						$mysql_query->query("INSERT INTO `website_actions_replies`(`id_for`, `success`, `code`) VALUES (".$action['id'].", false, 204)");
					$mysql_query->query("DELETE FROM `website_actions` WHERE `id` = ".$action['id'].";");
				}
			}

	}

	public static function refreshData($what)
	{
		global $ts_query, $ts_data;
		if($what == 'channels')
			$ts_data['channels'] = $ts_query->getElement('data', $ts_query->channelList('-topic -flags -voice -limits -icon -secondsempty'));
		else if($what == 'all')
		{
			$ts_data['clients'] = $ts_query->getElement('data', $ts_query->clientList('-uid -away -voice -times -groups -info -icon -country -ip -badges'));
			$ts_data['channels'] = $ts_query->getElement('data', $ts_query->channelList('-topic -flags -voice -limits -icon -secondsempty'));
			$ts_data['server'] = $ts_query->getElement('data', $ts_query->serverInfo());
		}
	}

	public static function isOnline($dbid)
	{
		global $ts_data;
		foreach($ts_data['clients'] as $client)
			if($client['client_database_id'] == $dbid)
				return true;
		return false;
	}

	public static function hasGroup($groups, $group)
	{
		$groups = explode(",", $groups);
		if(in_array($group, $groups))
			return true;
		else
			return false;
	}

	public static function hasChannelGroup($cid, $cgid, $dbid)
	{
		global $ts_query;
		foreach($ts_query->getElement('data', $ts_query->channelGroupClientList($cid)) as $client)
			if($client['cldbid'] == $dbid)
				if($client['cgid'] == $cgid)
					return true;
		return false;
	}

	public static function checkData($id, $data, $content)
	{
		global $ts_query, $ts_data;
		if(is_numeric($id))
		{
			$channelData = $ts_query->getElement('data', $ts_query->channelInfo($id));
			if($data == 'channel_description')
				$channelData[$data] = str_replace("\n", '\n', $channelData[$data]);
			if($channelData[$data] != $content)
				return true;
			else
				return false;
		}
		else
			if($ts_data[$id][$data] != $content)
				return true;
			else
				return false;
	}
	
	public static function checkErrors($function, $event, $action)
	{
		global $instance;
		if(!$action['success'])
			foreach($action['errors'] as $error)
				self::log("FUNCTION_ERROR  |  @".$function."(".$event."): ".$error." (Jeżeli nie wiesz co oznacza ten błąd bądź nie wiesz co zrobiłeś źle, skontaktuj się z właścicielem aplikacji.)", $instance['name'], true);
	}

	public static function getServerGroupInfo($sgid)
	{
		global $ts_query;
		foreach($ts_query->getElement('data', $ts_query->serverGroupList()) as $group)
			if($group['sgid'] == $sgid)
			{
				return $group;
			}
	}

	public static function getClientInfo($by, $value)
	{
		global $ts_query, $ts_data;
		$result = array();
		foreach($ts_data['clients'] as $client)
			if($client[$by] == $value)
			{
				$result = $client;
				$result['status'] = true;
				return $result;
			}
		if($by == 'client_database_id')
		{
			$result = $ts_query->getElement('data', $ts_query->clientDbInfo($value));
			$result['status'] = false;
			return $result;
		}
	}
	
	public static function log($text, $botname, $echo, $tokens = false)
	{
		if($tokens)
		{
			$month = date("F Y");
			$log = date("d.m.Y, H:i:s")."  |  ".$text."\n";
			if(file_exists("logs/tokens.log"))
			{
				$contents = file_get_contents("logs/tokens.log");
				$contents .= $log;
				file_put_contents("logs/tokens.log", $contents);
				if($echo)
					echo "    ".$log;
			}
			else
			{
				$contents = $log;
				file_put_contents("logs/tokens.log", $contents);
				if($echo)
					echo "    ".$log;
			}
		}
		else
		{
			$month = date("F Y");
			$log = date("d.m.Y, H:i:s")."  |  ".$text."\n";
			if(!file_exists("logs/".$botname))
				mkdir("logs/".$botname);
			if(!file_exists("logs/".$botname."/".$month))
				mkdir("logs/".$botname."/".$month);
			if(file_exists("logs/".$botname."/".$month."/".date("d.m.Y").".log"))
			{
				$contents = file_get_contents("logs/".$botname."/".$month."/".date("d.m.Y").".log");
				$contents .= $log;
				file_put_contents("logs/".$botname."/".$month."/".date("d.m.Y").".log", $contents);
				if($echo)
					echo "    ".$log;
			}
			else
			{
				$contents = $log;
				file_put_contents("logs/".$botname."/".$month."/".date("d.m.Y").".log", $contents);
				if($echo)
					echo "    ".$log;
			}
		}
	}
}