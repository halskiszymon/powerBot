<?php
class multi_function
{
	private static $function;
	
	public static function register($name)
	{
		global $config;
		self::$function = $config['functions'][$name];
		self::$function['name'] = $name;
		echo "    > '".$name."' - zarejestrowano\n";
	}

	private static function calcBytes($bytes)
	{
		$B = $bytes / 8;
		if($B > 1)
		{
			$dataReceived = round($B, 1).'B';
			$kB = $bytes / 1024;
			if($kB > 1)
			{
				$dataReceived = round($kB, 1).'kB';
				$MB = $bytes / 1048576;
				if($MB > 1)
				{
					$dataReceived = round($MB, 1).'MB';
					$GB = $bytes / 1073741824;
					if($GB > 1)
						$dataReceived = round($GB, 1).'GB';
				}
			}
		}
		return $dataReceived;
	}
	
	public static function execute()
	{
		global $powerBot, $ts_query, $ts_data;
		if(self::$function['clients_online']['enabled'])
		{
			$currentOnline = $ts_data['server']['virtualserver_clientsonline'] - $ts_data['server']['virtualserver_queryclientsonline'];
			$name = str_replace("{CLIENTS_ONLINE}", $currentOnline, self::$function['clients_online']['channel_name']);
			if($powerBot->checkData(self::$function['clients_online']['channel_id'], 'channel_name', $name))
				$powerBot->checkErrors(self::$function['name'], 'clients_online', $ts_query->channelEdit(self::$function['clients_online']['channel_id'], array('channel_name' => $name)));
		}
		if(self::$function['channels_count']['enabled'])
		{
			$name = str_replace("{CHANNELS_COUNT}", $ts_data['server']['virtualserver_channelsonline'], self::$function['channels_count']['channel_name']);
			if($powerBot->checkData(self::$function['channels_count']['channel_id'], 'channel_name', $name))
				$powerBot->checkErrors(self::$function['name'], 'channels_count', $ts_query->channelEdit(self::$function['channels_count']['channel_id'], array('channel_name' => $name)));
		}
		if(self::$function['average_ping']['enabled'])
		{
			$ping = round($ts_data['server']['virtualserver_total_ping'])."ms";
			$name = str_replace("{AVERAGE_PING}", $ping, self::$function['average_ping']['channel_name']);
			if($powerBot->checkData(self::$function['average_ping']['channel_id'], 'channel_name', $name))
				$powerBot->checkErrors(self::$function['name'], 'average_ping', $ts_query->channelEdit(self::$function['average_ping']['channel_id'], array('channel_name' => $name)));
		}
		if(self::$function['packet_loss']['enabled'])
		{
			$packetLoss = round($ts_data['server']['virtualserver_total_packetloss_total'], 2)."%";
			$name = str_replace("{PACKET_LOSS}", $packetLoss, self::$function['packet_loss']['channel_name']);
			if($powerBot->checkData(self::$function['packet_loss']['channel_id'], 'channel_name', $name))
				$powerBot->checkErrors(self::$function['name'], 'packet_loss', $ts_query->channelEdit(self::$function['packet_loss']['channel_id'], array('channel_name' => $name)));
		}
		if(self::$function['bytes_uploaded']['enabled'])
		{
			$name = str_replace("{BYTES_UPLOADED}", self::calcBytes($ts_data['server']['connection_bytes_sent_total']), self::$function['bytes_uploaded']['channel_name']);
			if($powerBot->checkData(self::$function['bytes_uploaded']['channel_id'], 'channel_name', $name))
				$powerBot->checkErrors(self::$function['name'], 'bytes_uploaded', $ts_query->channelEdit(self::$function['bytes_uploaded']['channel_id'], array('channel_name' => $name)));
		}
		if(self::$function['bytes_received']['enabled'])
		{
			$name = str_replace("{BYTES_RECEIVED}", self::calcBytes($ts_data['server']['connection_bytes_received_total']), self::$function['bytes_received']['channel_name']);
			if($powerBot->checkData(self::$function['bytes_received']['channel_id'], 'channel_name', $name))
				$powerBot->checkErrors(self::$function['name'], 'bytes_received', $ts_query->channelEdit(self::$function['bytes_received']['channel_id'], array('channel_name' => $name)));
		}
	}
}