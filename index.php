<?php
	header('Content-Type: text/html;charset=iso-8859-1');
	
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
		<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1">
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
				<font size="-1">
					<!-- #LNK-specific list; will be pulled by script later and thus can work in other supported cities -->
                                        <a href="/?route=24">Holdredge</a> | <a href="/?route=40">Heart Hospital</a> | <a href="/?route=41">Havelock</a>
                                        <br>
                                        <a href="/?route=42">Bethany</a> | <a href="/?route=43">Normal</a> | <a href="/?route=44">O St. - SCC</a>
                                        <br>
                                        <a href="/?route=45">Arapahoe</a> | <a href="/?route=46">Arnold Heights</a> | <a href="/?route=47">Belmont</a>
                                        <br>
                                        <a href="/?route=48">Salt Valley</a> | <a href="/?route=49">University Place</a> | <a href="/?route=50">College View</a>
                                        <br>
                                        <a href="/?route=51">West A</a> | <a href="/?route=52">Gaslight</a> | <a href="/?route=53">SouthPointe</a>
                                        <br>
                                        <a href="/?route=54">Vet's Hospital</a> | <a href="/?route=55">Star Shuttle</a>
					<br>
					<br>
					
					This is an incredibly simplified version of the tracker
					<br>
					found on <a href="http://getonboard.lincoln.ne.gov">getonboard.lincoln.ne.gov</a>, intended to work
					<br>
					well on the dumbest of dumbphones. Static images
					<br>
					and HTML 4.01.
					<br>
					<br>
					Upcoming features:
					<br>
					<b>1.</b> Bus icon changing direction
					<br>&nbsp;&nbsp;to match bus direction
					<br>
					<b>2.</b> Map zoom
				</font>
				<br>
				<br>
                                <font size="-2">Where the #@&*'s My Bus?, a <a href="http://tractorfeed.org">Tractor Feed</a> project</font>
                                <br>
				<font size="-2">Written by @<a href="http://twitter.com/rossnelson">rossnelson</a> and @<a href="http://twitter.com/natebenes">natebenes</a>.</font>
				<br>
				<font size="-2"><a href="https://github.com/rnelson/dinosaur-attack">Fork me on GitHub!</a></font>
			</p>
		</center>
	</body>
</html>
