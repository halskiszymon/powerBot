<?php
class private_channels_info
{
	private static $function, $days_of_week;
	
	public static function register($name)
	{
		global $config;
		self::$function = $config['functions'][$name];
		self::$function['name'] = $name;
		self::$days_of_week[1][1] = 'poniedziałku';
		self::$days_of_week[2][1] = 'poniedziałek';
		self::$days_of_week[1][2] = 'wtorku';
		self::$days_of_week[2][2] = 'wtorek';
		self::$days_of_week[1][3] = 'środy';
		self::$days_of_week[2][3] = 'środę';
		self::$days_of_week[1][4] = 'czwartku';
		self::$days_of_week[2][4] = 'czwartek';
		self::$days_of_week[1][5] = 'piątku';
		self::$days_of_week[2][5] = 'piątek';
		self::$days_of_week[1][6] = 'soboty';
		self::$days_of_week[2][6] = 'sobotę';
		self::$days_of_week[1][0] = 'niedzieli';
		self::$days_of_week[2][0] = 'niedzielę';
		echo "    > '".$name."' - zarejestrowano\n";
	}
	
	public static function execute()
	{
		global $powerBot, $ts_query, $ts_data;

		$desc = array();
		$days = array();
		$today = date('w');

		$number = 0;

		array_push($days, date('d.m.Y', time() + 86400), date('d.m.Y', time() + 2 * 86400), date('d.m.Y', time() + 3 * 86400));

		$desc[$days[0]] = '          » | [b]Dzisiaj o północy (noc z '.self::$days_of_week[1][$today].' na '.self::$days_of_week[2][(($today+1)%7)].'):[/b]\n';
		$desc[$days[1]] = '          » | [b]Jutro o północy (noc z '.self::$days_of_week[1][(($today+1)%7)].' na '.self::$days_of_week[2][(($today+2)%7)].'):[/b]\n';
		$desc[$days[2]] = '          » | [b]Pojutrze o północy (noc z '.self::$days_of_week[1][(($today+2)%7)].' na '.self::$days_of_week[2][(($today+3)%7)].'):[/b]\n';

		foreach($ts_data['channels'] as $channel)
			if($channel['pid'] == self::$function['channels_zone'])
			{
				$number++;
				if($channel['channel_topic'] == '#free#')
					if(isset($free))
						$free .= ', '.$number;
					else
						$free = $number;
				else
				{
					if(strpos($channel['channel_topic'], "urlop") !== false)
						continue;
					
					$topic = explode(" | ", $channel['channel_topic']);
					$channel_date = explode(".", $topic[0]);
					$channel_delete_date = date('d.m.Y', mktime(0, 0, 0, $channel_date[1], $channel_date[0], $channel_date[2]) + 7 * 86400);
					if(in_array($channel_delete_date, $days))
						if(isset($desc['channels_to_delete'][$channel_delete_date]))
							$desc['channels_to_delete'][$channel_delete_date] .= ', '.$number;
						else
							$desc['channels_to_delete'][$channel_delete_date] = $number;
				}
			}
		foreach($days as $day)
			if(isset($desc['channels_to_delete'][$day]))
				$desc[$day] .= '                    [i]'.$desc['channels_to_delete'][$day].'[/i]';
			else
				$desc[$day] .= '                    [i]brak kanałów do usunięcia[/i]';
		if(!isset($free))
			$free = 'brak kanałów wolnych';

		$desc['final'] = '[hr]\n     [size=13][color=#55aa00][b]Kanały wolne:[/b][/color][/size]\n          [size=10][i]'.$free.'[/i][/size]\n\n     [size=13][color=#aa0000][b]Kanały do usunięcia:[/b][/color][/size][size=10]\n'.$desc[$days[0]].'\n\n'.$desc[$days[1]].'\n\n'.$desc[$days[2]].'[/size]'.$powerBot->insertFooter();

		if($powerBot->checkData(self::$function['channel'], 'channel_description', $desc['final']))
			$powerBot->checkErrors(self::$function['name'], "write_private_channels_info", $ts_query->channelEdit(self::$function['channel'], array('channel_description' => $desc['final'])));
	}
}