<?php

	include_once( 'constants.php' );
	if(file_exists('countries.php') && empty($_COUNTRY_ISO_MAP)) include_once( 'countries.php' );
	
if(!function_exists(objectToArray)) {
	function objectToArray($d) {
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}

		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(__FUNCTION__, $d);
		}
		else {
			// Return array
			return $d;
		}
	}	
}
	
	$FILTER = getFilter($_GET);
	$limit=20;
	if(!empty($FILTER['countries'])) {
		$countries = explode('|', $FILTER['countries']);
		$array['objects'] = array();
		foreach($countries AS $c) {
			$array['objects'][$c] = array('path' => $_GM_POLYGONS[$c], 'name' => $_COUNTRY_ISO_MAP[$c], 'total_cnt' => $_COUNTRY_ACTIVITY_COUNT[$c]);
		}
		/*
		$activities_url = SEARCH_URL . "activities/?format=json&offset=0&limit={$limit}&countries={$FILTER['countries']}";
		$search_url = "";
		if(!empty($_DEFAULT_ORGANISATION_ID)) {
			$search_url .= "&organisations=" . $_DEFAULT_ORGANISATION_ID;
		}
		if(!empty($FILTER['query'])) {
			$search_url .= "&query={$FILTER['query']}";
		}
		
		if(!empty($FILTER['regions'])) {
			$search_url .= "&regions={$FILTER['regions']}";
		}
		
		if(!empty($FILTER['sectors'])) {
			$search_url .= "&sectors={$FILTER['sectors']}";
		}
		
		if(!empty($FILTER['budgets'])) {
			$budgets = explode('|', trim($_REQUEST['budgets']));
			//Get the lowest budget from filter and use this one, all the other are included in the range
			ksort($budgets);
			$search_url .= "&statistics__total_budget__gt={$budgets[0]}";
		}
		$activities_url .= $search_url;

		$content = file_get_contents($activities_url);
		$result = json_decode($content);
		$meta = $result->meta;
		$count = $meta->total_count;
		
		$objects = $result->objects;
		$activities = objectToArray($objects);
		
		foreach($activities AS $a) {
			foreach($a['recipient_country'] AS $c) {
				if(isset($array['objects'][$c['iso']])) {
					$array['objects'][$c['iso']]['total_cnt']++;
				}
			}
		}
		
		$start=$limit;
		
		$array['meta']['total_count'] = $count;
		while($start<$count) {
			$activities_url = SEARCH_URL . "activities/?format=json&offset={$start}&limit={$limit}&countries={$FILTER['countries']}";
			$activities_url .= $search_url;
			
			
			$content = file_get_contents($activities_url);
			$result = json_decode($content);
			$objects = $result->objects;
			$activities = objectToArray($objects);
			
			foreach($activities AS $a) {
				foreach($a['recipient_country'] AS $c) {
					if(isset($array['objects'][$c['iso']])) {
						$array['objects'][$c['iso']]['total_cnt']++;
					}
				}
			}
			
			$start+=$limit;
		}*/
		
	} else {
		/*
		$activities_url = SEARCH_URL . "activities/?format=json&limit=0";
		$search_url = "";
		if(!empty($_DEFAULT_ORGANISATION_ID)) {
			$search_url .= "&organisations=" . $_DEFAULT_ORGANISATION_ID;
		}
		
		if(!empty($FILTER['query'])) {
			$search_url .= "&query={$FILTER['query']}";
		}
		
		if(!empty($FILTER['regions'])) {
			$search_url .= "&regions={$FILTER['regions']}";
		}
		
		if(!empty($FILTER['sectors'])) {
			$search_url .= "&sectors={$FILTER['sectors']}";
		}
		
		if(!empty($FILTER['budgets'])) {
			$budgets = explode('|', trim($_REQUEST['budgets']));
			//Get the lowest budget from filter and use this one, all the other are included in the range
			ksort($budgets);
			$search_url .= "&statistics__total_budget__gt={$budgets[0]}";
		}
		$activities_url .= $search_url;
		$content = file_get_contents($activities_url);
		$result = json_decode($content);
		$meta = $result->meta;
		$count = $meta->total_count;
		
		if(!empty($search_url)) {
			$start=0;
			$limit=200;
			$array['objects'] = array();
			$array['meta']['total_count'] = $count;
			while($start<$count) {
				$activities_url = SEARCH_URL . "activities/?format=json&offset={$start}&limit={$limit}";
				$activities_url .= $search_url;
				$content = file_get_contents($activities_url);
				$result = json_decode($content);
				$objects = $result->objects;
				$activities = objectToArray($objects);
				foreach($activities AS $a) {
					foreach($a['recipient_country'] AS $c) {
						if(isset($array['objects'][$c['iso']])) {
							$array['objects'][$c['iso']]['total_cnt']++;
						} else {
							if(isset($_GM_POLYGONS[$c['iso']])) {
								$array['objects'][$c['iso']] = array('path' => $_GM_POLYGONS[$c['iso']], 'name' => $c['name'], 'total_cnt' => 1);
							}
						}
					}
				}
				$start+=$limit;
			}
		} else {*/
			$array['objects'] = array();
			$array['meta']['total_count'] = $count;
			foreach($_COUNTRY_ISO_MAP AS $iso=>$c) {
				if(isset($array['objects'][$iso])) {
					$array['objects'][$iso]['total_cnt']++;
				} else {
					if(isset($_GM_POLYGONS[$iso])) {
						$array['objects'][$iso] = array('path' => $_GM_POLYGONS[$iso], 'name' => $c, 'total_cnt' => $_COUNTRY_ACTIVITY_COUNT[$iso]);
					}
				}
			}
		//}
	}
	
	
	
	if(!isset($FILTER['inline'])) {
		echo json_encode($array);
	}




function getFilter(&$DATA, $format=1) {
	if (empty($DATA)) return false;
	if($format>2) return false;
	
	foreach ($DATA AS $key=>$value) {
		if($format==2) {
			$tmp->$key = $value;
		}elseif($format==1){
			$tmp["$key"] = $value;
		}
	}
	
	return $tmp;
}
?>