<?php
class levels
{
	private static $function;
	
	public static function register($name)
	{
		global $config;
		self::$function = $config['functions'][$name];
		self::$function['name'] = $name;
		echo "    > '".$name."' - zarejestrowano\n";
	}

	private static function getLevel($exp)
	{
		if($exp < 2) return '00';
		if($exp >= 2 && $exp <= 5) return '01';
		if($exp >= 6 && $exp <= 9) return '02';
		if($exp >= 10 && $exp <= 19) return '03';
		if($exp >= 20 && $exp <= 39) return '04';
		if($exp >= 40 && $exp <= 79) return '05';
		if($exp >= 80 && $exp <= 119) return '06';
		if($exp >= 120 && $exp <= 239) return '07';
		if($exp >= 240 && $exp <= 359) return '08';
		if($exp >= 360 && $exp <= 479) return '09';
		if($exp >= 480 && $exp <= 599) return '10';
		if($exp >= 600 && $exp <= 719) return '11';
		if($exp >= 720 && $exp <= 839) return '12';
		if($exp >= 840 && $exp <= 959) return '13';
		if($exp >= 960 && $exp <= 1079) return '14';
		if($exp >= 1080 && $exp <= 1199) return '15';
		if($exp >= 1200 && $exp <= 1319) return '16';
		if($exp >= 1320 && $exp <= 1439) return '17';
		if($exp >= 1440 && $exp <= 1559) return '18';
		if($exp >= 1560 && $exp <= 1679) return '19';
		if($exp >= 1680 && $exp <= 1799) return '20';
		if($exp >= 1800 && $exp <= 1919) return '21';
		if($exp >= 1920 && $exp <= 2039) return '22';
		if($exp >= 2040 && $exp <= 2159) return '23';
		if($exp >= 2160 && $exp <= 2279) return '24';
		if($exp >= 2280 && $exp <= 2399) return '25';
		if($exp >= 2400 && $exp <= 2519) return '26';
		if($exp >= 2520 && $exp <= 2639) return '27';
		if($exp >= 2640 && $exp <= 2759) return '28';
		if($exp >= 2760 && $exp <= 2879) return '29';
		if($exp >= 2880 && $exp <= 2999) return '30';
		if($exp >= 3000 && $exp <= 3119) return '31';
		if($exp >= 3120 && $exp <= 3239) return '32';
		if($exp >= 3240 && $exp <= 3359) return '33';
		if($exp >= 3360 && $exp <= 3479) return '34';
		if($exp >= 3480 && $exp <= 3599) return '35';
		if($exp >= 3600 && $exp <= 3719) return '36';
		if($exp >= 3720 && $exp <= 3839) return '37';
		if($exp >= 3840 && $exp <= 3959) return '38';
		if($exp >= 3960 && $exp <= 4079) return '39';
		if($exp >= 4080 && $exp <= 4199) return '40';
		if($exp >= 4200 && $exp <= 4319) return '41';
		if($exp >= 4320 && $exp <= 4439) return '42';
		if($exp >= 4440 && $exp <= 4559) return '43';
		if($exp >= 4560 && $exp <= 4679) return '44';
		if($exp >= 4680 && $exp <= 4799) return '45';
		if($exp >= 4800 && $exp <= 4919) return '46';
		if($exp >= 4920 && $exp <= 5039) return '47';
		if($exp >= 5040 && $exp <= 5159) return '48';
		if($exp >= 5160 && $exp <= 5279) return '49';
		if($exp >= 5280 && $exp <= 5399) return '50';
		if($exp >= 5400 && $exp <= 5519) return '51';
		if($exp >= 5520 && $exp <= 5639) return '52';
		if($exp >= 5640 && $exp <= 5759) return '53';
		if($exp >= 5760 && $exp <= 5879) return '54';
		if($exp >= 5880 && $exp <= 5999) return '55';
		if($exp >= 6000 && $exp <= 6119) return '56';
		if($exp >= 6120 && $exp <= 6239) return '57';
		if($exp >= 6240 && $exp <= 6359) return '58';
		if($exp >= 6360 && $exp <= 6479) return '59';
		if($exp >= 6480 && $exp <= 6599) return '60';
		if($exp >= 6600 && $exp <= 6719) return '61';
		if($exp >= 6720 && $exp <= 6839) return '62';
		if($exp >= 6840 && $exp <= 6959) return '63';
		if($exp >= 6960 && $exp <= 7079) return '64';
		if($exp >= 7080 && $exp <= 7199) return '65';
		if($exp >= 7200 && $exp <= 7319) return '66';
		if($exp >= 7320 && $exp <= 7439) return '67';
		if($exp >= 7440 && $exp <= 7559) return '68';
		if($exp >= 7560 && $exp <= 7679) return '69';
		if($exp >= 7680 && $exp <= 100000) return '70';
	}

	private static function getLevelGroups($level)
	{
		$groups = array();
		$loop = 0;
		foreach(str_split($level) as $number)
		{
			$number = (int) $number;
			$loop++;
			for($i = 0; $i <= 9; $i++)
				if($number == $i)
					$groups[] = self::$function['level_groups'][$loop][$number];
		}
		return $groups;
	}
	
	public static function execute()
	{
		global $powerBot, $ts_query, $mysql_query, $ts_data;

		foreach($ts_data['clients'] as $client)
		{
			if($client['client_type'] == 1)
				continue;
			foreach(self::$function['ignored_groups'] as $group)
				if($powerBot->hasGroup($client['client_servergroups'], $group))
					continue 2;
			$result = $mysql_query->query("SELECT `level`,`exp_bonus`,`exp_time_spent` FROM `clients` WHERE `client_database_id` = ".$client['client_database_id'].";");
			if($result->num_rows == 1)
			{
				$record = $result->fetch_assoc();
				$exp = floor($record['exp_time_spent']/3600);
				$exp += $record['exp_bonus'];

				if($record['level'] >= 0 && $record['level'] < 10)
					$current_level = '0'.$record['level'];
				else
					$current_level = (string) $record['level'];
				$level = self::getLevel($exp);
				$groups = self::getLevelGroups($level);

				foreach(self::$function['groups_before_level'] as $group)
						if(!$powerBot->hasGroup($client['client_servergroups'], $group))
							$ts_query->serverGroupAddClient($group, $client['client_database_id']);

				if($level == '00')
				{
					$ts_query->serverGroupAddClient(self::$function['level_groups'][1][0], $client['client_database_id']);
					$ts_query->serverGroupAddClient(self::$function['level_groups'][2][0], $client['client_database_id']);
				}

				if($current_level == $level)
				{
					if($level != 0)
						foreach($groups as $group)
							if(!$powerBot->hasGroup($client['client_servergroups'], $group))
								$ts_query->serverGroupAddClient($group, $client['client_database_id']);
				}
				else
				{
					$old_groups = self::getLevelGroups($current_level);
					for($i = 0; $i < 2; $i++)
						if($groups[$i] != $old_groups[$i])
						{
							$ts_query->serverGroupDeleteClient($old_groups[$i], $client['client_database_id']);
							$ts_query->serverGroupAddClient($groups[$i], $client['client_database_id']);
						}
					if(substr($level, 0, 1) == '0')
						$level = str_replace("0", '', $level);
					$level = (int) $level;
					$mysql_query->query("UPDATE `clients` SET `level` = ".$level." WHERE `client_database_id` = ".$client['client_database_id'].";");
					$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$client['client_database_id'].'| > Gratulacje, [b]'.$client['client_nickname'].'[/b]! Awansowałeś na [b]'.$level.' poziom[/b]!');
				}
			}
		}
		
	}
}