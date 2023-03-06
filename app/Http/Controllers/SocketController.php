<?php

namespace App\Http\Controllers;

use Ratchet\MessageComponentInterface;

use Ratchet\ConnectionInterface;



// use Auth;

class SocketController extends Controller implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }
	/**
	 * When a new connection is opened it will be passed to this method
	 *
	 * @param ConnectionInterface $conn The socket/connection that just connected to your application
	 * @return mixed
	 */
	public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        $querystring = $conn->httpRequest->getUri()->getQuery();

        parse_str($querystring, $queryarray);

        if(isset($queryarray['typing']))
        {
            foreach($this->clients as $client)
            {
                $data['message'] = 'Someone is Typing....';
                $data['userId'] = $queryarray['userId'];
                $data['commentId'] = $queryarray['commentId'];

                if($client->resourceId != $conn->resourceId )
                {
                    $client->send(json_encode($data));
                }
            }
        }

	}
	
	/**
	 * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
	 *
	 * @param ConnectionInterface $conn The socket/connection that is closing/closed
	 * @return mixed
	 */
	public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        // foreach($this->clients as $client)
        // {
        //     $data['message'] = 'End of typing';
        //     if($client->resourceId != $conn->resourceId)
        //     {
        //         $client->send(json_encode($data));
        //     }
        // }
	}
	
	/**
	 * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
	 * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
	 *
	 * @param ConnectionInterface $conn
	 * @param \Exception $e
	 * @return mixed
	 */
	public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()} \n";
        $conn->close();
	}
	
	/**
	 * Triggered when a client sends data through the socket
	 *
	 * @param ConnectionInterface $from The socket/connection that sent the message to your application
	 * @param string $msg The message received
	 * @return mixed
	 */
	public function onMessage(ConnectionInterface $conn, $msg) {
        
        $commentData = json_decode($msg);

        if($commentData)
        {
            if(isset($commentData->isComment)){
                foreach($this->clients as $client)
                {   
                    $data['isComment'] = $commentData->isComment;
                    $data['commentData'] = $commentData;
                    $client->send(json_encode($data));
                }
            }
        }
	}
}
