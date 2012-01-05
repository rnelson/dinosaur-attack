<?php

define('GMAP_STATIC_MAP_BASE', 'https://maps.googleapis.com/maps/api/staticmap');
define('GMAP_CUSTOM_MARKER_URL', 'http://pretendamazing.org/tmp/bus-small.jpg'); // TODO: get this on wtfmb.tractorfeed.org

// TODO: what size is sane?
function buildStaticMapUrl($lat, $lon, $size='200x200', $zoom=17) {
	$url = GMAP_STATIC_MAP_BASE . '?';
	
	$location = $lat . ',' . $lon;
	
	// The point to have as the center of our map
	$url .= 'center=' . $location . '&';
	
	// Zoom level
	$url .= 'zoom=' . $zoom . '&';
	
	// The size of the map
	$url .= 'size=' . $size . '&';
	
	// Use JPEG since everything can read it
	$url .= 'format=jpg&';
	
	// Roads are what we probably care about
	$url .= 'maptype=roadmap&';
	
	// Add a marker for the bus
	$url .= 'markers=icon:' . urlencode(GMAP_CUSTOM_MARKER_URL) . '%7C' . $location . '&';
	
	// We're not acquiring the user's location for the map, so this is false
	$url .= 'sensor=false';
	
	return $url;
}
