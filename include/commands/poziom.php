<?php
class poziom
{
	private static $command;
	
	public static function register($name)
	{
		global $config;
		self::$command = $config['commands'][$name];
		self::$command['name'] = $name;
		echo "    > '".$name."' - zarejestrowano\n";
	}

	private static function getMissingExp($exp)
	{
		if($exp < 2) return 2 - $exp;
		if($exp >= 2 && $exp <= 5) return 5 - $exp;
		if($exp >= 6 && $exp <= 9) return 9 - $exp;
		if($exp >= 10 && $exp <= 19) return 19 - $exp;
		if($exp >= 20 && $exp <= 39) return 39 - $exp;
		if($exp >= 40 && $exp <= 79) return 79 - $exp;
		if($exp >= 80 && $exp <= 119) return 119 - $exp;
		if($exp >= 120 && $exp <= 239) return 239 - $exp;
		if($exp >= 240 && $exp <= 359) return 359 - $exp;
		if($exp >= 360 && $exp <= 479) return 479 - $exp;
		if($exp >= 480 && $exp <= 599) return 599 - $exp;
		if($exp >= 600 && $exp <= 719) return 719 - $exp;
		if($exp >= 720 && $exp <= 839) return 839 - $exp;
		if($exp >= 840 && $exp <= 959) return 959 - $exp;
		if($exp >= 960 && $exp <= 1079) return 1079 - $exp;
		if($exp >= 1080 && $exp <= 1199) return 1199 - $exp;
		if($exp >= 1200 && $exp <= 1319) return 1319 - $exp;
		if($exp >= 1320 && $exp <= 1439) return 1439 - $exp;
		if($exp >= 1440 && $exp <= 1559) return 1559 - $exp;
		if($exp >= 1560 && $exp <= 1679) return 1679 - $exp;
		if($exp >= 1680 && $exp <= 1799) return 1799 - $exp;
		if($exp >= 1800 && $exp <= 1919) return 1919 - $exp;
		if($exp >= 1920 && $exp <= 2039) return 2039 - $exp;
		if($exp >= 2040 && $exp <= 2159) return 2159 - $exp;
		if($exp >= 2160 && $exp <= 2279) return 2279 - $exp;
		if($exp >= 2280 && $exp <= 2399) return 2399 - $exp;
		if($exp >= 2400 && $exp <= 2519) return 2519 - $exp;
		if($exp >= 2520 && $exp <= 2639) return 2639 - $exp;
		if($exp >= 2640 && $exp <= 2759) return 2759 - $exp;
		if($exp >= 2760 && $exp <= 2879) return 2879 - $exp;
		if($exp >= 2880 && $exp <= 2999) return 2999 - $exp;
		if($exp >= 3000 && $exp <= 3119) return 3119 - $exp;
		if($exp >= 3120 && $exp <= 3239) return 3239 - $exp;
		if($exp >= 3240 && $exp <= 3359) return 3359 - $exp;
		if($exp >= 3360 && $exp <= 3479) return 3479 - $exp;
		if($exp >= 3480 && $exp <= 3599) return 3599 - $exp;
		if($exp >= 3600 && $exp <= 3719) return 3719 - $exp;
		if($exp >= 3720 && $exp <= 3839) return 3839 - $exp;
		if($exp >= 3840 && $exp <= 3959) return 3959 - $exp;
		if($exp >= 3960 && $exp <= 4079) return 4079 - $exp;
		if($exp >= 4080 && $exp <= 4199) return 4199 - $exp;
		if($exp >= 4200 && $exp <= 4319) return 4319 - $exp;
		if($exp >= 4320 && $exp <= 4439) return 4439 - $exp;
		if($exp >= 4440 && $exp <= 4559) return 4559 - $exp;
		if($exp >= 4560 && $exp <= 4679) return 4679 - $exp;
		if($exp >= 4680 && $exp <= 4799) return 4799 - $exp;
		if($exp >= 4800 && $exp <= 4919) return 4919 - $exp;
		if($exp >= 4920 && $exp <= 5039) return 5039 - $exp;
		if($exp >= 5040 && $exp <= 5159) return 5159 - $exp;
		if($exp >= 5160 && $exp <= 5279) return 5279 - $exp;
		if($exp >= 5280 && $exp <= 5399) return 5399 - $exp;
		if($exp >= 5400 && $exp <= 5519) return 5519 - $exp;
		if($exp >= 5520 && $exp <= 5639) return 5639 - $exp;
		if($exp >= 5640 && $exp <= 5759) return 5759 - $exp;
		if($exp >= 5760 && $exp <= 5879) return 5879 - $exp;
		if($exp >= 5880 && $exp <= 5999) return 5999 - $exp;
		if($exp >= 6000 && $exp <= 6119) return 6119 - $exp;
		if($exp >= 6120 && $exp <= 6239) return 6239 - $exp;
		if($exp >= 6240 && $exp <= 6359) return 6359 - $exp;
		if($exp >= 6360 && $exp <= 6479) return 6479 - $exp;
		if($exp >= 6480 && $exp <= 6599) return 6599 - $exp;
		if($exp >= 6600 && $exp <= 6719) return 6719 - $exp;
		if($exp >= 6720 && $exp <= 6839) return 6839 - $exp;
		if($exp >= 6840 && $exp <= 6959) return 6959 - $exp;
		if($exp >= 6960 && $exp <= 7079) return 7079 - $exp;
		if($exp >= 7080 && $exp <= 7199) return 7199 - $exp;
		if($exp >= 7200 && $exp <= 7319) return 7319 - $exp;
		if($exp >= 7320 && $exp <= 7439) return 7439 - $exp;
		if($exp >= 7440 && $exp <= 7559) return 7559 - $exp;
		if($exp >= 7560 && $exp <= 7679) return 7679 - $exp;
		if($exp >= 7680 && $exp <= 100000) return 100000 - $exp;
	}
	
	public static function execute($params)
	{
		global $powerBot, $ts_query, $mysql_query, $ts_data;

		$result = $mysql_query->query("SELECT * FROM `clients` WHERE `client_database_id` = ".$params['executor']['client_database_id'].";");
		$client = $result->fetch_assoc();
		$ts_query->sendMessage(1, $params['executor']['clid'], ' > [b]'.$params['executor']['client_nickname'].'[/b], aktualnie jesteś na [b]'.$client['level'].' poziomie[/b].');
		$ts_query->sendMessage(1, $params['executor']['clid'], '     » | Łącznie, na Twoim koncie jest [b]'.floor($client['exp_time_spent']/3600 + $client['exp_bonus']).' expa[/b].');
		$ts_query->sendMessage(1, $params['executor']['clid'], '     » | Do następnego poziomu brakuje Ci [b]'.self::getMissingExp(floor($client['exp_time_spent']/3600 + $client['exp_bonus'])).' expa[/b].');

		$multiplier = false;
		foreach(self::$command['groups_to_more_exp'] as $id => $m)
			if($powerBot->hasGroup($client['client_servergroups'], $id))
			{
				$multiplier = $m;
				break;
			}

		if($multiplier != false)
		{
			if($client['exp_multiplier'] > $multiplier)
				$multiplier = $client['exp_multiplier'];
		}
		else
			$multiplier = $client['exp_multiplier'];

		$ts_query->sendMessage(1, $params['executor']['clid'], '     » | Aktywny mnożnik: [b]'.$multiplier.'[/b]');
	}
}