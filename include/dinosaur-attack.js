var sys = require("util"),
    http = require("http"),
    url = require("url"),
    path = require("path"),
    fs = require("fs"),
    events = require("events");

function load_static_file(uri, response) {
	var filename = path.join(process.cwd(), uri);
	path.exists(filename, function(exists) {
		if(!exists) {
			response.sendHeader(404, {"Content-Type": "text/plain"});
			response.write("404 Not Found\n");
			response.close();
			return;
		}

		fs.readFile(filename, "binary", function(err, file) {
			if(err) {
				response.sendHeader(500, {"Content-Type": "text/plain"});
				response.write(err + "\n");
				response.close();
				return;
			}

			response.sendHeader(200);
			response.write(file, "binary");
			response.close();
		});
	});
}

var OTVIAHOST = "getonboard.lincoln.ne.gov";
var OTVIAPATH = "/packet/json/vehicle?routes=203,204,205,206,207,208,209,210,211,212,213,214,215,216,217,218,219";

var gob_client = http.createClient(80, OTVIAHOST);
var last_req = Math.round((new Date()).getTime());
var gob_emitter = new events.EventEmitter();
var latest_json = '{}';

function get_buses() {
	var request = gob_client.request("GET", OTVIAPATH + "&lastVehicleHttpRequestTime="+last_req.toString(), {"host": OTVIAHOST});
	last_req = Math.round((new Date()).getTime());
	request.addListener("response", function(response) {
		var body = "";
		response.addListener("data", function(data) {
			body += data;
		});

		response.addListener("end", function() {
			eval("var buses = " + body);
			sys.puts(buses)
			if(!(buses === undefined)) {
				gob_emitter.emit("buses", buses);
			}
		});
	});

	request.end();
}

setInterval(get_buses, 15000);
var listener = gob_emitter.addListener("buses", function(buses) {
		//sys.puts(JSON.stringify(buses));
		latest_json = JSON.stringify(buses);
	});
http.createServer(function (req, res) {
  res.writeHead(200, {'Content-Type': 'application/javascript'});
  res.end(latest_json)
}).listen(5309, "0.0.0.0");
sys.puts("Server running at http://0.0.0.0:5309/");