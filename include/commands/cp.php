<?php
class cp
{
	private static $command;
	
	public static function register($name)
	{
		global $config;
		self::$command = $config['commands'][$name];
		self::$command['name'] = $name;
		echo "    > '".$name."' - zarejestrowano\n";
	}
	
	public static function execute($params)
	{
		global $powerBot, $ts_query;

		$channel = $ts_query->getElement('data', $ts_query->channelInfo(self::$command['help_center_channel_id']));
		if($channel['channel_flag_maxclients_unlimited'])
		{
			$ts_query->clientMove($params['executor']['clid'], self::$command['help_center_channel_id']);
			$ts_query->sendMessage(1, $params['executor']['clid'], ' > Zostałeś przeniesiony na kanał pomocy.');
		}
		else
			$ts_query->sendMessage(1, $params['executor']['clid'], ' > Aktualnie nie ma żadnego administratora, który mogłby Ci pomóc. Spróbuj ponownie później.');
		
	}
}