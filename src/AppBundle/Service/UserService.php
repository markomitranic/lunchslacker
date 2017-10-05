<?php

namespace AppBundle\Service;

use AppBundle\Exception\LunchSlackerException;
use CL\Slack\Payload\UsersInfoPayload;
use CL\Slack\Payload\UsersInfoPayloadResponse;
use CL\Slack\Transport\ApiClientInterface;

/**
 * Class UserService
 * @package AppBundle\Service
 */
class UserService
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
     * @return \CL\Slack\Model\User|null
     * @throws LunchSlackerException
     */
    public function getById($userId)
    {
        $payload = new UsersInfoPayload();
        $payload->setUserId($userId);
        /** @var UsersInfoPayloadResponse $response */
        $response = $this->client->send($payload);

        if ($response->isOk()) {
            return $response->getUser();
        }

        throw new LunchSlackerException('Could not retrieve IM channels. Error message: '
            . $response->getError()
            . '. Explanation: ' . $response->getErrorExplanation());
    }

}