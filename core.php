<?php
ini_set('default_charset', 'utf8mb4');
setlocale(LC_ALL, 'utf8mb4');

/*echo "\n:: powerBot - Aplikacja zarządzająca serwerem TeamSpeak3\n";
echo ":: Autor: futurepower.pl (https://freely.digital Szymon Halski)\n";
echo ":: Kontakt: halskiszymon@gmail.com\n";
echo ":: Wersja aplikacji: 1.0\n\n";*/

global $powerBot, $ts_query, $mysql_query, $ts_data, $instance, $config;

$options = getopt('i:');
if(!array_key_exists('i', $options))
{
	echo "[Blad] Podaj numer instancji, którą chcesz uruchomić.\n";
	exit;
}
$instance['id'] = $options['i'];
if(!is_numeric($instance['id']))
{
	echo "[Blad] Podaj poprawny numer instancji, którą chcesz uruchomić.\n";
	exit;
}
if($instance['id'] == 0 || $instance['id'] > 8)
{
	echo "[Blad] Podaj poprawny numer instancji, którą chcesz uruchomić.\n";
	exit;
}
$sleep = 1000000;
if($instance['id'] == 1)
	$instance['name'] = "first_instance";
else if($instance['id'] == 2)
{
	$sleep = 50000;
	$instance['name'] = "second_instance";
}
else if($instance['id'] == 3)
	$instance['name'] = "third_instance";
else if($instance['id'] == 4)
	$instance['name'] = "fourth_instance";
else if($instance['id'] == 6)
	$instance['name'] = "help_center";
else if($instance['id'] == 7)
	$instance['name'] = "data_handler";
else if($instance['id'] == 8)
	$instance['name'] = "website";

echo ":: Witaj w procesie uruchamiania instancji wykonawczej.\n";
echo ":: Wybrano program startowy dla: ".$instance['name']."\n\n";

echo "*=*--------------------------------------------------*=*\n";
include('include/configs/config.php');
$config['connection'] = $configuration['main']['teamspeak_connection'];
$config['mysql'] = $configuration['main']['mysql_connection'];
$config['settings'] = $configuration['main']['instances_settings'][$instance['name']];
$config['functions'] = $configuration['functions'][$instance['name']];
$config['commands'] = $configuration['commands'];
if(isset($config['settings']['individual_login']))
{
	$config['connection']['login'] = $config['settings']['individual_login']['login'];
	$config['connection']['password'] = $config['settings']['individual_login']['password'];
}
echo "[»] Wczytywanie konfiguracji aplikacji. (zakonczone)\n";

include('include/classes/ts3admin.class.php');
include('include/classes/application.class.php');
$app = new application;
include('include/classes/powerBot.class.php');
$powerBot = new powerBot;
echo "[»] Wczytywanie klas aplikacji. (zakonczone)\n\n";

if($config['settings']['enable_mysql'])
{
	echo "[»] Łączenie z serwerem MySQL. (w toku)\n";
	try
	{
		mysqli_report(MYSQLI_REPORT_STRICT);
		$mysql_query = new mysqli($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['dbname']);
		if($mysql_query->connect_errno != 0)
			throw new Exception(mysqli_connect_errno());
	}
	catch(Exception $e)
	{
		echo "[Blad] Wystąpił problem z połączeniem z serwerem MySQL!\n";
		echo "[Blad] Treść: ".$e->getMessage()."\n";
		echo "[Info] Instancja wymaga połączenia z bazą danych. Wyłączanie aplikacji.\n";
		$powerBot->log("MYSQL_ERROR  |  ".$e->getMessage(), $instance['name'], false);
		exit;
	}
	$mysql_query->query("set names 'utf8mb4'");
	echo "[Sukces] Połącznie z serwerem MySQL zostało ustabilizowane!\n\n";
}

