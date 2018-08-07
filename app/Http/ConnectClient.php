<?php
namespace App\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;

class ConnectClient
{
    private $client;

    public function __construct(){
        $this->client = new Client();
    }

    public function connect($uri){
        //return $this->client->request('GET', $uri);

        try {
            return $this->client->request('GET', $uri);
        } catch (RequestException $e) {
            return false;
        } catch (ServerException $e) {
            return false;
        } catch (ClientException $e) {
            return false;
        } 
    }
    

}