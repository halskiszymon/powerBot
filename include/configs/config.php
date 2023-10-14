<?php
$configuration = array
(
	'main' => array
	(
		'teamspeak_connection' => array
		(
			'ip' 				=> '',
			'query_port'		=> '10011',
			'server_port' 		=> '9987',
			'login' 			=> '',
			'password' 			=> '',
		),
		
		'mysql_connection' => array
		(
			'host' 				=> 'localhost',
			'user' 				=> '',
			'password' 			=> '',
			'dbname' 			=> '',
		),
		
		'instances_settings' => array
		(
			'data_handler' => array
			(
				'bot_name' 			=> 'powerBot @ #1',
				'default_channel' 	=> 59,
				'enable_events' 	=> false,
				'enable_commands' 	=> false,
				'enable_mysql' 		=> true,
			),
			'website' => array
			(
				'bot_name' 			=> 'powerBot @ #2',
				'default_channel' 	=> 59,
				'enable_events' 	=> false,
				'enable_commands' 	=> false,
				'enable_mysql' 		=> true,
			),
			'shop' => array
			(
				'bot_name' 			=> 'powerBot @ #3',
				'default_channel' 	=> 59,
				'enable_events' 	=> false,
				'enable_commands' 	=> false,
				'enable_mysql' 		=> true,
			),
			'help_center' => array
			(
				'bot_name' 			=> 'powerBot @ 4',
				'default_channel' 	=> 59,
				'enable_events' 	=> false,
				'enable_commands' 	=> false,
				'enable_mysql' 		=> true,
			),
			'first_instance' => array
			(
				'bot_name' 			=> 'powerBot @ #5',
				'default_channel' 	=> 59,
				'enable_events' 	=> false,
				'enable_commands' 	=> false,
				'enable_mysql' 		=> true,
			),
			'second_instance' => array
			(
				'bot_name' 			=> 'powerBot @ #6',
				'default_channel' 	=> 59,
				'enable_events' 	=> true,
				'enable_commands' 	=> true,
				'enable_mysql' 		=> true,
				'individual_login' => array
				(
					'login' 			=> 'powerBot-commander',
					'password' 			=> 'aVAUAYgx',
				),
			),
			'third_instance' => array
			(
				'bot_name' 			=> 'powerBot @ #7',
				'default_channel' 	=> 59,
				'enable_events' 	=> false,
				'enable_commands' 	=> false,
				'enable_mysql' 		=> true,
			),
			'fourth_instance' => array
			(
				'bot_name' 			=> 'powerBot @ #8',
				'default_channel' 	=> 59,
				'enable_events' 	=> false,
				'enable_commands' 	=> false,
				'enable_mysql' 		=> true,
			),
		),
	),

	'commands' => array
	(
		'pomoc' => array
		(
			'enabled' 		 	=> true,
			'privileged_groups' => array(),
			'register_groups' 	=> array(),
		),
		'szukaj' => array
		(
			'enabled' 		 	=> true,
			'privileged_groups' => array(2, 9, 12, 13, 14),
		),
		'live' => array
		(
			'enabled' 		 	=> true,
			'privileged_groups' => array(278),
		),
		'urodziny' => array
		(
			'enabled' 		 	=> true,
			'privileged_groups' => array(19, 20),
		),
		'antypoke' => array
		(
			'enabled' 		 	=> true,
			'privileged_groups' => array(33),
			'group_id' 	=> 31,
		),
		'antymessage' => array
		(
			'enabled' 		 	=> true,
			'privileged_groups' => array(33),
			'group_id' 	=> 32,
		),
		'rejestracja' => array
		(
			'enabled' 		 	=> true,
			'privileged_groups' => array(8),
		),
		'cp' => array
		(
			'enabled' 		 	=> true,
			'privileged_groups' => array(),
			'help_center_channel_id' => 93,
		),
		'kanal' => array
		(
			'enabled' 		 	=> true,
			'privileged_groups' => array(19, 20),
			'private_channel_owner_group_id' => 5,
			'private_channels_zone' => 221,
			'max_subchannels' => 3,
			'get_private_channel_id' => 98,
		),
		'poziom' => array
		(
			'enabled' 		 	=> true,
			'groups_to_more_exp' => array(258 => 2, 33 => 1.5),
			'privileged_groups' => array(),
		),
		'pokeall' => array
		(
			'enabled' 		 	=> true,
			'privileged_groups' => array(2, 9, 12),
			'ignored_groups' 	=> array(),
		),
		'pwall' => array
		(
			'enabled' 		 	=> true,
			'privileged_groups' => array(2, 9, 12),
			'ignored_groups' 	=> array(),
		),
		'refresh_groups_to_assign' => array
		(
			'enabled' 		 	=> true,
			'privileged_groups' => array(2, 9, 12),
		),
		'edit_client' => array
		(
			'enabled' 		 	=> true,
			'privileged_groups' => array(2, 9),
		),
		'premium_channels' => array
		(
			'enabled' 		 	=> true,
			'privileged_groups' => array(2, 9, 12),
		),
		'tokeny' => array
		(
			'enabled' 		 	=> true,
			'privileged_groups' => array(2, 9, 12),
		),
		'exp' => array
		(
			'enabled' 		 	=> true,
			'privileged_groups' => array(2, 9, 12),
		),
		'exp_event' => array
		(
			'enabled' 		 	=> true,
			'privileged_groups' => array(2, 9, 12),
		),
		'token_event' => array
		(
			'enabled' 		 	=> true,
			'privileged_groups' => array(2, 9, 12),
		),
		'kp' => array
		(
			'enabled' 		 	=> true,
			'privileged_groups' => array(2, 9, 12),
		),
	),
	
	'functions' => array
	(
		'data_handler' => array 
		(
			'generate_banner_cache' => array
			(
				'enabled'	=> true,
				'dir'		=> '/var/www/ts3.today/cache/',
				'interval' 	=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 5),
			),
			'generate_website_cache' => array
			(
				'enabled'	=> true,
				'interval' 	=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 55),
			),

		),
		'website' => array 
		(
			'tokens' => array
			(
				'enabled'	=> true,
				'interval' 	=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 5),
			),
			'shop' => array
			(
				'enabled'	=> true,
				'interval' 	=> array('days' => 0, 'hours' => 0, 'minutes' => 1, 'seconds' => 0),
			),
		),
		'shop' => array 
		(
			'services_checker' => array
			(
				'enabled'	=> true,
				'interval' 	=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 5),
			),
		),
		'help_center' => array
		(
			'avabile_admins' => array
			(
				'enabled'			=> true,
				'channels'			=> array
				(
					1 => array
					(
						'channel_id'		=> 91,
						'channel_name'		=> '» | Dostępni administratorzy: {AVABILE_ADMINS}',
						'help_center_group_id' => 30,
					),
				),
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 5),
			),
			'help_center_channels' => array
			(
				'enabled'			=> true,
				'channels' => array
				(
					1 => array
					(
						'channel_id'	=> 93,
						'help_center_group_id' => 30,
						'channel_name_open'	=> '» | Czekam na administratora',
						'channel_name_close'	=> '» | Czekam na administratora [Zamknięte]',
					),
				),
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 5),
			),
			'help_center_time_spent' => array
			(
				'enabled'			=> true,
				'hours'				=> array(14, 23),
				'help_center_group_id' => 30,
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 5),
			),
			'help_center_notifier' => array
			(
				'enabled'			=> true,
				'channels'	=> array
				(
					1 => array
					(
						'channel_id' => 93,
						'admin_groups' => array(9, 12, 14, 13, 15, 16),
						'groups_to_poke' => array(30),
						'ignored_groups_on_channel' => array(),
						'ignored_channels_to_poke' => array(57, 94, 95, 96),
						'help_rooms' => array(94, 95, 96),
					),
				),
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 8),
			),
			'add_server_group' => array
			(
				'enabled'			=> true,
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 5),
			),
			'remove_groups' => array
			(
				'enabled'			=> true,
				'channels'	=> array
				(
					1 => array
					(
						'channel_id' => 100,
						'groups_to_remove' => array(41, 42),
					),
				),
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 5),
			),
			'write_help_center_stats' => array
			(
				'enabled'			=> true,
				'channels' => array
				(
					'served_users' => array
					(
						'channel_id' => 92,
						'channel_name' => '» | Obsłużone osoby: {SERVED_USERS}',
					),
					'help_center_stats' => array
					(
						'channel_id' => 51,
						'interval' 	=> array('days' => 0, 'hours' => 0, 'minutes' => 2, 'seconds' => 0),
					),
				),
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 5),
			),
		),
		'first_instance' => array
		(
			'twitch_info' => array
			(
				'enabled'			=> true,
				'channels' => array
				(
					'corinmear' => array
					(
						'views' => array
						(
							'channel_id' => 10077,
							'channel_name' => '» | Liczba wyświetleń: {VIEWS}',
						),
						'follows' => array
						(
							'channel_id' => 10078,
							'channel_name' => '» | Liczba obserwujących: {FOLLOWS}',
						),
						'status' => array
						(
							'channel_id' => 10080,
							'channel_name' => '| ✶ | Stream: {STATUS}',
							'game_channel_id' => 10081,
							'game_channel_name' => '» | Gram w: {GAME}',
							'spectators_channel_id' => 10082,
							'spectators_channel_name' => '» | Liczba widzów: {SPECTATORS}',
						),
					),
				),
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 5),
			),
			'multi_function' => array
			(
				'enabled'			=> true,
				'clients_online'	=> array
				(
					'enabled' 		=> true,
					'channel_id'	=> 18,
					'channel_name'	=> '[cspacer]✶ Osoby online: {CLIENTS_ONLINE} ✶',
				),
				'channels_count'	=> array
				(
					'enabled' 		=> true,
					'channel_id'	=> 20,
					'channel_name'	=> '[cspacer]✶ Liczba kanałów: {CHANNELS_COUNT} ✶',
				),
				'average_ping'		=> array
				(
					'enabled' 		=> false,
					'channel_id'	=> 0,
					'channel_name'	=> 'Średni ping: {AVERAGE_PING}',
				),
				'packet_loss'		=> array
				(
					'enabled' 		=> false,
					'channel_id'	=> 0,
					'channel_name'	=> 'Utrata pakietów serwerowych: {PACKET_LOSS}',
				),
				'bytes_uploaded'	=> array
				(
					'enabled' 		=> false,
					'channel_id'	=> 0,
					'channel_name'	=> 'Danych wysłanych: {BYTES_UPLOADED}',
				),
				'bytes_received'	=> array
				(
					'enabled' 		=> false,
					'channel_id'	=> 0,
					'channel_name'	=> 'Danych odebranych: {BYTES_RECEIVED}',
				),
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 1, 'seconds' => 0),
			),
			'clients_online_record' => array
			(
				'enabled'			=> true,
				'channel_id'		=> 19,
				'channel_name'		=> '[cspacer]✶ Rekord online: {CLIENTS_ONLINE_RECORD} ✶',
				'show_history' 		=> true,
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 10),
			),
			'birthdays' => array
			(
				'enabled'			=> true,
				'group_id'		=> 187,
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 5, 'seconds' => 0),
			),
			'admin_list' => array
			(
				'enabled'			=> true,
				'channels'			=> array
				(
					1 => array
					(
						'channel_id'		=> 85,
						'groups'			=> array(9, 307, 13, 15),
						'help_center_group_id' => 30,
					),
				),
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 2, 'seconds' => 0),
			),
			'change_server_name' => array
			(
				'enabled'			=> true,
				'server_name'		=> 'ts3.today • Twoje miejsce każdego dnia! ({ONLINE_CLIENTS}/{MAX_CLIENTS})',
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 1, 'seconds' => 0),
			),
			'change_host_message' => array
			(
				'enabled'			=> false,
				'host_message'		=> 'Witaj na serwerze [b]ts3.today[/b]!\nAktualnie jest [b]{ONLINE_CLIENTS} użytkowników[/b] online.\nTo Twoje miejsce każdego dnia, życzymy przyjemnych rozmów!',
				//'host_message'		=> '\n[b]      PRZEPRASZAMY ZA OSTATNIE PROBLEMY Z SERWEREM.\n[url=https://www.facebook.com/groups/2173965879594668/]Na naszej grupie opisaliśmy całą sytuację - kliknij tutaj i dowiedz się więcej![/url]\n',
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 1, 'seconds' => 0),
			),
			'full_server_checker' => array
			(
				'enabled'			=> true,
				'execute_when'		=> 118,
				'ignored_groups'	=> array(9, 12, 14, 13, 15, 16, 164),
				'queue'				=> array(163, 8, 19, 20),
				'how_many_kicks'	=> 5,
				'kick_message'		=> '[url=http://bit.ly/2Eav5]Zostałeś wyrzucony z serwera! Kliknij tutaj po więcej informacji.[/url]',	
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 10),
			),
			'users_in_server_group' => array
			(
				'enabled'			=> true,
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 2, 'seconds' => 0),
			),
		),
		'second_instance' => array
		(
			'adverts' => array
			(
				'enabled'			=> true,
				'addons' => array
				(
					'ads' 			=> array
					(
						'enabled' => false,
						'messages' => array('wiadomosc1','wiadomosc2','wiadomosc3'),
					),
					'birthday_reminder' => array
					(
						'enabled' => false,
						'messages' => array(' > Zauważyłem, że nie masz dodanej daty urodzenia do swojego konta.',' > Jeżeli ją dodasz, w tym dniu Twój nick będzie wyświetlany na banerze a do Twojego konta zostanie dodana specjalna ranga, która posiada uprawnienia użytkownika premium.',' > Wpisz [b]!urodziny[/b] aby dodać datę urodzenia.'),
					),
				),
				'interval' 			=> array('days' => 0, 'hours' => 1, 'minutes' => 0, 'seconds' => 0),
			),
			'bad_nicks' => array
			(
				'enabled'			=> true,
				'base'				=> 'include/cache/bad_nicks.json',
				'interval' 			=> array('days' => 0, 'hours' => 1, 'minutes' => 0, 'seconds' => 5),
			),
			'block_recording' => array
			(
				'enabled'			=> true,
				'ignored_groups' 	=> array(9, 12, 14, 13, 15),
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 5),
			),
			'block_vpn' => array
			(
				'enabled'			=> false,
				'ignored_groups' 	=> array(9, 12, 14, 13, 15, 185),
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 10),
			),
			'message_on_connect' => array
			(
				'enabled'			=> true,
			),
			'public_channels_checker' => array
			(
				'enabled'			=> true,
				'supply_channels_section' => 249,
				'zones' => array
				(
					2 => array
					(
						'channels_section'	=> 151,
						'min_free_channels'	=> 3,
						'max_channels'	=> 25,
						'channel_name'	=> '» | Kanał publiczny #{CHANNEL_NUMBER}'
					),
					3 => array
					(
						'channels_section'	=> 153,
						'min_free_channels'	=> 3,
						'max_channels'	=> 25,
						'channel_name'	=> '» | Kanał publiczny #{CHANNEL_NUMBER}'
					),
					4 => array
					(
						'channels_section'	=> 155,
						'min_free_channels'	=> 3,
						'max_channels'	=> 25,
						'channel_name'	=> '» | Kanał publiczny #{CHANNEL_NUMBER}'
					),
					5 => array
					(
						'channels_section'	=> 157,
						'min_free_channels'	=> 3,
						'max_channels'	=> 25,
						'channel_name'	=> '» | Kanał publiczny #{CHANNEL_NUMBER}'
					),
				),
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 5),
			),
		),
		'third_instance' => array
		(
			'clients_manager' => array
			(
				'enabled' 			=> true,
				'groups_to_more_exp' => array(258 => 2, 33 => 1.5),
				'ignored_groups'	=> array(163, 8, 164),
				'achievements' => array 
				(
					'time_spent' => array(1 => 60, 2 => 18000, 3 => 54000, 4 => 90000, 5 => 180000, 6 => 360000, 7 => 900000, 8 => 1800000, 9 => 3600000, 10 => 9000000, 11 => 18000000),
					'idle_time_spent' => array(1 => 60, 2 => 18000, 3 => 54000, 4 => 90000, 5 => 180000, 6 => 360000, 7 => 900000, 8 => 1800000, 9 => 3600000, 10 => 9000000, 11 => 18000000),
					'connections' => array(1 => 10, 2 => 50, 3 => 100, 4 => 500, 5 => 1000, 6 => 2000, 7 => 3000),
				),
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 3),
			),
			'levels' => array
			(
				'enabled' 			=> true,
				'ignored_groups'	=> array(8, 163, 164),
				'groups_before_level' => array(234, 235),
				'level_groups' => array
				(
					1 => array
					(
						1 => 236,
						2 => 237,
						3 => 238,
						4 => 239,
						5 => 240,
						6 => 241,
						7 => 242,
						8 => 243,
						9 => 244,
						0 => 245,
					),
					2 => array
					(
						1 => 246,
						2 => 247,
						3 => 248,
						4 => 249,
						5 => 250,
						6 => 251,
						7 => 252,
						8 => 253,
						9 => 254,
						0 => 255,
					),
				),
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 5),
			),
			'top_week' => array
			(
				'enabled' 			=> true,
				'channel_id' 		=> 113,
				'reward_group_id'	=> 33,
				'other_reward_group_id'	=> 180,
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 2, 'seconds' => 0),
			),
			'write_tops' => array
			(
				'enabled'			=> true,
				'tops' => array
				(
					'connection_time' => array
					(
						'channel_id' => 114,
						'select' => 'connection_time_record',
						'title'	=> 'Użytkownicy z najdłuższym, jednorazowym czasem połączenia',
						'format' => true
					),
					'connections' => array
					(
						'channel_id' => 115,
						'select' => 'client_totalconnections',
						'title'	=> 'Użytkownicy z największą ilością połączeń do serwera',
						'format' => false
					),
					'time_spent' => array
					(
						'channel_id' => 116,
						'select' => 'time_spent',
						'title'	=> 'Użytkownicy z największą ilością spędzonego czasu na serwerze',
						'format' => true
					),
					/*'idle_time_spent' => array
					(
						'channel_id' => 159,
						'select' => 'idle_time_spent',
						'title'	=> 'Użytkownicy z największą ilością spędzonego czasu away na serwerze',
						'format' => true
					),*/
					'levels' => array
					(
						'channel_id' => 117,
						'select' => 'level',
						'title'	=> 'Użytkownicy z największym poziomem',
						'format' => false
					),
					/*'donors' => array
					(
						'channel_id' => 118,
						'select' => 'shop_spent_tokens',
						'title'	=> 'Użytkownicy z największą ilością wydanych tokenów',
						'format' => false
					),*/
				),
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 2, 'seconds' => 0),
			),
		),
		'fourth_instance' => array
		(
			'private_channels_manager' => array
			(
				'enabled' 			=> true,
				'channels_zone' 	=> 221,
				'groups' => array
				(
					'owner' => 5,
					'admin'	=> 6,
					'operator' => 7,
				),
				'warning_text'		=> ' [ZMIEŃ DATĘ]',
				'min_free_channels' => 5,
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 5),
			),
			'special_channels_manager' => array
			(
				'enabled' 			=> true,
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 10),
			),
			'private_channels_info' => array
			(
				'enabled' 			=> true,
				'channels_zone' 	=> 221,
				'channel'			=> 98,
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 1, 'seconds' => 0),
			),
			'get_private_channel' => array
			(
				'enabled' 			=> true,
				'channel'			=> 98,
				'ignored_groups'	=> array(8, 163, 164),
				'channels_zone' 	=> 221,
				'groups' => array
				(
					'owner' => 5
				),
				'interval' 			=> array('days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 5),
			),
		),
	),
);