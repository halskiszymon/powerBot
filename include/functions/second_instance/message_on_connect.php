<?php
class message_on_connect
{
	private static $function;
	
	public static function register($name)
	{
		global $config;
		self::$function = $config['functions'][$name];
		self::$function['name'] = $name;
		echo "    > '".$name."' - zarejestrowano\n";
	}

	public static function onClientConnect($client)
	{
		global $powerBot, $ts_query, $mysql_query, $ts_data;

		$result = $mysql_query->query('SELECT * FROM `message_on_connect`;')->fetch_assoc();
		if($result['id'] == 1)
			$connect_message = $result['message'];

		$clients_online = $ts_data['server']['virtualserver_clientsonline'] - $ts_data['server']['virtualserver_queryclientsonline'];
		$max_clients = $ts_data['server']['virtualserver_maxclients'];
		$edit = array
		(
			'{CLIENT_NICK}' => $client['client_nickname'],
			'{CLIENT_CONNECTIONS}' => $client['client_totalconnections'],
			'{CLIENT_ON_SERVER_FOR}' => $powerBot->format_seconds($client['client_lastconnected'] - $client['client_created'], true, true, true, true),
			'{SERVER_MAX_CLIENTS}' => $max_clients,
			'{SERVER_CLIENTS_ONLINE}' => $clients_online,
			'{SERVER_ONLINE_PERCENT}' => floor(($clients_online * 100) / $max_clients)
		);

		$message = str_replace(array_keys($edit), array_values($edit), $connect_message);
		$message = explode('\n', $message);
		foreach($message as $line)
			$ts_query->sendMessage(1, $client['clid'], $line);

		//$ts_query->clientPoke($client['clid'], "[b]Strona www już działa![/b] Zapraszamy: https://ts3.today");
		/*$ts_query->clientPoke($client['clid'], "Cześć... Niestety nie mamy dobrych informacji :(");
		$ts_query->clientPoke($client['clid'], "Dwa dni temu wystąpił problem i dane naszego TeamSpeaka cofnęły się o dwa tygodnie.");
		$ts_query->clientPoke($client['clid'], "Więcej informacji nt. problemu znajdziesz pod tym linkiem:");
		$ts_query->clientPoke($client['clid'], "https://www.facebook.com/groups/2173965879594668/permalink/2289880524669869/");
		$ts_query->clientPoke($client['clid'], "");
		$ts_query->clientPoke($client['clid'], "Więcej informacji o rozwiązaniu i przeprosinach znajdziesz pod tym linkiem:");
		$ts_query->clientPoke($client['clid'], "https://www.facebook.com/groups/2173965879594668/permalink/2290560301268558/");
		$ts_query->clientPoke($client['clid'], "");
		$ts_query->clientPoke($client['clid'], "Przepraszamy za całą tą sytuację. [b]Jeżeli masz jakiś problem, wejdź na Centrum Pomocy.");
		$ts_query->clientPoke($client['clid'], "Postaramy się pomóc z każdym problemem i w każdym czasie.");
		$ts_query->clientPoke($client['clid'], "Miłych rozmów, administracja ts3.today :|");*/
	}
}