<?php

namespace AppBundle\Command;

use AppBundle\Document\User;
use CL\Slack\Model\ImChannel;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReminderCommand extends ContainerAwareCommand
{

    /**
     * @var string[]
     */
    private static $days = [
        'sunday',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday'
    ];

    protected function configure()
    {
        $this
            ->setName('app:remind-users')
            ->setDescription('Reminds users');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
       $dm = $this->getDocumentManager();
       $users = $dm->getRepository('AppBundle:User')->findAll();
       foreach ($users as $user) {
           if (!$this->userHasMeals($user)) {
               $this->sendReminder($user);
               $output->writeln('Reminder sent to: ' . $user->getName() . ' (' . $user->getEmail() . ')');  
           }
       }
    }

    /**
     * @param User $user
     * @return bool
     */
    private function userHasMeals(User $user)
    {
            $orders = $this->getDocumentManager()
                ->getRepository('AppBundle:Order')
                ->findBy(['user.$id' => $user->getUserId(), 'day' => self::$days[date('w')]]);

        return count($orders) > 0;
    }

    /**
     * @param User $user
     */
    private function sendReminder(User $user)
    {
        $messaging = $this->getContainer()->get('AppBundle\Service\MessageService');
        $messaging->sendMessageToAChannel($user->getChannelId(), 'Please, order your meal for today, by 10 AM!');
    }

    /**
     * @return DocumentManager
     */
    private function getDocumentManager()
    {
        return $this->getContainer()->get('doctrine_mongodb')->getManager();
    }

}