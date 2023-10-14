<?php
class users_in_server_group
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

		$result = $mysql_query->query('SELECT * FROM `users_in_server_group`;');
		$channels = mysqli_fetch_all($result, MYSQLI_ASSOC);
		foreach($channels as $channel)
		{
			$desc = "";
			$users = $ts_query->getElement('data', $ts_query->serverGroupClientList($channel['group_id'], true));
			$online = 0; $all = 0;
			foreach($users as $user)
			{
				if(isset($user['cldbid']))
				{
					$info = $powerBot->getClientInfo('client_database_id', $user['cldbid']);
					if($info['status'])
					{
						$desc .= "[URL=client://0/".$info['client_unique_identifier']."][COLOR=GREEN][U]".$user['client_nickname']."[/URL]\n";
						$online++;
					}
					$all++;
				}
			}
			foreach($users as $user)
			{
				if(isset($user['cldbid']))
				{
					$info = $powerBot->getClientInfo('client_database_id', $user['cldbid']);
					if(!$info['status'])
						$desc .= "[URL=client://0/".$info['client_unique_identifier']."][COLOR=RED]".$user['client_nickname']."[/URL]\n";	
				}
			}
			$desc .= $powerBot->insertFooter();
			$name = str_replace(array("{CLIENTS_ONLINE}", "{ALL_CLIENTS}"), array($online, $all), $channel['channel_name']);
			if($powerBot->checkData($channel['channel_id'], 'channel_name', $name))
				$powerBot->checkErrors(self::$function['name'], 'write_users_in_server_group', $ts_query->channelEdit($channel['channel_id'], array('channel_description' => $desc, 'channel_name' => $name)));
		}
	}
}