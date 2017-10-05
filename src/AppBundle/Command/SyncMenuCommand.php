<?php

namespace AppBundle\Command;

use AppBundle\Document\Meal;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SyncLunchCommand
 * @package AppBundle\Command
 */
class SyncMenuCommand extends ContainerAwareCommand
{

    /**
     * @var string[]
     */
    private static $days = [
        'NEDELJA' => 'sunday',
        'PONEDELJAK' => 'monday',
        'UTORAK' => 'tuesday',
        'SREDA' => 'wednesday',
        'CETVRTAK' => 'thursday',
        'PETAK' => 'friday',
        'SUBOTA' => 'saturday'
    ];

    /**
     * @var array
     */
    private static $userMap = [];

    protected function configure()
    {
        $this
            ->setName('app:sync-menu')
            ->setDescription('Synchronizes menu from Google Spreadsheet.');
    }

    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sheet = $this->getSpreadsheet();
        foreach ($sheet as $day => $menu) {
            foreach ($menu as $meal => $users) {
                foreach ($users as $user) {
                    if (date('w') !== array_search($day, array_keys(self::$days))) {
                        $date = date('Y-m-d',  strtotime('next ' .  self::$days[$day]));
                    } else {
                        $date = date('Y-m-d');
                    }
                    $meal = new Meal();
                    $meal->setDate($date);
                    $meal->setUser($this->getUserByName($user));
                    $meal->setMeal($meal);
                }
            }
        }
    }

    /**
     * @return array
     */
    private function getSpreadsheet()
    {
        $buzz = $this->getContainer()->get('buzz');
        $response = $buzz->get($this->getContainer()->getParameter('google_spreadsheet_menu_tsv'));
        return $this->parseContent($response->getContent());
    }

    /**
     * @param string $content
     * @return array
     */
    private function parseContent($content)
    {
        $lines = explode("\r\n", $content);
        $result = [];
        $day = null;
        foreach ($lines as $line) {
            $lineArr = explode("\t", $line);

            if (array_key_exists($lineArr[0], self::$days)) {
                $day = $lineArr[0];
                continue;
            }

            if (is_null($day)) {
                continue;
            }

            if (!isset($result[$day])) {
                $result[$day] = [];
            }

            if (!isset($result[$day][$lineArr[0]])) {
                $result[$day][$lineArr[0]] = [];
            }

            for ($i = 2; $i <= count($lineArr); $i++) {
                if (!empty($lineArr[$i]))
                $result[$day][$lineArr[0]][] = $lineArr[$i];
            }

        }
        return $result;
    }

}