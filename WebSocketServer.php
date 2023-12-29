<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;

require __DIR__ . '/vendor/autoload.php';

class Chat implements MessageComponentInterface
{
    protected $clients;
    protected $onlineUsers = [];

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);

        // Assuming the username is sent as a query parameter named 'username'
        $queryParams = $conn->httpRequest->getUri()->getQuery();
        parse_str($queryParams, $queryParameters);

        // Use the provided username or generate a unique identifier if not provided
        $username = isset($queryParameters['username']) ? $queryParameters['username'] : 'User' . $conn->resourceId;
        $user_id = isset($queryParameters['user_id']) ? $queryParameters['user_id'] : '000' . $conn->resourceId;
        echo $conn->resourceId;
        $this->onlineUsers[$conn->resourceId] = ["name" => $username, "user_id" => $user_id];

        echo "New connection! ({$username})  ({$user_id})\n";

        // Notify all clients about the updated list of online users
        $this->sendOnlineUsers();
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // Broadcast the message to all connected clients
        foreach ($this->clients as $client) {
            $client->send($msg);
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";

        // Remove the username from the list of online users
        unset($this->onlineUsers[$conn->resourceId]);

        // Notify all clients about the updated list of online users
        $this->sendOnlineUsers();
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    protected function sendOnlineUsers()
    {
        $onlineUsernames = array_values($this->onlineUsers);

        foreach ($this->clients as $client) {
            $client->send(
                json_encode(
                    ["event" => "online_users", "data" => $onlineUsernames]
                )
            );
        }
    }
}

// Specify the IP address and port on which the server should run
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080, // Change this port if needed
    '92.205.147.175' // Change this IP address to your desired IP
);

echo "WebSocket Server running at http://92.205.147.175:8080\n";

$server->run();
?>
