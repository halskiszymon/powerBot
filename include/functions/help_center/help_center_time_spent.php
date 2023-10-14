<?php
class help_center_time_spent
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

		$hour = date('H');
		if($hour > self::$function['hours'][1] && $hour < self::$function['hours'][0])
			return;

		foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(self::$function['help_center_group_id'])) as $client)
		{
			if(!isset($client['cldbid']))
				break;

			$client_info = $powerBot->getClientInfo('client_database_id', $client['cldbid']);
			if($client_info['status'])
				if($client_info['client_output_muted'])
				{
					$ts_query->serverGroupDeleteClient(self::$function['help_center_group_id'], $client['cldbid']);
					continue;
				}
			$result = $mysql_query->query('SELECT * FROM `help_center_time_spent` WHERE `client_database_id` = '.$client['cldbid'].';');
			if($result->num_rows == 1)
				$mysql_query->query('UPDATE `help_center_time_spent` SET `time` = `time` + '.$powerBot->getFunctionInterval(self::$function['interval']).' WHERE `client_database_id` = '.$client['cldbid'].';');
			else
				$mysql_query->query('INSERT INTO `help_center_time_spent`
				(
					`client_database_id`,
					`time`
				)
				VALUES
				(
					'.$client['cldbid'].',
					'.$powerBot->getFunctionInterval(self::$function['interval']).'
				);');
		}
	}
}