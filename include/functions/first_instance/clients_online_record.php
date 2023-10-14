<?php
class clients_online_record
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
		$time = time();
		$currentOnline = $ts_data['server']['virtualserver_clientsonline'] - $ts_data['server']['virtualserver_queryclientsonline']; 
		if(!file_exists('include/cache/clients_online_record.json'))
		{
			$cache['clients_online_record']['current']['clients'] = $currentOnline;
			$cache['clients_online_record']['current']['time'] = $time;
			$cache['clients_online_record']['history'][$time] = $currentOnline;
			file_put_contents("include/cache/clients_online_record.json", json_encode($cache));
		}
		else
		{
			$result = file_get_contents('include/cache/clients_online_record.json');
			$cache = json_decode($result, true);
			if($cache['clients_online_record']['current']['clients'] < $currentOnline)
			{
				$cache['clients_online_record']['current']['clients'] = $currentOnline;
				$cache['clients_online_record']['current']['time'] = $time;
				$cache['clients_online_record']['history'][$time] = $currentOnline;
				file_put_contents('include/cache/clients_online_record.json', json_encode($cache));
			}
			$name = str_replace("{CLIENTS_ONLINE_RECORD}", $cache['clients_online_record']['current']['clients'], self::$function['channel_name']);
			$desc = '[size=12]';
			$desc .= 'Obecny rekord wynosi [b]'.$cache['clients_online_record']['current']['clients'].' użytkowników[/b] online.\nData jego ustanowienia to [b]'.date('d.m.Y, H:i', $cache['clients_online_record']['current']['time']).'[/b].';
			if(self::$function['show_history'])
			{
				$desc .= '\nHistoria:\n';
				foreach($cache['clients_online_record']['history'] as $index => $value)
					$desc .= '      • '.date('d.m.Y, H:i', $index).': '.$value.'\n';
			}
			$desc .= '[/size]';
			$desc .= $powerBot->insertFooter();;
			if($powerBot->checkData(self::$function['channel_id'], 'channel_name', $name))
				$powerBot->checkErrors(self::$function['name'], 'write_clients_online_record', $ts_query->channelEdit(self::$function['channel_id'], array('channel_name' => $name, 'channel_description' => $desc)));
		}
	}
}