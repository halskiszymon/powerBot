<?php
class help_center_channels
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

		foreach(self::$function['channels'] as $channel)
		{
			$admin_group_clients = $ts_query->getElement('data', $ts_query->serverGroupClientList($channel['help_center_group_id']));
			$clients = 0;
			foreach($admin_group_clients as $client)
				if(isset($client['cldbid']))
					$clients++;
			if($clients > 0)
			{
				if($powerBot->checkData($channel['channel_id'], 'channel_name', $channel['channel_name_open']))
				{
					$powerBot->checkErrors(self::$function['name'], "open_channel(ID: ".$channel['channel_id'].")", $ts_query->channelEdit($channel['channel_id'], array('channel_name' => $channel['channel_name_open'], 'channel_flag_maxclients_unlimited' => 1, 'channel_maxclients' => '-1')));
					$powerBot->checkErrors(self::$function['name'], "open_channel_remove_permission(ID: ".$channel['channel_id'].")", $ts_query->channelAddPerm($channel['channel_id'], array('136' => 0)));
				}
			}
			else
			{
				if($powerBot->checkData($channel['channel_id'], 'channel_name', $channel['channel_name_close']))
				{
					$powerBot->checkErrors(self::$function['name'], "close_channel(ID: ".$channel['channel_id'].")", $ts_query->channelEdit($channel['channel_id'], array('channel_name' => $channel['channel_name_close'], 'channel_flag_maxclients_unlimited' => 0, 'channel_maxclients' => 0)));
					$powerBot->checkErrors(self::$function['name'], "close_channel_add_permission(ID: ".$channel['channel_id'].")", $ts_query->channelAddPerm($channel['channel_id'], array('136' => 200)));
				}
			}
		}
	}
}