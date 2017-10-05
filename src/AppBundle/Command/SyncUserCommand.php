<?php

namespace AppBundle\Command;

use AppBundle\Document\User;
use CL\Slack\Model\ImChannel;
use CL\Slack\Model\UserProfile;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SyncUserCommand
 * @package AppBundle\Command
 */
class SyncUserCommand extends ContainerAwareCommand
{


    protected function configure()
    {
        $this
            ->setName('app:sync-users')
            ->setDescription('Synchronizes users from Slack.');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $channels = $this->getChannels();
        $emailNameMap = $this->getEmailNameMap();
        $this->sync($channels, $emailNameMap);
    }

    private function getChannels()
    {
       return $this->getContainer()->get('AppBundle\Service\ChannelService')->getImChannels();
    }

    /**
     * @return array
     */
    private function getEmailNameMap()
    {
        $buzz = $this->getContainer()->get('buzz');
        $response = $buzz->get($this->getContainer()->getParameter('google_spreadsheet_users'));
        $rawContent = $response->getContent();
        $lines = explode("\r\n", $rawContent);
        $headerArr = explode(',', array_shift($lines));
        $nameIndex = array_search('Name', $headerArr);
        $emailIndex = array_search('Official Email', $headerArr);
        $result = [];
        foreach ($lines as $line) {
            $lineArr = explode(',', $line);
            $result[$lineArr[$emailIndex]] = $lineArr[$nameIndex];
        }
        return $result;
    }

    /**
     * @param ImChannel[] $channels
     * @param array $emailNameMap
     */
    private function sync(array $channels, array $emailNameMap)
    {
        $dm = $this->getDocumentManager();
        foreach ($channels as $channel) {
            $user = $this->getUser($channel);
            if ($user && isset($emailNameMap[$user->getEmail()]) && $user->getEmail()) {
                $user->setName($emailNameMap[$user->getEmail()]);
                $dm->persist($user);
            }
        }
        $dm->flush();
    }

    /**
     * @param ImChannel $channel
     * @return User
     */
    private function getUser(ImChannel $channel)
    {
        $userResponse = $this->fetchUserFromSlack($channel->getuserId());
        if ($userResponse) {
            /** @var UserProfile $profile */
            $profile = $userResponse->getProfile();
            $user = new User();
            $user
                ->setUserId($userResponse->getId())
                ->setChannelId($channel->getId())
                ->setEmail($profile->getEmail())
            ;

            return $user;
        }

        return null;
    }

    /**
     * @param $userId
     * @return \CL\Slack\Model\User|null
     */
    private function fetchUserFromSlack($userId)
    {
        return $this->getContainer()->get('AppBundle\Service\UserService')->getById($userId);
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    private function getDocumentManager()
    {
        return $this->getContainer()->get('doctrine_mongodb')->getManager();
    }

}
