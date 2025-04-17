const WebSocket = require('ws');
const http = require('http');

// Create HTTP server
const server = http.createServer();

// Create WebSocket server
const wss = new WebSocket.Server({ server });

// Store connected clients
const clients = new Set();

// Handle WebSocket connections
wss.on('connection', function connection(ws) {
    // Add new client to the set
    clients.add(ws);
    console.log('New client connected');

    // Handle client disconnection
    ws.on('close', function() {
        clients.delete(ws);
        console.log('Client disconnected');
    });
});

// Create HTTP endpoint for notifications
server.on('request', (req, res) => {
    if (req.method === 'POST' && req.url === '/notify') {
        let body = '';
        
        req.on('data', chunk => {
            body += chunk.toString();
        });

        req.on('end', () => {
            try {
                const message = JSON.parse(body);
                
                // Broadcast message to all connected clients
                clients.forEach(client => {
                    if (client.readyState === WebSocket.OPEN) {
                        client.send(JSON.stringify(message));
                    }
                });

                res.writeHead(200, { 'Content-Type': 'application/json' });
                res.end(JSON.stringify({ success: true }));
            } catch (error) {
                res.writeHead(400, { 'Content-Type': 'application/json' });
                res.end(JSON.stringify({ success: false, error: error.message }));
            }
        });
    } else {
        res.writeHead(404);
        res.end();
    }
});

// Start server
const PORT = 8080;
server.listen(PORT, () => {
    console.log(`WebSocket server is running on port ${PORT}`);
});
