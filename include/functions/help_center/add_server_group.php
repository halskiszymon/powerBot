<?php
class add_server_group
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

		$result = $mysql_query->query('SELECT * FROM `add_server_group`;');
		$channels = mysqli_fetch_all($result, MYSQLI_ASSOC);
		foreach($channels as $channel)
		{	
			$clients_on_channel = $ts_query->getElement('data', $ts_query->channelClientList($channel['channel_id'], '-groups'));
			foreach($clients_on_channel as $client)
			{
				if($channel['needed_group_id'] != 0)
					if(!$powerBot->hasGroup($client['client_servergroups'], $channel['needed_group_id']))
					{
						$group_info = $powerBot->getServerGroupInfo($channel['needed_group_id']);
						$ts_query->clientPoke($client['clid'], "Aby móć otrzymać tą rangę, musisz posiadać grupę [b]".$group_info['name']."[/b].");
						$ts_query->clientKick($client['clid'], 'channel');
						continue;
					}
				$group_info = $powerBot->getServerGroupInfo($channel['group_id']);
				if($powerBot->hasGroup($client['client_servergroups'], $channel['group_id']))
					if($channel['mode'] == 'ONLY_ADD')
					{
						$ts_query->clientPoke($client['clid'], "Juz posiadasz tą rangę.");
						$ts_query->clientKick($client['clid'], 'channel');
					}
					else
					{
						$ts_query->serverGroupDeleteClient($channel['group_id'], $client['client_database_id']);
						$ts_query->clientPoke($client['clid'], "Ranga [b]".$group_info['name']."[/b] została odebrana.");
						$ts_query->clientKick($client['clid'], 'channel');
					}
				else
				{
					if($channel['mode'] == 'ONLY_REMOVE')
					{
						$ts_query->clientPoke($client['clid'], "Nie posiadasz rangi [b]".$group_info['name']."[/b].");
						$ts_query->clientKick($client['clid'], 'channel');
					}
					else
					{
						$ts_query->serverGroupAddClient($channel['group_id'], $client['client_database_id']);
						$ts_query->clientPoke($client['clid'], "Ranga [b]".$group_info['name']."[/b] została nadana.");
						$ts_query->clientKick($client['clid'], 'channel');
					}

				}

			}
		}
		
	}
}