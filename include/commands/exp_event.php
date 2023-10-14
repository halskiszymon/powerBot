<?php
class exp_event
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
		{
			foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(89)) as $client)
			{
				if(!isset($client['cldbid']))
					break;
				if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
					$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 20 WHERE `client_database_id` = ".$client['cldbid'].";");
				else
					$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 10 WHERE `client_database_id` = ".$client['cldbid'].";");
			}
			$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "Byłem/am na evencie!" otrzymali dodatkowy exp.');
		}
		else
		{
			if($params['arguments'][1] == '1')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(67)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 120 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 60 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "1# - 5 sekund" otrzymali dodatkowy exp.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(68)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 80 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 40 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "2# - 5 sekund" otrzymali dodatkowy exp.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(69)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 40 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 20 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "3# - 5 sekund" otrzymali dodatkowy exp.');
			}
			else if($params['arguments'][1] == '2')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(71)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 120 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 60 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "1# - jaka to piosenka" otrzymali dodatkowy exp.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(72)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 80 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 40 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "2# - jaka to piosenka" otrzymali dodatkowy exp.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(73)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 40 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 20 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "3# - jaka to piosenka" otrzymali dodatkowy exp.');
			}
			else if($params['arguments'][1] == '3')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(75)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 120 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 60 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "1# - krzesełka" otrzymali dodatkowy exp.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(76)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 80 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 40 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "2# - krzesełka" otrzymali dodatkowy exp.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(77)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 40 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 20 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "3# - krzesełka" otrzymali dodatkowy exp.');
			}
			else if($params['arguments'][1] == '4')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(199)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 120 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 60 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "1# - szósty zmysł" otrzymali dodatkowy exp.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(200)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 80 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 40 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "2# - szósty zmysł" otrzymali dodatkowy exp.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(201)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 40 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 20 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "3# - szósty zmysł" otrzymali dodatkowy exp.');
			}
			else if($params['arguments'][1] == '5')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(79)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 120 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 60 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "1# - taboo" otrzymali dodatkowy exp.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(80)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 80 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 40 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "2# - taboo" otrzymali dodatkowy exp.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(81)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 40 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 20 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "3# - taboo" otrzymali dodatkowy exp.');
			}
			else if($params['arguments'][1] == '6')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(83)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 120 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 60 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "1# - quiz" otrzymali dodatkowy exp.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(84)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 80 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 40 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "2# - quiz" otrzymali dodatkowy exp.');

				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(85)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 40 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 20 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "3# - quiz" otrzymali dodatkowy exp.');
			}
			else if($params['arguments'][1] == '7')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(87)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 80 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 40 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "karaoke master" otrzymali dodatkowy exp.');
			}
			else if($params['arguments'][1] == '8')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(88)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 60 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 30 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "ox master" otrzymali dodatkowy exp.');
			}
			else if($params['arguments'][1] == '9')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(296)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 60 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 30 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "kalambury master" otrzymali dodatkowy exp.');
			}
			else if($params['arguments'][1] == '10')
			{
				foreach($ts_query->getElement('data', $ts_query->serverGroupClientList(297)) as $client)
				{
					if(!isset($client['cldbid']))
						break;
					if(isset($premium_clients[$client['cldbid']]) && $premium_clients[$client['cldbid']] === true)
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 60 WHERE `client_database_id` = ".$client['cldbid'].";");
					else
						$mysql_query->query("UPDATE `clients` SET `exp_bonus` = `exp_bonus` + 30 WHERE `client_database_id` = ".$client['cldbid'].";");
				}
				$ts_query->sendMessage(1, $params['executor']['clid'], ' > Użytkownicy w grupie "państwa-miasta master" otrzymali dodatkowy exp.');
			}
		}
	}
}