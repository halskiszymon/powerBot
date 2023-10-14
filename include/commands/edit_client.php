<?php
class edit_client
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
		global $ts_query;

		if(!isset($params['arguments'][1]) || !is_numeric($params['arguments'][1]) || !isset($params['arguments'][2]) || !isset($params['arguments'][3]))
		{
			$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użycie komendy: [b]!eidt_client <client_database_id> <klucz> <wartość>');
			$ts_query->sendMessage(1, $params['executor']['clid'], ' > Dostępne klucze: client_unique_identifier, client_nickname, client_database_id, client_created, client_lastconnected, client_totalconnections, client_flag_avatar, client_description, client_month_bytes_uploaded, client_month_bytes_downloaded, client_total_bytes_uploaded, client_total_bytes_downloaded, client_base64HashClientUID, client_lastip');
		}
		else
		{
			$result = $ts_query->clientDbEdit($params['arguments'][1], array($params['arguments'][2] => $params['arguments'][3]));
			if($result['success'])
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=green][b]Sukces![/b][/color] Klient został zedytowany.');
			else
			{
				foreach($result['errors'] as $error)
					if(isset($errors))
						$errors .= ', '.$error;
					else
						$errors = $error;
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=red][b]Ooops![/b][/color] Coś poszło nie tak. ('.$errors.')');
			}
		}
		
	}
}