<?php
class tokens
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
		global $mysql_query;

		$result = $mysql_query->query("SELECT * FROM `website_tokens`;");
		if($result->num_rows > 0)
			foreach(mysqli_fetch_all($result, MYSQLI_ASSOC) as $token)
				if($token['expires'] <= time())
					$mysql_query->query("DELETE FROM `website_tokens` WHERE `id` = ".$token['id'].";");
	}
}