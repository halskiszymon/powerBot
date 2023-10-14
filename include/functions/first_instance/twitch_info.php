<?php
class twitch_info
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
		global $powerBot, $ts_query;

		$key = 'oaocbf2zpmv6807kp9jcxkwmcjvq5a';
		foreach(self::$function['channels'] as $nickname => $info)
		{
			$ch = curl_init();
			$api = 'https://api.twitch.tv/kraken/users/';
			curl_setopt_array
			(
				$ch, array
				(
					CURLOPT_HTTPHEADER => array
					(
						'Client-ID: '.$key
					),
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_URL => $api . $nickname
				)
			);
			$response = curl_exec($ch);
			curl_close($ch);
			$user_info = json_decode($response);

			$ch = curl_init();
			$api = 'https://api.twitch.tv/kraken/streams/';
			curl_setopt_array
			(
				$ch, array
				(
					CURLOPT_HTTPHEADER => array
					(
						'Client-ID: '.$key
					),
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_URL => $api.$nickname
				)
			);
			$response = curl_exec($ch);
			curl_close($ch);
			$stream_info = json_decode($response);

			$ch = curl_init();
			$api = 'https://api.twitch.tv/kraken/channels/';
			curl_setopt_array
			(
				$ch, array
				(
					CURLOPT_HTTPHEADER => array
					(
						'Client-ID: '.$key
					),
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_URL => $api.$nickname
				)
			);
			$response = curl_exec($ch);
			curl_close($ch);
			$channel_info = json_decode($response);

			$name = str_replace('{VIEWS}', number_format($channel_info->views, 0, '.', '.'), $info['views']['channel_name']);
			if($powerBot->checkData($info['views']['channel_id'], 'channel_name', $name))
				$ts_query->channelEdit($info['views']['channel_id'], array('channel_name' => $name));

			$name = str_replace('{FOLLOWS}', number_format($channel_info->followers, 0, '.', '.'), $info['follows']['channel_name']);
			if($powerBot->checkData($info['follows']['channel_id'], 'channel_name', $name))
				$ts_query->channelEdit($info['follows']['channel_id'], array('channel_name' => $name));

			if($stream_info->stream != NULL)
			{
				$name = str_replace('{STATUS}', 'TRWA, NA ŻYWO', $info['status']['channel_name']);
				if($powerBot->checkData($info['status']['channel_id'], 'channel_name', $name))
					$ts_query->channelEdit($info['status']['channel_id'], array('channel_name' => $name));

				$name = str_replace('{GAME}', $stream_info->stream->game, $info['status']['game_channel_name']);
				if($powerBot->checkData($info['status']['game_channel_id'], 'channel_name', $name))
					$ts_query->channelEdit($info['status']['game_channel_id'], array('channel_name' => $name));

				$name = str_replace('{SPECTATORS}', $stream_info->stream->viewers, $info['status']['spectators_channel_name']);
				if($powerBot->checkData($info['status']['spectators_channel_id'], 'channel_name', $name))
					$ts_query->channelEdit($info['status']['spectators_channel_id'], array('channel_name' => $name));
			}
			else
			{
				$name = str_replace('{STATUS}', 'OFF', $info['status']['channel_name']);
				if($powerBot->checkData($info['status']['channel_id'], 'channel_name', $name))
					$ts_query->channelEdit($info['status']['channel_id'], array('channel_name' => $name));

				$name = str_replace('{GAME}', '-', $info['status']['game_channel_name']);
				if($powerBot->checkData($info['status']['game_channel_id'], 'channel_name', $name))
					$ts_query->channelEdit($info['status']['game_channel_id'], array('channel_name' => $name));

				$name = str_replace('{SPECTATORS}', '-', $info['status']['spectators_channel_name']);
				if($powerBot->checkData($info['status']['spectators_channel_id'], 'channel_name', $name))
					$ts_query->channelEdit($info['status']['spectators_channel_id'], array('channel_name' => $name));
			}
		}
	}
}