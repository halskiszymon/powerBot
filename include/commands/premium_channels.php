<?php
class premium_channels
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
		global $ts_query, $mysql_query;

		if(!isset($params['arguments'][1]))
			$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użycie komendy: [b]!premium_channels <add/free>');
		else if($params['arguments'][1] == 'add')
			if(!isset($params['arguments'][2]) && !is_int($params['arguments'][2]) && !isset($params['arguments'][3]) && !is_int($params['arguments'][3]) && !isset($params['arguments'][4]) && !is_int($params['arguments'][4]))
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użycie komendy: [b]!premium_channels add <id_kanału_głównego> <id_kanału_do_nadawania_grupy> <id_grupy>');
			else
			{
				$result = $mysql_query->query("INSERT INTO `special_channels`(`type`, `group_id`, `channel_id`, `channel_add_group_id`, `created`, `owners`, `service_id`) VALUES ('premium_free',".$params['arguments'][4].",".$params['arguments'][2].",".$params['arguments'][3].",0,'free',0)");
				if($result)
					$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=green][b]Sukces![/b][/color] Poprawnie dodano kanał do bazy.');
				else
					$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=red][b]Ooops![/b][/color] Coś poszło nie tak. ('.mysqli_error($mysql_query).')');
			}
		else if($params['arguments'][1] == 'free')
			if(!isset($params['arguments'][2]) && !is_int($params['arguments'][2]))
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użycie komendy: [b]!premium_channels free <id_kanału_głównego>');
			else
			{
				$result = $mysql_query->query("UPDATE `special_channels` SET `type` = 'premium_free', `created` = 0, `owners` = 'free', `service_id` = 0 WHERE `channel_id` = ".$params['arguments'][2].";");
				if($result)
					$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=green][b]Sukces![/b][/color] Poprawnie oznaczono ten kanał jako wolny.');
				else
					$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=red][b]Ooops![/b][/color] Coś poszło nie tak. ('.mysqli_error($mysql_query).')');
			}
		else
			$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użycie komendy: [b]!premium_channels <add/free>');
	}
}