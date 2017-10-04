<?php

namespace AppBundle\Service;

use CL\Slack\Model\ImChannel;
use CL\Slack\Payload\ChatPostMessagePayload;
use CL\Slack\Transport\ApiClientInterface;

/**
 * Class MessageService
 * @package AppBundle\Service
 */
class MessageService
{

    /**
     * @var ApiClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $userName;

    /**
     * MessageService constructor.
     * @param ApiClientInterface $client
     * @param string $userName
     */
    public function __construct(ApiClientInterface $client, $userName)
    {
        $this->client = $client;
        $this->userName = $userName;
    }

    /**
     * @param ImChannel $channel
     * @param string $content
     * @return \CL\Slack\Payload\PayloadResponseInterface
     */
    public function sendMessage(ImChannel $channel, $content)
    {
        $payload = new ChatPostMessagePayload();
        $payload->setChannel($channel->getId());
        $payload->setUsername($this->userName);
        $payload->setText($content);

        return $this->client->send($payload);
    }

}