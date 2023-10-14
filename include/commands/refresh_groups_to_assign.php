<?php
class refresh_groups_to_assign
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
		global $ts_query, $mysql_query;

		$ts_query->sendMessage(1, $params['executor']['clid'], ' > Rozpoczynam odświeżanie grup. Proszę czekać...');

		$mysql_query->query("DELETE FROM `website_groups_to_assign`;");
		$ts_query->sendMessage(1, $params['executor']['clid'], '      » | Usunięto stare rekordy.');

		$result = $mysql_query->query("SELECT * FROM `website_groups_assigner`;");
		$data = array();
		$groups_in_database = array();
		if($result->num_rows > 0)
		{
			$server_groups = $ts_query->getElement('data', $ts_query->serverGroupList());
			foreach(mysqli_fetch_all($result, MYSQLI_ASSOC) as $row)
			{
				$ignored_groups = explode(",", $row['ignored_groups_ids']);
				$data[$row['id']] = array();
				foreach($server_groups as $group)
				{
					if(in_array($group['sgid'], $ignored_groups))
						continue;
					if($group['sortid'] >= $row['sort_id_start'] && $group['sortid'] <= $row['sort_id_end'])
						array_push($data[$row['id']], array('group_id' => $group['sgid'], 'group_name' => $group['name'], 'base64_image' => $ts_query->getElement('data', $ts_query->serverGroupGetIconBySGID($group['sgid']))));
				}
			}
			foreach($data as $id => $groups)
				foreach($groups as $group)
				{
					$mysql_query->query("INSERT INTO `website_groups_to_assign`
					(
						`group_id`, `id`, `group_name`, `base64_image`
					) VALUES (
						".$group['group_id'].", ".$id.", '".$group['group_name']."', '".$group['base64_image']."'
					);");
					$ts_query->sendMessage(1, $params['executor']['clid'], '      » | Zaaktualizowano rangę: [b]'.$group['group_name'].'[/b]');
				}
		}
		$ts_query->sendMessage(1, $params['executor']['clid'], ' > [color=green][b]Sukces![/b][/color] Zakończono odświeżanie grup.');
	}
}