<?php
class shop
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
		global $powerBot, $ts_query, $mysql_query;

		if(file_exists('include/cache/'.self::$function['name'].'.json'))
			$cache = json_decode(file_get_contents('include/cache/'.self::$function['name'].'.json'), true);

		if(!isset($cache['warnings']))
			$cache['warnings'] = array();

		$result = $mysql_query->query("SELECT * FROM `shop_purchased_services`;");
		if($result->num_rows > 0)
			foreach(mysqli_fetch_all($result, MYSQLI_ASSOC) as $service)
			{
				$service_info = $mysql_query->query("SELECT * FROM `shop_services` WHERE `id` = ".$service['service_id'].";");
				$service_info = $service_info->fetch_assoc();

				$client_info = $powerBot->getClientInfo('client_database_id', $service['client_database_id']);

				$warning[1] = $service['expiration_time'] - 604800;
				$warning[2] = $service['expiration_time'] - 259200;
				$warning[3] = $service['expiration_time'] - 86400;

				if($service['status'] == 'disabled,waiting')
				{
					foreach(explode("|", $service['attributes']) as $action)
					{
						$arguments = explode(":", $action);
						if($arguments[0] == 'addGroup')
							$ts_query->serverGroupDeleteClient($arguments[1], $service['client_database_id']);
					}

					$mysql_query->query("UPDATE `shop_purchased_services` SET `status` = 'disabled' WHERE `id` = ".$service['id'].";");

					$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$service['client_database_id'].'|Usługa [b]'.$service_info['name'].'[/b] dla użytkownika [b]'.$client_info['client_nickname'].'[/b] została wyłączona.');
				}
				else if($service['status'] == 'enabled,waiting')
				{
					foreach(explode("|", $service['attributes']) as $action)
					{
						$arguments = explode(":", $action);
						if($arguments[0] == 'addGroup')
							$ts_query->serverGroupAddClient($arguments[1], $service['client_database_id']);
					}

					$mysql_query->query("UPDATE `shop_purchased_services` SET `status` = 'active' WHERE `id` = ".$service['id'].";");

					$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$service['client_database_id'].'|Usługa [b]'.$service_info['name'].'[/b] dla użytkownika [b]'.$client_info['client_nickname'].'[/b] została włączona.');
				}
				else if($service['status'] == 'paid,waiting')
				{
					$expiration = time() + $service_info['validity'];
					foreach(explode("|", $service['attributes']) as $action)
					{
						$arguments = explode(":", $action);
						if($arguments[0] == 'addGroup')
							$ts_query->serverGroupAddClient($arguments[1], $service['client_database_id']);
					}

					$mysql_query->query("UPDATE `shop_purchased_services` SET `status` = 'active', `expiration_time` = ".$expiration." WHERE `id` = ".$service['id'].";");

					$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$service['buyer_database_id'].'|Usługa [b]'.$service_info['name'].'[/b] dla użytkownika [b]'.$client_info['client_nickname'].'[/b] została poprawnie zrealizowana. Jej ważność minie dnia [b]'.date('d.m.Y', $expiration).'[/b] o godzinie [b]'.date('H:i:s', $expiration).'[/b]. Od czasu zakupu do czasu realizacji usługi minęło [b]'.$powerBot->format_seconds(($expiration - $service['expiration_time']), true, true, true, true).'[/b]. Dziękujemy za zakupy i życzymy niekończących się rozmów! 8)');

					if($service['client_database_id'] != $service['buyer_database_id']) $powerBot->request('second_instance', 'sendOfflineMessage|1|'.$service['client_database_id'].'|Cześć! Ktoś właśnie podarował Ci prezent z naszego sklepu! Usługa [b]'.$service_info['name'].'[/b] została aktywowana! Jej ważność minie dnia [b]'.date('d.m.Y', $expiration).'[/b] o godzinie [b]'.date('H:i:s', $expiration).'[/b].');
				}
				else if($service['status'] == 'extended,waiting')
				{
					$expiration = time() + $service_info['validity'];
					foreach(explode("|", $service['attributes']) as $action)
					{
						$arguments = explode(":", $action);
						if($arguments[0] == 'addGroup')
							$ts_query->serverGroupAddClient($arguments[1], $service['client_database_id']);
					}

					$mysql_query->query("UPDATE `shop_purchased_services` SET `status` = 'active', `expiration_time` = ".$expiration." WHERE `id` = ".$service['id'].";");

					$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$service['buyer_database_id'].'|Usługa [b]'.$service_info['name'].'[/b] dla użytkownika [b]'.$client_info['client_nickname'].'[/b] została poprawnie przedłużona. Jej ważność minie dnia [b]'.date('d.m.Y', $expiration).'[/b] o godzinie [b]'.date('H:i:s', $expiration).'[/b]. Dziękujemy za zakupy i życzymy niekończących się rozmów! 8)');

					if($service['client_database_id'] != $service['buyer_database_id']) $powerBot->request('second_instance', 'sendOfflineMessage|1|'.$service['client_database_id'].'|Cześć! Ktoś właśnie przedłużył Twój prezent z naszego sklepu! Usługa [b]'.$service_info['name'].'[/b] została przedłużona o kolejny miesiąc! Jej ważność minie dnia [b]'.date('d.m.Y', $expiration).'[/b] o godzinie [b]'.date('H:i:s', $expiration).'[/b].');
				}	
				else if($service['status'] == 'active')
				{
					if(time() > $service['expiration_time'])
					{
						foreach(explode("|", $service['attributes']) as $action)
						{
							$arguments = explode(":", $action);
							if($arguments[0] == 'addGroup')
								print_r($ts_query->serverGroupDeleteClient($arguments[1], $service['client_database_id']));
						}
						$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$service['buyer_database_id'].'|Usługa [b]'.$service_info['name'].'[/b] dla użytkownika [b]'.$client_info['client_nickname'].'[/b] wygasła! :( Jej ważność minęła dnia [b]'.date('d.m.Y', $service['expiration_time']).'[/b] o godzinie [b]'.date('H:i:s', $service['expiration_time']).'[/b]. Przedłuż ją [url=https://ts3.today/?orders]klikając tutaj[/url].');
						$mysql_query->query("UPDATE `shop_purchased_services` SET `status` = 'expired' WHERE `id` = ".$service['id'].";");
						unset($cache['warnings'][$service['id']]);
					}
					else
					{
						if(time() > $warning[1])
							if(!isset($cache['warnings'][$service['id']][1]))
							{
								$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$service['buyer_database_id'].'|Usługa [b]'.$service_info['name'].'[/b] dla użytkownika [b]'.$client_info['client_nickname'].'[/b] zakupiona dnia [b]'.date('d.m.Y', $service['purcharse_time']).'[/b] o godzinie [b]'.date('H:i:s', $service['purcharse_time']).'[/b] wygaśnie za [b]7 dni[/b] ([b]'.date('d.m.Y', $service['expiration_time']).'[/b] o godzinie [b]'.date('H:i:s', $service['expiration_time']).'[/b]). Pamiętaj aby ją w odpowiednim czasie przedłużyć [url=https://ts3.today/?orders]klikając tutaj[/url].');
								if($service['client_database_id'] != $service['buyer_database_id'])
									$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$service['client_database_id'].'|Usługa [b]'.$service_info['name'].'[/b] dla użytkownika [b]'.$client_info['client_nickname'].'[/b] zakupiona dnia [b]'.date('d.m.Y', $service['purcharse_time']).'[/b] o godzinie [b]'.date('H:i:s', $service['purcharse_time']).'[/b] wygaśnie za [b]7 dni[/b] ([b]'.date('d.m.Y', $service['expiration_time']).'[/b] o godzinie [b]'.date('H:i:s', $service['expiration_time']).'[/b]). Pamiętaj aby ją w odpowiednim czasie przedłużyć [url=https://ts3.today/?orders]klikając tutaj[/url].');
								$cache['warnings'][$service['id']][1] = true;
							}
						if(time() > $warning[2])
							if(!isset($cache['warnings'][$service['id']][2]))
							{
								$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$service['buyer_database_id'].'|Usługa [b]'.$service_info['name'].'[/b] dla użytkownika [b]'.$client_info['client_nickname'].'[/b] zakupiona dnia [b]'.date('d.m.Y', $service['purcharse_time']).'[/b] o godzinie [b]'.date('H:i:s', $service['purcharse_time']).'[/b] wygaśnie za [b]3 dni[/b] ([b]'.date('d.m.Y', $service['expiration_time']).'[/b] o godzinie [b]'.date('H:i:s', $service['expiration_time']).'[/b]). Pamiętaj aby ją w odpowiednim czasie przedłużyć [url=https://ts3.today/?orders]klikając tutaj[/url].');
								if($service['client_database_id'] != $service['buyer_database_id'])
									$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$service['client_database_id'].'|Usługa [b]'.$service_info['name'].'[/b] dla użytkownika [b]'.$client_info['client_nickname'].'[/b] zakupiona dnia [b]'.date('d.m.Y', $service['purcharse_time']).'[/b] o godzinie [b]'.date('H:i:s', $service['purcharse_time']).'[/b] wygaśnie za [b]3 dni[/b] ([b]'.date('d.m.Y', $service['expiration_time']).'[/b] o godzinie [b]'.date('H:i:s', $service['expiration_time']).'[/b]). Pamiętaj aby ją w odpowiednim czasie przedłużyć [url=https://ts3.today/?orders]klikając tutaj[/url].');
								$cache['warnings'][$service['id']][2] = true;
							}
						if(time() > $warning[3])
							if(!isset($cache['warnings'][$service['id']][3]))
							{
								$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$service['buyer_database_id'].'|Usługa [b]'.$service_info['name'].'[/b] dla użytkownika [b]'.$client_info['client_nickname'].'[/b] zakupiona dnia [b]'.date('d.m.Y', $service['purcharse_time']).'[/b] o godzinie [b]'.date('H:i:s', $service['purcharse_time']).'[/b] wygaśnie za [b]1 dzień[/b] ([b]'.date('d.m.Y', $service['expiration_time']).'[/b] o godzinie [b]'.date('H:i:s', $service['expiration_time']).'[/b]). Pamiętaj aby ją w odpowiednim czasie przedłużyć [url=https://ts3.today/?orders]klikając tutaj[/url].');
								if($service['client_database_id'] != $service['buyer_database_id'])
									$powerBot->request('second_instance', 'sendOfflineMessage|1|'.$service['client_database_id'].'|Usługa [b]'.$service_info['name'].'[/b] dla użytkownika [b]'.$client_info['client_nickname'].'[/b] zakupiona dnia [b]'.date('d.m.Y', $service['purcharse_time']).'[/b] o godzinie [b]'.date('H:i:s', $service['purcharse_time']).'[/b] wygaśnie za [b]1 dzień[/b] ([b]'.date('d.m.Y', $service['expiration_time']).'[/b] o godzinie [b]'.date('H:i:s', $service['expiration_time']).'[/b]). Pamiętaj aby ją w odpowiednim czasie przedłużyć [url=https://ts3.today/?orders]klikając tutaj[/url].');
								$cache['warnings'][$service['id']][3] = true;
							}
					}
				}
			}

		file_put_contents('include/cache/'.self::$function['name'].'.json', json_encode($cache));
	}
}