<?php
class powerBot
{
    public $instance_info, $handle;
    private $console, $loading_start;

	public function __construct($options)
	{
		$this->console = new console($this);
		$this->loading_start = microtime();

		$instances = json_decode(file_get_contents('include/configs/instances.conf'), true);

		if(!array_key_exists('i', $options) || !array_key_exists($options['i'], $instances))
			$this->console->echo('ERROR', 'The specified instance doesn\'t exists.');
		else
			$this->instance_info = $instances[$options['i']];

		if(posix_getpwuid(posix_geteuid())['name'] == 'root')
		{
			$this->console->echo('WARN', 'You are trying to enable me at root account. That\'s a bit dangerous...');
			$response = $this->console->question('Do you want to continue?', 'Type \'yes\' or \'no\':', array('yes', 'no'));
			if($response == 'no')
			{
				$this->console->echo('INFO', 'Good choice. Change user and come back again.');
				exit;
			}
			$this->console->echo('INFO', 'Mmm. Okay, I\'ll continue. Remember that isn\'t a good choice.');
			sleep(2);
		}
	}

	public function getConsole()
	{
		return $this->console;
	}

	public function loading_ended()
	{
		self::getConsole()->echo('SUCCESS', 'Enabled in '.@round(microtime() - $this->loading_start, 3).' sec.');
		self::getConsole()->echo('INFO', 'Current memory usage: '.self::calc_memory(memory_get_peak_usage(false)));
		self::getConsole()->echo('INFO', 'Type \'help\' to see the commands.');
		$this->handle = fopen('php://stdin', 'r');
		stream_set_blocking($this->handle, false);
	}

	public function readCli()
	{
		$command = trim(fgets($this->handle, 512));
		if(strlen($command) > 0)
			if($command == 'help')
				self::getConsole()->echo('INFO', 'Commands: memory');
			else if($command == 'memory')
				self::getConsole()->echo('INFO', 'Current memory usage: '.self::calc_memory(memory_get_peak_usage(false)));
			else
				self::getConsole()->echo('WARN', 'Unknown command. Type \'help\'.');
	}

	public static function calc_memory($bytes)
	{
		$B = $bytes / 8;
		if($B > 1)
		{
			$memory = round($B, 1).'B';
			$kB = $bytes / 1024;
			if($kB > 1)
			{
				$memory = round($kB, 1).'kB';
				$MB = $bytes / 1048576;
				if($MB > 1)
				{
					$memory = round($MB, 1).'MB';
					$GB = $bytes / 1073741824;
					if($GB > 1)
						$memory = round($GB, 1).'GB';
				}
			}
		}
		return $memory;
	}
}


class console
{
	private $powerBot;

	public function __construct($main)
	{
		$this->powerBot = $main;
		echo 'Console:' . "\n";
	}

	public static function echo($level, $text, $log = false)
	{
		if($level == 'ERROR')
		{
			echo '  '.date('H:i:s')." | \033[0;31mERROR\033[0m | ".$text."\n";
			exit;
		}
		else if($level == 'WARN')
			echo '  '.date('H:i:s')." | \033[1;31mWARNING\033[0m | ".$text."\n";
		else if($level == 'SUCCESS')
			echo '  '.date('H:i:s')." | \033[0;32mSUCCESS\033[0m | ".$text."\n";
		else if($level == 'INFO')
			echo '  '.date('H:i:s')." | \033[1;37mINFO\033[0m | ".$text."\n";
	}

	public static function question($question, $answers, $responses)
	{
		echo '  '.date('H:i:s')." | \033[1;31mQUESTION\033[0m | ".$question.' '.$answers.' ';
		$handle = fopen('php://stdin', 'r');
		$response = trim(fgets($handle));
		while(!in_array($response, $responses))
		{
			echo '  '.date('H:i:s')." | \033[1;31mQUESTION\033[0m | I don't understand. ".$answers.' ';
			$response = trim(fgets($handle));
		}
		fclose($handle);
		return $response;
	}
}
