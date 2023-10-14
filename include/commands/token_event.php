<?php
class token_event
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
		global $powerBot, $ts_query, $mysql_query;

		$premium_clients = array();
		foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(33)) as $client)
			if(!isset($client['cldbid']))
				break;
			else
				$premium_clients[$client['cldbid']] = true;
		$ts_query->sendMessage(1, $params['executor']['clid'], ' > Ustalono listę użytkowników premium.');

		if(!isset($params['arguments'][1]))
			$ts_query->sendMessage(1, $params['executor']['clid'], ' > Musisz podac numer eventu.');
		else
		{
			if($params['arguments'][1] == '1')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(67)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 8 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "1# - 5 sekund" otrzymali dodatkowe tokeny.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(68)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 5 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "2# - 5 sekund" otrzymali dodatkowe tokeny.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(69)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 3 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "3# - 5 sekund" otrzymali dodatkowe tokeny.');
			}
			else if($params['arguments'][1] == '2')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(71)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 7 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "1# - jaka to piosenka" otrzymali dodatkowe tokeny.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(72)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 5 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "2# - jaka to piosenka" otrzymali dodatkowe tokeny.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(73)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 3 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "3# - jaka to piosenka" otrzymali dodatkowe tokeny.');
			}
			else if($params['arguments'][1] == '3')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(75)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 7 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "1# - krzesełka" otrzymali dodatkowe tokeny.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(76)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 5 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "2# - krzesełka" otrzymali dodatkowe tokeny.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(77)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 3 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "3# - krzesełka" otrzymali dodatkowe tokeny.');
			}
			else if($params['arguments'][1] == '4')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(199)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 7 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "1# - szósty zmysł" otrzymali dodatkowe tokeny.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(200)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 5 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "2# - szósty zmysł" otrzymali dodatkowe tokeny.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(201)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 3 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "3# - szósty zmysł" otrzymali dodatkowe tokeny.');
			}
			else if($params['arguments'][1] == '5')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(79)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 7 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "1# - taboo" otrzymali dodatkowe tokeny.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(80)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 5 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "2# - taboo" otrzymali dodatkowe tokeny.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(81)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 3 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "3# - taboo" otrzymali dodatkowe tokeny.');
			}
			else if($params['arguments'][1] == '6')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(83)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 7 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "1# - quiz" otrzymali dodatkowe tokeny.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(84)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 5 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "2# - quiz" otrzymali dodatkowe tokeny.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(85)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 3 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "3# - quiz" otrzymali dodatkowe tokeny.');
			}
			else if($params['arguments'][1] == '7')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(87)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 5 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "karaoke master" otrzymali dodatkowe tokeny.');
			}
			else if($params['arguments'][1] == '8')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(88)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 5 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "ox master" otrzymali dodatkowe tokeny.');
			}
			else if($params['arguments'][1] == '9')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(296)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 5 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "kalambury master" otrzymali dodatkowe tokeny.');
			}
			else if($params['arguments'][1] == '10')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(297)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					$mysql_query->query("UPDATE `clients` SET `shop_tokens` = `shop_tokens` + 5 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "państwa-miasta master" otrzymali dodatkowe tokeny.');
			}
		}
	}
}