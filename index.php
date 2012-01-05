<?php
	
	require_once('include/googlemaps.php');
	require_once('include/util.php');
	
	// See if we got the city and route from the user. If nothing was specified, we'll default
	// to showing all routes in the default city. And because we only support Lincoln, NE right
	// now, that'll be our default.
	$city = 'lnk';
	$route = '53';
	
	// Get route and/or city if the user specified them
	if (isset($_GET['city']) && strlen($_GET['city'] > 0)) {
		$city = $_GET['city'];
	}
	if (isset($_GET['route'])) {
		$route = $_GET['route'];
	}
	
	// Get the data
	require_once('include/cities/' . $city . '.php');
	$routes = get_data($route);
	
	// Build our page title
	$pageTitle = 'WTFMB - ' . $city;
	if (strlen($route)) {
		$pageTitle .= ' - ' . $route;
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title><?php echo $pageTitle; ?></title>
	</head>
	<body>
		<center>
<?php
	if (count($routes) > 0) {
		foreach ($routes as $route) {
?>
			<h1><?php echo $route['label']; ?></h1>
<?php
			foreach ($route['buses'] as $bus) {
?>
			<p>
				<img src="<?php echo $bus['mapurl']; ?>" alt="<?php echo $bus['hover']; ?>"><br>
				<?php echo $bus['hover']; ?>
			</p>
<?php
			}
		}
	}
	else {
?>
			<p>
				Sorry! No buses on route <?php echo $route; ?> are currently running.
			</p>
			<p>
				If your bus should be running, it's possible that the server is down. Let 
				@<a href="http://twitter.com/rossnelson">rossnelson</a> know and he'll try to 
				fix it when he gets a chance.
			</p>
<?php
	}
?>
			<p>
				<font size="-2">Where the #@&*'s My Bus?, a <a href="http://tractorfeed.org">Tractor Feed</a> project</font>
			</p>
			<p>
				<font size="-2">Hacked together by @<a href="http://twitter.com/rossnelson">rossnelson</a> out of necessity.</font>
			</p>
		</center>
	</body>
</html>