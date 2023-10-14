<?php
class clients_manager
{
	private static $function;
	
	public static function register($name)
	{
		global $config;
		self::$function = $config['functions'][$name];
		self::$function['name'] = $name;
		echo "    > '".$name."' - zarejestrowano\n";
	}

	private static function addClientToDatabase($client)
	{
		global $mysql_query;
		$all_nicks = array();
		$query = $mysql_query->prepare("INSERT INTO `clients`
		(
			`client_database_id`,
			`clid`,
			`cid`,
			`client_unique_identifier`,
			`connection_client_ip`,
			`client_nickname`,
			`client_totalconnections`,
			`client_created`,
			`client_servergroups`,
			`shop_tokens`,
			`achievements`,
			`receives_adverts`,
			`all_nicks`,
			`level`,
			`exp_bonus`,
			`exp_time_spent`,
			`exp_multiplier`,
			`week`,
			`week_idle_time_spent`,
			`week_time_spent`,
			`connection_time_record`,
			`time_spent`,
			`idle_time_spent`,
			`last_update`
		)
		VALUES
		(
			".$client['client_database_id'].",
			".$client['clid'].",
			".$client['cid'].",
			'".$client['client_unique_identifier']."',
			'".$client['connection_client_ip']."',
			?,
			".$client['client_totalconnections'].",
			".$client['client_created'].",
			'".$client['client_servergroups']."',
			0,
			'[]',
			true,
			'".json_encode($all_nicks)."',
			0,
			0,
			0,
			1.0,
			".date('W').",
			0,
			0,
			0,
			0,
			0,
			".time()."
		)");
		$query->bind_param("s", $client['client_nickname']);
		$query->execute();
		echo mysqli_stmt_error($query);
		$query->close();
	}

	private static function updateClientInDatabase($data, $client, $achievements)
	{
		global $powerBot, $mysql_query;

		$interval = $powerBot->getFunctionInterval(self::$function['interval']);

		$more_exp = false;
		$all_nicks = json_decode($data['all_nicks'], true);
		$week = $data['week'];
		$week_idle_time_spent = $data['week_idle_time_spent'];
		$week_time_spent = $data['week_time_spent'];
		$connection_time = floor($client['connection_connected_time']/1000);
		$connection_time_record = $data['connection_time_record'];
		$time_spent = $data['time_spent'] + $interval;
		$idle_time = floor($client['client_idle_time']/1000);
		$idle_time_spent = $data['idle_time_spent'];

		if(is_array($all_nicks) && sizeof($all_nicks) > 0)
		{
			if(!in_array($client['client_nickname'], $all_nicks))
			{
				$last_nick = end($all_nicks);
				if(strlen($last_nick) > 0 && $last_nick != $client['client_nickname'])
					$all_nicks[time()] = $client['client_nickname'];
			}
		}
		else
			if($data['client_nickname'] != $client['client_nickname'])
				$all_nicks[time()] = $client['client_nickname'];

		foreach(self::$function['groups_to_more_exp'] as $id => $multiplier)
			if($powerBot->hasGroup($client['client_servergroups'], $id))
			{
				$more_exp = $multiplier;
				break;
			}

		if($more_exp != false)
			if($data['exp_multiplier'] > $more_exp)
				$exp_time_spent = $data['exp_time_spent'] + ($data['exp_multiplier']*$interval);
			else
				$exp_time_spent = $data['exp_time_spent'] + ($more_exp*$interval);
		else
			$exp_time_spent = $data['exp_time_spent'] + ($data['exp_multiplier']*$interval);

		if($week == date('W'))
		{
			if($idle_time >= 180)
				$week_idle_time_spent += $interval;
			$week_time_spent += $interval;
		}
		else
		{
			$week = date('W');
			$week_idle_time_spent = 0;
			$week_time_spent = 0;
		}

		if($connection_time_record < $connection_time)
			$connection_time_record = $connection_time;

		if($idle_time >= $interval)
			$idle_time_spent += $interval;

		$query = $mysql_query->prepare("UPDATE `clients` SET 
			`clid` = ".$client['clid'].",
			`cid` = ".$client['cid'].",
			`client_unique_identifier` = '".$client['client_unique_identifier']."',
			`connection_client_ip` = '".$client['connection_client_ip']."',
			`client_nickname` = ?,
			`client_totalconnections` = ".$client['client_totalconnections'].",
			`client_servergroups` = '".$client['client_servergroups']."',
			`achievements` = '".json_encode($achievements)."',
			`all_nicks` = ?,
			`exp_time_spent` = ".$exp_time_spent.",
			`week` = ".$week.",
			`week_idle_time_spent` = ".$week_idle_time_spent.",
			`week_time_spent` = ".$week_time_spent.",
			`connection_time_record` = ".$connection_time_record.",
			`time_spent` = ".$time_spent.",
			`idle_time_spent` = ".$idle_time_spent.",
			`last_update` = ".time()."
		WHERE `client_database_id` = ".$client['client_database_id'].";");
		@$query->bind_param("ss", $client['client_nickname'], json_encode($all_nicks, JSON_UNESCAPED_UNICODE));
		$query->execute();
		echo mysqli_stmt_error($query);
		$query->close();
	}
	
	public static function execute()
	{
		global $powerBot, $ts_query, $mysql_query, $ts_data;

		/*$result = $mysql_query->query("SELECT * FROM `clients`;");
		foreach(mysqli_fetch_all($result, MYSQLI_ASSOC) as $client)
			$mysql_query->query("UPDATE `clients` SET `all_nicks` = '[]' WHERE `client_database_id` = ".$client['client_database_id'].";");
		exit();*/

		foreach($ts_data['clients'] as $client)
		{
			foreach(self::$function['ignored_groups'] as $group)
				if($powerBot->hasGroup($client['client_servergroups'], $group))
					continue 2;
			if($client['client_type'] == 1)
				continue;
			$action = $ts_query->clientInfo($client['clid']);
			if(!$ts_query->getElement('success', $action))
				continue;
			$client_info = $ts_query->getElement('data', $action);
			$client_info['clid'] = $client['clid'];
			$result = $mysql_query->query("SELECT * FROM `clients` WHERE `client_database_id` = ".$client['client_database_id'].";");
			if($result->num_rows == 0)
				self::addClientToDatabase($client_info);
			else
			{
				$record = $result->fetch_assoc();
				$current_achievements = json_decode($record['achievements'], true);

				foreach(self::$function['achievements'] as $name => $achievements)
				{
					if($name == 'connections')
						foreach($achievements as $id => $achievement)
							if(!isset($current_achievements[$name][$id]))
								if($client_info['client_totalconnections'] > $achievement)
								{
									$current_achievements[$name][$id] = true;
									$powerBot->request('second_instance', 'sendMessage|1|'.$client_info['clid'].'| > Gratulacje, [b]'.$client_info['client_nickname'].'[/b]! Do Twojego konta zostało dodane osiągnięcie: [b]połącz się z serwerem '.$achievement.' razy[/b].');
								}
					if($name == 'time_spent')
						foreach($achievements as $id => $achievement)
							if(!isset($current_achievements[$name][$id]))
								if($record['time_spent'] > $achievement)
								{
									$current_achievements[$name][$id] = true;
									$powerBot->request('second_instance', 'sendMessage|1|'.$client_info['clid'].'| > Gratulacje, [b]'.$client_info['client_nickname'].'[/b]! Do Twojego konta zostało dodane osiągnięcie: [b]spędź na serwerze '.$powerBot->format_seconds($achievement, true, true, true, true).'[/b].');
								}
					if($name == 'idle_time_spent')
						foreach($achievements as $id => $achievement)
							if(!isset($current_achievements[$name][$id]))
								if($record['idle_time_spent'] > $achievement)
								{
									$current_achievements[$name][$id] = true;
									$powerBot->request('second_instance', 'sendMessage|1|'.$client_info['clid'].'| > Gratulacje, [b]'.$client_info['client_nickname'].'[/b]! Do Twojego konta zostało dodane osiągnięcie: [b]spędź na serwerze '.$powerBot->format_seconds($achievement, true, true, true, true).' na statusie away[/b].');
								}
				}
				self::updateClientInDatabase($record, $client_info, $current_achievements);
			}
		}
		
	}
}