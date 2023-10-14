<?php
class public_channels_checker
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
		global $powerBot, $ts_query, $ts_data;

		foreach(self::$function['zones'] as $max_clients => $zone)
		{
			$powerBot->refreshData('channels');

			$parent_channels['all'] = array();
			$parent_channels['free'] = array();
			$parent_channels['busy'] = array();
			$parent_supply_channels = array();
			$last_parent_channel = 0;

			foreach($ts_data['channels'] as $channel)
				if($channel['pid'] == $zone['channels_section'])
				{
					if($channel['total_clients'] > 0)
						$parent_channels['busy'][] = $channel;
					else
						$parent_channels['free'][] = $channel;
					$last_parent_channel = $channel['cid'];
					$parent_channels['all'][] = $channel;
				}
				else if($channel['pid'] == self::$function['supply_channels_section'])
					$parent_supply_channels[] = $channel;

			$total = sizeof($parent_channels['free']) + sizeof($parent_channels['busy']);
			$free = sizeof($parent_channels['free']);
			$supply = sizeof($parent_supply_channels);

			if($free > $zone['min_free_channels'])
			{
				$diff = $free - $zone['min_free_channels'];
				for($i = 0; $i < $diff; $i++)
				{
					$last_free_channel = end($parent_channels['free']);
					$last_channel =  end($parent_channels['all']);
					if($last_free_channel['cid'] == $last_channel['cid'])
					{
						$free--;
						$supply++;
						$ts_query->channelEdit($last_free_channel['cid'], array
						(
							'channel_name' => $supply,
							'channel_maxclients' => 0,
							'channel_maxfamilyclients' => 0,
							'channel_flag_maxclients_unlimited' => 0,
							'channel_flag_maxfamilyclients_unlimited' => 0,
							'channel_flag_maxfamilyclients_inherited' => 0
						));
						$ts_query->channelAddPerm($last_free_channel['cid'], array('136' => 200));
						$ts_query->channelMove($last_free_channel['cid'], self::$function['supply_channels_section']);
						unset($parent_channels['free'][$free]);
						$parent_supply_channels[] = $last_free_channel;
					}
				}
			}

			if($free < $zone['min_free_channels'])
			{
				$diff = $zone['min_free_channels'] - $free;
				if($total + $diff > $zone['max_channels'])
					$diff = $zone['max_channels'] - $total;

				if($supply == 0)
				{
					for($i = 0; $i < $diff; $i++)
					{
						$result = $ts_query->getElement('data', $ts_query->channelCreate(array
						(
							'cpid' => self::$function['supply_channels_section'],
							'channel_flag_semi_permanent' => 0,
							'channel_flag_permanent' => 1,
							'channel_name' => ++$supply,
							'channel_maxclients' => 0,
							'channel_maxfamilyclients' => 0,
							'channel_flag_maxclients_unlimited' => 0,
							'channel_flag_maxfamilyclients_unlimited' => 0,
							'channel_flag_maxfamilyclients_inherited' => 0
						)));
						$ts_query->channelAddPerm($result['cid'], array('136' => 0));
						$parent_supply_channels[] = $result;
					}
					$supply = sizeof($parent_supply_channels);
					if($diff == 1)
						$powerBot->log('INFO  |  '.self::$function['name'].'(add_more_public_channels): W strefie zapasowej dla kanałów publicznych zabrakło kanałów. Utworzono jeden dodatkowy kanał.', $powerBot->getInstance(), true);
					else
						$powerBot->log('INFO  |  '.self::$function['name'].'(add_more_public_channels): W strefie zapasowej dla kanałów publicznych zabrakło kanałów. Utworzono '.$diff.' dodatkowe kanały.', $powerBot->getInstance(), true);
				}

				for($i = 0; $i < $diff; $i++)
				{
					$supply--;
					$free++;
					$total++;
					$ts_query->channelEdit($parent_supply_channels[$i]['cid'], array
					(
						'channel_name' => str_replace("{CHANNEL_NUMBER}", $total, $zone['channel_name']),
						'channel_maxclients' => $max_clients,
						'channel_maxfamilyclients' => $max_clients,
						'channel_flag_maxclients_unlimited' => 0,
						'channel_flag_maxfamilyclients_unlimited' => 0,
						'channel_flag_maxfamilyclients_inherited' => 0
					));
					$ts_query->channelAddPerm($parent_supply_channels[$i]['cid'], array('136' => 0));
					$ts_query->channelMove($parent_supply_channels[$i]['cid'], $zone['channels_section'], $last_parent_channel);
					$parent_channels['free'][] = $parent_supply_channels[$i];
					unset($parent_supply_channels[$i]);
				}
			}
		}
	}
}