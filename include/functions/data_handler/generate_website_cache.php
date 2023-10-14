<?php
class generate_website_cache
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

		$online_clients = $ts_data['server']['virtualserver_clientsonline'] - $ts_data['server']['virtualserver_queryclientsonline'];
		$max_clients = $ts_data['server']['virtualserver_maxclients'];
		$mysql_query->query("UPDATE `website_cache` SET `onlineclients` = ".$online_clients.", `maxclients` = ".$max_clients.", `time` = ".time()." WHERE `id` = 1;");
		
		$powerBot->refreshData('channels');
		$json = array();
		foreach($ts_data['channels'] as $channel)
		{
			$json['channels'][$channel['cid']] = $channel;
			/*$json['groups'][$channel['cid']]['Owner'] = $ts_query->getElement('data', $ts_query->channelGroupClientList($channel['cid'], NULL, 15));
			$json['groups'][$channel['cid']]['Moderator'] = $ts_query->getElement('data', $ts_query->channelGroupClientList($channel['cid'], NULL, 16));
			$json['groups'][$channel['cid']]['Head Channel Admin'] = $ts_query->getElement('data', $ts_query->channelGroupClientList($channel['cid'], NULL, 5));
			$json['groups'][$channel['cid']]['Channel Admin'] = $ts_query->getElement('data', $ts_query->channelGroupClientList($channel['cid'], NULL, 6));
			$json['groups'][$channel['cid']]['Ch. Admin Operator'] = $ts_query->getElement('data', $ts_query->channelGroupClientList($channel['cid'], NULL, 7));
			$json['groups'][$channel['cid']]['Blokada: Mówienie'] = $ts_query->getElement('data', $ts_query->channelGroupClientList($channel['cid'], NULL, 11));
			$json['groups'][$channel['cid']]['Blokada: Pisanie'] = $ts_query->getElement('data', $ts_query->channelGroupClientList($channel['cid'], NULL, 12));
			$json['groups'][$channel['cid']]['Blokada: Wchodzenie na kanał'] = $ts_query->getElement('data', $ts_query->channelGroupClientList($channel['cid'], NULL, 13));
			$json['groups'][$channel['cid']]['Dostęp: Wchodzenie na kanał'] = $ts_query->getElement('data', $ts_query->channelGroupClientList($channel['cid'], NULL, 18));*/
		}
		$result = $mysql_query->query("SELECT * FROM `json` WHERE `id` = 1;");
		if($result->num_rows == 0)
			$mysql_query->query(sprintf("INSERT INTO `json`(`json`) VALUES ('%s');", mysqli_real_escape_string($mysql_query, json_encode($json))));
		else
			$mysql_query->query(sprintf("UPDATE `json` SET `json` = '%s' WHERE `id` = 1;", mysqli_real_escape_string($mysql_query, json_encode($json))));



		$powerBot->refreshData('clients');
		$json = array();
		foreach($ts_data['clients'] as $client)
		{
			$client_info = $ts_query->getElement('data', $ts_query->clientInfo($client['clid']));
			$json['clients'][$client['client_database_id']] = $client_info;
			/*if($client_info['client_flag_avatar'])
				$json['clients'][$client['client_database_id']]['avatar'] = $ts_query->getElement('data', $ts_query->clientAvatar($client['client_unique_identifier']));*/
		}
		$result = $mysql_query->query("SELECT * FROM `json` WHERE `id` = 2;");
		if($result->num_rows == 0)
			$mysql_query->query(sprintf("INSERT INTO `json`(`json`) VALUES ('%s');", mysqli_real_escape_string($mysql_query, json_encode($json))));
		else
			$mysql_query->query(sprintf("UPDATE `json` SET `json` = '%s' WHERE `id` = 2;", mysqli_real_escape_string($mysql_query, json_encode($json))));



		$json = array();
		foreach($ts_query->getElement('data', $ts_query->serverGroupList(1)) as $group)
			$json['groups'][$group['sgid']] = $group;
		$result = $mysql_query->query("SELECT * FROM `json` WHERE `id` = 3;");
		if($result->num_rows == 0)
			$mysql_query->query(sprintf("INSERT INTO `json`(`json`) VALUES ('%s');", mysqli_real_escape_string($mysql_query, json_encode($json))));
		else
			$mysql_query->query(sprintf("UPDATE `json` SET `json` = '%s' WHERE `id` = 3;", mysqli_real_escape_string($mysql_query, json_encode($json))));
	}
}