echo "[»] Uruchamianie instancji wykonawczej. (w toku)\n";
$ts_query = new ts3admin($config['connection']['ip'], $config['connection']['query_port']/*, true, 1*/);
if($ts_query->getElement('success', $ts_query->connect($config['connection']['login'], $config['connection']['password']))) 
{
	if(!$ts_query->getElement('success', $ts_query->selectServer($config['connection']['server_port'])))
	{
		echo "[Blad] Instancja ".$instance['name']." nie została uruchomiona!\n";
		echo "Konsola:\n";
		$powerBot->log("TSQUERY_ERROR  |  Podano nieprawidłowy port.", $instance['name'], true);
		echo "*=*--------------------------------------------------*=*\n";
		exit;
	}
	echo "    > Bot zalogował się na serwer.\n";
	$ts_query->setName($config['settings']['bot_name']);
	echo "    > Bot zmienił nick na: '".$config['settings']['bot_name']."'\n";
	$whoami = $ts_query->getElement('data', $ts_query->whoAmI());
	$ts_query->clientMove($whoami['client_id'], $config['settings']['default_channel']);
	echo "    > Bot przeszedl na kanal: '".$config['settings']['default_channel']."'\n";
}
else
{
	echo "[Blad] Instancja ".$instance['name']." nie została uruchomiona!\n";
	if(count($ts_query->getDebugLog()) > 0) 
	{
		foreach($ts_query->getDebugLog() as $error) 
		{
			echo "[Blad] ".$error."\n";
			$powerBot->log("TSQUERY_ERROR  |  ".$error, $instance['name'], false);
		}
	}
	exit;
}
echo "[Sukces] Instancja została poprawnie uruchomiona.\n\n";
echo "[»] Ładowanie funkcji. (w toku)\n";
foreach(scandir('include/functions/'.$instance['name']) as $function) 
{
	if(preg_match('/[A-Za-z0-9_-]\.php/', $function))
	{
		$class = str_replace('.php', '', $function);
		if(!isset($config['functions'][$class]))
		{
			echo "[Blad] Nie odnaleziono konfiguracji funkcji '".$class."', ktora jest w plikach instancji.\n";
			$powerBot->log("CONFIG_ERROR  |  Nie znaleziono konfiguracji funkcji '".$class."'.", $instance['name'], false);
			exit;
		}
		if($config['functions'][$class]['enabled'])
		{
			include('include/functions/'.$instance['name'].'/'.$function);
			if(method_exists($class, "register"))
			{	
				$class::register($class);
				$functions[] = $class;
			}
		}
	}
}
echo "[Sukces] Funkcje zostały poprawnie wczytane.\n";
if($config['settings']['enable_commands'])
{
	echo "[»] Ładowanie komend. (w toku)\n";
	foreach(scandir('include/commands/') as $command) 
	{
		if(preg_match('/[A-Za-z0-9_-]\.php/', $command))
		{
			$class = str_replace('.php', '', $command);
			if(!isset($config['commands'][$class]))
			{
				echo "[Blad] Nie odnaleziono konfiguracji komendy '".$class."', ktora jest w plikach.\n";
				$powerBot->log("CONFIG_ERROR  |  Nie znaleziono konfiguracji komendy '".$class."'.", $instance['name'], false);
				exit;
			}
			if($config['commands'][$class]['enabled'])
			{
				include('include/commands/'.$command);
				if(method_exists($class, "register"))
				{	
					$class::register($class);
					$commands[] = $class;
				}
			}
		}
	}
	echo "[Sukces] Komendy zostały poprawnie wczytane.\n";
}
echo "*=*--------------------------------------------------*=*\n";
echo "Konsola:\n";
if(!isset($functions))
	$powerBot->log("WARNING  |  Nie wczytano żadnych funkcji.", $instance['name'], true);
if($config['settings']['enable_commands'])
	if(!isset($commands))
		$powerBot->log("WARNING  |  Nie wczytano żadnych komend.", $instance['name'], true);
$powerBot->log("INFO  |  Aplikacja została poprawnie uruchomiona.", $instance['name'], true);
$powerBot->log("INFO  |  Pobrano informacje z serwera aplikacji.", $instance['name'], true);
while(true)
{
	if($config['settings']['enable_commands'])
	{
		$message = $ts_query->getElement('data', $ts_query->readChatMessage('textprivate', true));
		if(strlen($message['invokername']) > 0)
		{
			$params['arguments'] = explode(" ", $message['msg']);

			$params['executor'] = $ts_query->getElement('data', $ts_query->clientInfo($message['invokerid']));
			$params['executor']['clid'] = $message['invokerid'];

			$command_name = strtolower(str_replace('!', '', $params['arguments'][0]));
			if($command_name == 'pokeall' || $command_name == 'pwall')
				$params['arguments'] = explode(" ", $message['msg'], 2);
			$find = false;
			foreach($commands as $command)
				if($command == $command_name)
				{
					$find = true;
					$has_group = false;
					if(sizeof($config['commands'][$command]['privileged_groups']) > 0)
						foreach($config['commands'][$command]['privileged_groups'] as $group)
							if($powerBot->hasGroup($params['executor']['client_servergroups'], $group))
							{
								$command::execute($params);
								$has_group = true;
								break 1;
							}
					if(sizeof($config['commands'][$command]['privileged_groups']) == 0)
					{
						$command::execute($params);
						$has_group = true;
					}
					if(!$has_group)
						$ts_query->sendMessage(1, $params['executor']['clid'], " > Nie posiadasz dostępu do tej komendy.");
				}
			if(!$find)
				$ts_query->sendMessage(1, $params['executor']['clid'], " > Nie znalazłem takiej komendy. Wpisz [b]!pomoc[/b] aby uzyskać pomoc.");
		}
	}
	$powerBot->refreshData('all');
	$powerBot->checkRequests();
	if($instance['id'] == 8)
		$powerBot->checkActions();
	$powerBot->checkServerEditQueue();
	if(isset($functions))
	{
		foreach($functions as $function)
		{
			if($config['settings']['enable_events'])
			{
				$cache = array();
				if(method_exists($function, "onClientConnect")) 
				{
					foreach($ts_data['clients'] as $client)
						array_push($cache, array('client_database_id' => $client['client_database_id'], 'clid' => $client['clid'], 'client_lastconnected' => $client['client_lastconnected']));
					foreach($cache as $key => $client)
					{
						$diff = time() - $client['client_lastconnected'] - 36;
						if($client['client_database_id'] == 2)
							echo $diff."\n";
						if($diff < 3)
						{
							echo $client['clid'];
							$client_info = $ts_query->getElement('data', $ts_query->clientInfo($client['clid']));
							$client_info['clid'] = $client['clid'];
							if(!isset($already_send[$client['client_database_id']]))
							{
								$function::onClientConnect($client_info);
								$already_send[$client['client_database_id']] = time();
							}
						}
						else
							if(isset($already_send[$client['client_database_id']]) && time() - $already_send[$client['client_database_id']] > 30)
								unset($already_send[$client['client_database_id']]);
					}
				}
			}
			if($app->isTimeForFunction($function))
				if(method_exists($function, "execute")) 
					$function::execute();
		}
	}
	usleep($sleep);
}