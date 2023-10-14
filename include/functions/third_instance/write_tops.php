<?php
class write_tops
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

		foreach(self::$function['tops'] as $index => $top)
		{
			$desc = '[hr]\n[size=13]'.$top['title'].':[/size] \n[size=9]';
			$result = $mysql_query->query('SELECT `client_database_id`,`'.$top['select'].'` FROM `clients` ORDER BY `'.$top['select'].'` DESC LIMIT 100;');
			$records = mysqli_fetch_all($result, MYSQLI_ASSOC);
			$count = 0;
			foreach($records as $client)
			{
				$count++;
				if($count > 20)
					break;
				$client_info = $powerBot->getClientInfo('client_database_id', $client['client_database_id']);
				if($top['format'])
					$stat = $powerBot->format_seconds($client[$top['select']], true, true, true, true);
				else
					$stat = $client[$top['select']];
				$desc .= '\n\n     » | [b]Miejsce #'.$count.':[/b] [url=client://0/'.$client_info['client_unique_identifier'].'][color=red]'.$client_info['client_nickname'].'[/color][/url] ([url=https://ts3.today/?profile&id='.$client['client_database_id'].']profil użytkownika[/url]) z wynikiem: [b]'.$stat.'[/b].';
			}
			$desc .= '[/size]\n'.$powerBot->insertFooter();
			if($powerBot->checkData($top['channel_id'], 'channel_description', $desc))
				$powerBot->checkErrors(self::$function['name'], "write_".$index, $ts_query->channelEdit($top['channel_id'], array('channel_description' => $desc)));
		}
	}
}