// Load the http module to create an http server.
var http = require('http');

// Configure our HTTP server to respond with 'OK-RPROXY-JAVASCRIPT' to all requests.
var server = http.createServer(function (request, response) {
  response.writeHead(200, {"Content-Type": "text/plain"});
  response.end("OK-RPROXY-JAVASCRIPT\n");
});

// Listen on port 8000, IP defaults to 127.0.0.1
server.listen(8000);
