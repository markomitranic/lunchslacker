<?php

namespace AppBundle\Service;

use AppBundle\Exception\LunchSlackerException;
use CL\Slack\Payload\ImOpenPayload;
use CL\Slack\Payload\ImOpenPayloadResponse;
use CL\Slack\Transport\ApiClientInterface;

/**
 * Class ImService
 * @package AppBundle\Service
 */
class ImService
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
     * @param string $userId
     * @return \CL\Slack\Model\ImChannel[]
     * @throws LunchSlackerException
     */
    public function getByUserId($userId)
    {
        $payload = new ImOpenPayload();
        $payload->setUserId($userId);

        /** @var $response ImOpenPayloadResponse */
        $response = $this->client->send($payload);

        if ($response->isOk()) {
            return $response->getChannel();
        }

        throw new LunchSlackerException('Could not retrieve IM Channel. Error message: '
            . $response->getError()
            . '. Explanation: ' . $response->getErrorExplanation());
    }

}