<?php

namespace AppBundle\Service;

use CL\Slack\Model\Attachment;
use CL\Slack\Model\ImChannel;
use CL\Slack\Payload\ChatDeletePayload;
use CL\Slack\Payload\ChatPostMessagePayload;
use CL\Slack\Transport\ApiClientInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Intl\Exception\NotImplementedException;

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
     * @param string $channelId
     * @param string $content
     * @param $attachments
     * @return \CL\Slack\Payload\PayloadResponseInterface
     */
    public function sendMessage($channelId, $content, array $attachments)
    {
        $payload = new ChatPostMessagePayload();
        $payload->setChannel($channelId);
        $payload->setUsername($this->userName);
        $payload->setText($content);
        foreach ($attachments as $attachment) {
            $payload->addAttachment($attachment);
        }
        return $this->client->send($payload);
    }

    /**
     * @param string $channelId
     * @param string $content
     * @return \CL\Slack\Payload\PayloadResponseInterface
     */
    public function sendMessageToAChannel($channelId, $content)
    {
        $payload = new ChatPostMessagePayload();
        $payload->setChannel($channelId);
        $payload->setUsername($this->userName);
        $payload->setText($content);

        return $this->client->send($payload);
    }

}