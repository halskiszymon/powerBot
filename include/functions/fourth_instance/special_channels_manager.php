<?php
class special_channels_manager
{
	private static $function;
	private static $last_notify = 0;
	
	public static function register($name)
	{
		global $config;
		self::$function = $config['functions'][$name];
		self::$function['name'] = $name;
		echo "    > '".$name."' - zarejestrowano\n";
	}
	
	public static function execute()
	{
		global $powerBot, $ts_query, $mysql_query;

		$result = $mysql_query->query("SELECT * FROM `special_channels` WHERE `type` = 'premium';");
		foreach(mysqli_fetch_all($result, MYSQLI_ASSOC) as $channel)
		{
			foreach($ts_query->getElement('data', $ts_query->serverGroupList()) as $group)
				if($group['sgid'] == $channel['group_id'])
					$name = $group['name'];
			
			$icon = $ts_query->getElement('data', $ts_query->serverGroupGetIconBySGID($channel['group_id']));

			if($name != $channel['name'] || $icon != $channel['icon'])
				$mysql_query->query("UPDATE `special_channels` SET `icon` = '".$icon."', `name` = '".$name."' WHERE `id` = ".$channel['id'].";");
		}
		/*$result = $mysql_query->query("SELECT * FROM `special_channels` WHERE `type` = 'premium_free';");
		if($result->num_rows < 2)
		{
			foreach(self::$function['premium']['groups_to_notify_no_channels'] as $group)
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList($group)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					else
						if(time() - self::$last_notify >)
				}

		}*/
	}
}