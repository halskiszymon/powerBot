<?php
global $powerBot, $ts_query, $mysql_query;

ini_set('default_charset', 'utf8mb4');
error_reporting(E_ALL);

include('include/classes/powerBot.class.php');
include('include/classes/ts3admin.class.php');

$powerBot = new powerBot(getopt('i:'));

$teamspeak3 = json_decode(file_get_contents('include/configs/teamspeak3.conf'), true);
$mysql = json_decode(file_get_contents('include/configs/mysql.conf'), true);
$powerBot->getConsole()->echo('SUCCESS', 'Loaded application configuration.');

if($powerBot->instance_info['mysql']['enable'])
{
	try
	{
		mysqli_report(MYSQLI_REPORT_STRICT);
		$mysql_query = new mysqli($mysql['host'], $mysql['user'], $mysql['password']);
		if($mysql_query->connect_errno != 0)
			throw new Exception(mysqli_connect_errno());
	}
	catch(Exception $e)
	{
		$powerBot->getConsole()->echo('ERROR', 'Cannot connect to mysql: '.$e->getMessage());
		exit;
	}
	$mysql_query->query('SET NAMES utf8mb4;');
	$mysql_query->query('CREATE DATABASE IF NOT EXISTS '.$mysql['database'].';');
	$powerBot->getConsole()->echo('SUCCESS', 'Connected to mysql server.');
}

$ts_query = new ts3admin($teamspeak3['server_ip'], $teamspeak3['query_port']);
if($ts_query->getElement('success', $ts_query->connect()))
{
	if(!$ts_query->getElement('success', $ts_query->login($teamspeak3['query_user'], $teamspeak3['query_password'])))
		$powerBot->getConsole()->echo('ERROR', 'Cannot connect to teamspeak3: Invalid serverquery username or password.');
	if(!$ts_query->getElement('success', $ts_query->selectServer($teamspeak3['voice_port'])))
		$powerBot->getConsole()->echo('ERROR', 'Cannot connect to teamspeak3: Invalid server port.');

	$ts_query->setName($powerBot->instance_info['teamspeak3']['client_nickname']);
	$whoami = $ts_query->getElement('data', $ts_query->whoAmI());
	$ts_query->clientMove($whoami['client_id'], $powerBot->instance_info['teamspeak3']['client_channel_id']);
	$powerBot->getConsole()->echo('SUCCESS', 'Connected to teamspeak3 server.');
}
else
{
	foreach($ts_query->getDebugLog() as $error)
		isset($errors) ? $errors .= ', '.$error : $errors = $error;
	$powerBot->getConsole()->echo('ERROR', 'Cannot connect to teamspeak3: '.$errors);
}
$powerBot->loading_ended();
while(true)
{
	$powerBot->readCli();
	usleep(500000);
}
