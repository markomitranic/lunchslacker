<?php

namespace AppBundle\Command;

use AppBundle\Document\User;
use CL\Slack\Model\ImChannel;
use CL\Slack\Model\UserProfile;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MapCommand
 * @package AppBundle\Command
 */
class MapUserCommand extends ContainerAwareCommand
{


    protected function configure()
    {
        $this
            ->setName('app:map-user')
            ->setDescription('Map users from Slack Google spreadsheet')
            ->addOption('email', 'e', InputOption::VALUE_REQUIRED, 'User email')
            ->addOption('firstName', 'f', InputOption::VALUE_REQUIRED, 'First name')
            ->addOption('lastName', 'l', InputOption::VALUE_REQUIRED, 'First name')
        ;

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dm = $this->getDocumentManager();
        $user = $dm->getRepository('AppBundle:User')
            ->findOneBy(['email' =>  $input->getOption('email')]);
        if ($user) {
            $user
                ->setFirstName($input->getOption('firstName'))
                ->setLastName($input->getOption('lastName'))
            ;
            $dm->persist($user);
            $dm->flush();
        } else {
            $output->writeln('User with email ' . $input->getOption('email') . ' not found');
        }
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    private function getDocumentManager()
    {
        return $this->getContainer()->get('doctrine_mongodb')->getManager();
    }

}
