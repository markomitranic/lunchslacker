<?php

namespace AppBundle\Command;

use AppBundle\Document\User;
use CL\Slack\Model\User as SlackUser;
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
        $users = $this->fetchUsersFromSlack();
        $emailNameMap = $this->getEmailNameMap();
        $this->sync($users, $emailNameMap);
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
     * @param array $slackUsers
     * @param array $emailNameMap
     */
    private function sync(array $slackUsers, array $emailNameMap)
    {
        $dm = $this->getDocumentManager();
        foreach ($slackUsers as $slackUser) {
            $user = $this->getUser($slackUser);
            if ($user && isset($emailNameMap[$user->getEmail()]) && $user->getEmail()) {
                $imChannel = $this->fetchImChannelFromSlack($slackUser->getId());
                $user->setChannelId($imChannel['id']);
                $user->setName($emailNameMap[$user->getEmail()]);
                $dm->persist($user);
            }
        }
        $dm->flush();
    }

    /**
     * @param SlackUser $user
     * @return User
     */
    private function getUser(SlackUser $slackUser)
    {
        if ($slackUser) {
            /** @var UserProfile $profile */
            $profile = $slackUser->getProfile();
            $user = new User();

            $user
                ->setUserId($slackUser->getId())
                ->setName($slackUser->getName())
                ->setEmail($profile->getEmail())
            ;

            return $user;
        }

        return null;
    }

    /**
     *
     */
    private function fetchImChannelFromSlack($userId)
    {
        return $this->getContainer()->get('AppBundle\Service\ImService')->getByUserId($userId);
    }

    /**
     *
     */
    private function fetchUsersFromSlack()
    {
        return $this->getContainer()->get('AppBundle\Service\UserService')->getAll();
    }


    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    private function getDocumentManager()
    {
        return $this->getContainer()->get('doctrine_mongodb')->getManager();
    }

}
