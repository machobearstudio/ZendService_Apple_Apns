<?php

namespace ZendServiceTest\Apple\Apns;

use PHPUnit\Framework\TestCase;
use ZendService\Apple\Apns\Client\AbstractClient;
use ZendService\Apple\Apns\Client\Message as Client;
use ZendService\Apple\Apns\Message;
use ZendService\Apple\Apns\Response\Message as Response;
use ZendService\Apple\Exception\RuntimeException;

class MessageSendTest extends TestCase
{
    public function setUp(): void
    {
    }

    public function testSend()
    {

        $bundleId = 'your.bundle.id';
        $cert = 'TestAsset/certificate.pem';
        $pwd = 'CertPwd';
        $token = 'a65222627d25e8afe14a6f03f673f6594b36f69db736bc9768c30498edbda1f4';

        $client = new Client();
        $client->open(AbstractClient::SANDBOX_URI, $cert, $pwd);

        $message = new Message();
        $message->setId('1');
        $message->setBundleId($bundleId);
        $message->setToken($token);
        $message->setBadge(0);
        $message->setSound('default');

        $message->setAlert('Push notification test');

        try {
            $response = $client->send($message);
        } catch (RuntimeException $e) {
            echo $e->getMessage() . PHP_EOL;
            exit(1);
        }
        $client->close();

        if ($response->getCode() != Response::RESULT_OK) {
            switch ($response->getCode()) {
                case Response::RESULT_BAD_REQUEST:
                    // check response body for more info
                    $this->fail('Bad request. Check response JSON body: ' . $response->getBody());
                case Response::RESULT_BAD_AUTH:
                    // There was an error with the certificate or with the provider’s authentication token.
                    $this->fail('you were missing a token');
                case Response::RESULT_INVALID_PATH:
                    // The request contained an invalid :path value.
                    $this->fail('you are missing a message id');
                case Response::RESULT_INVALID_METHOD:
                    // The request used an invalid :method value. Only POST requests are supported.
                    $this->fail('Invalid method value');
                case Response::RESULT_INVALID_TOKEN:
                    // The device token is no longer active for the topic.
                    $this->fail('The device token is no longer active for the topic');
                case Response::RESULT_INVALID_PAYLOAD_SIZE:
                    // the payload was too large
                    $this->fail('the payload was too large');
                case Response::RESULT_TOO_MANY_REQUESTS:
                    // The server received too many requests for the same device token.
                    $this->fail('The server received too many requests for the same device token.');
                case Response::RESULT_INTERNAL_SERVER_ERROR:
                    // Internal server error.
                    $this->fail('Internal server error.');
                case Response::RESULT_SERVER_UNAVAILABLE:
                    // The server is shutting down and unavailable.
                    $this->fail('The server is shutting down and unavailable.');
            }
        }
    }
}
