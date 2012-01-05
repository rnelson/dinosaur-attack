<?php

function get_data($route='53') {
	// OTvia2 -- invalid JSON
	//$url = 'http://getonboard.lincoln.ne.gov/packet/json/vehicle?routes=203,204,205,206,207,208,209,210,211,212,213,214,215,216,217,218,219&lastVehicleHttpRequestTime=' . time();
	
	// nko2/dinosaur-attack
	$url = 'http://elysium.pretendamazing.org:5309';
	
	// Request the stringified JSON
	$data = @json_decode(file_get_contents($url), true);
	
	if (NULL == $data) {
		return array();
	}
	
	// If we got a response, get the data we care about
	$json = '';
	foreach ($data as $key => $value) {
		if (is_array($value)) {
			$data = $value;
			break;
		}
	}
	
	/*
	// temp data
	$data = array(
		array (
		  'vehicle' => 
		  array (
		    'routeID' => 53,
		    'id' => 206,
		    'oos' => false,
		    'update' => true,
		    'WebLabel' => '<div class=\'labelVehicleHeader\'>53 SOUTHPOINTE</div><div class=\'labelCloseIcon\'></div><div class=\'labelVehicleCurrentRow\'>Current Stop&nbsp;&nbsp;22ND & VAN DORN-INBOUND</div><div class=\'labelVehicleCurrentRow\'>ETA&nbsp;&nbsp;ARRIVED <span class=\'bold\'>(NOW)</span></div><div class=\'clear\'></div><div class=\'labelVehicleNextRow\'>Next Stop&nbsp;&nbsp;STATE OFFICE BLDG-RT 53 IB</div><div class=\'labelVehicleNextRow\'>ETA&nbsp;&nbsp;&nbsp;7:01 PM Jan&nbsp;&nbsp;4th <span class=\'bold\' id=\'vehiclePred2061323512012115\'></span></div><div class=\'clear\'></div>',
		    'PredictionTimes' => 
		    array (
		      'vehiclePred2061323512012115' => 1325725260000,
		    ),
		    'CVLocation' => 
		    array (
		      'vehicleID' => 206,
		      'latitude' => 4078517,
		      'longitude' => -9668968,
		      'angle' => 2,
		      'locStatus' => 1,
		      'locTime' => 1325702504,
		      'speed' => 0,
		      'bValidSanp' => false,
		      'snapLat' => -1207938642,
		      'snapLong' => 20,
		      'snapAngle' => 0,
		    ),
		  ),
		), array (
		  'vehicle' => 
		  array (
		    'routeID' => 53,
		    'id' => 201,
		    'oos' => false,
		    'update' => true,
		    'WebLabel' => '<div class=\'labelVehicleHeader\'>53 SOUTHPOINTE</div><div class=\'labelCloseIcon\'></div><div class=\'labelVehicleCurrentRow\'>Next Stop&nbsp;&nbsp;SOUTHPOINTE</div><div class=\'labelVehicleCurrentRow\'>ETA&nbsp;&nbsp;&nbsp;6:46 PM Jan&nbsp;&nbsp;4th <span class=\'bold\' id=\'vehiclePred2011323512012116\'></span></div><div class=\'clear\'></div>',
		    'PredictionTimes' => 
		    array (
		      'vehiclePred2011323512012116' => 1325724360000,
		    ),
		    'CVLocation' => 
		    array (
		      'vehicleID' => 201,
		      'latitude' => 4074845,
		      'longitude' => -9666350,
		      'angle' => 180,
		      'locStatus' => 1,
		      'locTime' => 1325702510,
		      'speed' => 41,
		      'bValidSanp' => false,
		      'snapLat' => -1258270302,
		      'snapLong' => 20,
		      'snapAngle' => 0,
		    ),
		  ),
		)
	);
	*/
	
	// Find the actual buses in that mess
	$buses = array();
	foreach ($data as $k => $v) {
		if (isset($v['vehicle'])) {
			if (isset($v['vehicle']['routeID']) && $route == $v['vehicle']['routeID']) {
				$buses[] = $v;
			}
		}
	}
	
	$routes = array();
	if (count($buses) > 0) {
		foreach ($buses as $vehicle => $bRoute) {
			foreach ($bRoute as $junk => $aRoute) {
				// Create our new route, save the ID, default our label to just the ID
				$newRoute = array();
				$newRoute['buses'] = array();
				
				$newRoute['id'] = $aRoute['routeID'];
				$newRoute['label'] = $aRoute['routeID'];
				
				if (isset($aRoute['CVLocation']) && count($aRoute['CVLocation']) > 0) {
					foreach ($aRoute['CVLocation'] as $vehicle) {
						$bus = array();
						$bus['lat'] = intval($aRoute['CVLocation']['latitude']) * 10E-6;
						$bus['lon'] = intval($aRoute['CVLocation']['longitude']) * 10E-6;
						
						// Grab the WebLabel text and toss it into an object we can use
						$webLabelText = str_replace("'", '"', $aRoute['WebLabel']);
						$dom = new DOMDocument();
						@$dom->loadHTML($webLabelText);
						$xpath = new DOMXPath($dom);
						
						// Get the vehicle ID
						$bus['vid'] = $vehicle['vehicleID'];
						
						// Get the vehicle header (number and route name)
						$vehicleHeaders = $xpath->query("//*[contains(@class,'labelVehicleHeader')]");
						foreach ($vehicleHeaders as $vehicleHeader) {
							$newRoute['label'] = getTextFromNode($vehicleHeader);
						}
						
						// Get the hover text
						$bus['hover'] = '';
						/*
						$currentRows = $xpath->query("//*[contains(@class,'labelVehicleCurrentRow')]");
						$nextRows = $xpath->query("//*[contains(@class,'labelVehicleNextRow')]");
						$bus['hover'] = '';
						foreach ($currentRows as $currentRow) {
							$bus['hover'] .= getTextFromNode($currentRow) . "<br>\n";
						}
						foreach ($nextRows as $nextRow) {
							$bus['hover'] .= getTextFromNode($nextRow) . "<br>\n";
						}
						*/
						
						$bus['mapurl'] = buildStaticMapUrl($bus['lat'], $bus['lon']);
						
						// Add the bus; TODO: why am I getting duplicates and needing to do this?
						$add = true;
						foreach ($newRoute['buses'] as $aBus) {
							if ($aBus['mapurl'] == $bus['mapurl']) {
								$add = false;
								break;
							}
						}
						if ($add) {
							$newRoute['buses'][] = $bus;
						}
					}
				}
			}
			
			$routes[] = $newRoute;
		}
	}
	
	return $routes;
}