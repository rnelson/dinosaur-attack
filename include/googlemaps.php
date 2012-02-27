<?php

define('GMAP_STATIC_MAP_BASE', 'https://maps.googleapis.com/maps/api/staticmap');
define('GMAP_CUSTOM_MARKER_URL', 'http://pretendamazing.org/tmp/bus-small.jpg'); // TODO: get this on wtfmb.tractorfeed.org

// TODO: what size is sane?
function buildStaticMapUrl($lat, $lon, $size='200x200', $zoom=17) {
	$url = GMAP_STATIC_MAP_BASE . '?';
	
	$location = $lat . ',' . $lon;
	
	// The point to have as the center of our map
	$url .= 'center=' . $location . '&amp;';
	
	// Zoom level
	$url .= 'zoom=' . $zoom . '&amp;';
	
	// The size of the map
	$url .= 'size=' . $size . '&amp;';
	
	// Use JPEG since everything can read it
	$url .= 'format=jpg&amp;';
	
	// Roads are what we probably care about
	$url .= 'maptype=roadmap&amp;';
	
	// Add a marker for the bus
	$url .= 'markers=icon:' . urlencode(GMAP_CUSTOM_MARKER_URL) . '%7C' . $location . '&amp;';
	
	// We're not acquiring the user's location for the map, so this is false
	$url .= 'sensor=false';
	
	return $url;
}
