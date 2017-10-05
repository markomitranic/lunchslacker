<?php

namespace AppBundle\Service;

use AppBundle\Exception\LunchSlackerException;
use CL\Slack\Payload\ImListPayload;
use CL\Slack\Payload\ImListPayloadResponse;
use CL\Slack\Transport\ApiClientInterface;

/**
 * Class ChannelService
 * @package AppBundle\Service
 */
class ChannelService
{

    /**
     * @var ApiClientInterface
     */
    private $client;

    /**
     * MessageService constructor.
     * @param ApiClientInterface $client
     */
    public function __construct(ApiClientInterface $client)
    {
        $this->client = $client;
    }


    /**
     * @return \CL\Slack\Model\ImChannel[]
     * @throws LunchSlackerException
     */
    public function getImChannels()
    {
        $payload = new ImListPayload();

        /** @var $response ImListPayloadResponse */
        $response = $this->client->send($payload);

        if ($response->isOk()) {
            return $response->getImChannels();
        }

        throw new LunchSlackerException('Could not retrieve IM channels. Error message: '
            . $response->getError()
            . '. Explanation: ' . $response->getErrorExplanation());
    }

}