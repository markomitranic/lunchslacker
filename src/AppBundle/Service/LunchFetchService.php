<?php

namespace AppBundle\Service;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class MessageService
 * @package AppBundle\Service
 */
class LunchFetchService
{

    public $sheetId = "1bj7NpJl3TnnmvMDI65AzaAR4c3hb3_Ifr_Nw7FL-lEs";

    private $buzz;

    public function __construct($buzz)
    {
        $this->buzz = $buzz;
    }


    public function fetchSheet($sheetId = '')
    {
        if($sheetId === '') {
            $sheetId = $this->sheetId;
        }
        $buzz = $this->buzz;
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);

        // encoding contents in CSV format
        // TODO: don't just hardcode like this
        $response = $buzz->get('https://docs.google.com/spreadsheets/d/' . $sheetId . '/export?format=csv');
        $csvData = $serializer->decode($response->getContent(), 'csv');
        return $csvData;
    }

    public function parseCSVData($csv)
    {
        foreach($csv as $rowName => $value) {

        }
    }

    public function fetchByUser($user) {
        $this->fetchSheet();
    }

    public function fetchByUserByDay($day) {
    }
}