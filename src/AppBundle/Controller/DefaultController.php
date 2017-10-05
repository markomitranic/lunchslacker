<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
       return new JsonResponse('bla');
    }

    /**
     * @Route("/read-menu", name="readMenu")
     */
    public function readMenuAction(Request $request)
    {
        $buzz = $this->container->get('buzz');
//        $serializer = $this->container->get('serializer');
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);

// encoding contents in CSV format
        $response = $buzz->get('https://docs.google.com/spreadsheets/d/1bj7NpJl3TnnmvMDI65AzaAR4c3hb3_Ifr_Nw7FL-lEs/export?format=csv&id=1bj7NpJl3TnnmvMDI65AzaAR4c3hb3_Ifr_Nw7FL-lEs&gid=0');
        $csvData = $serializer->decode($response->getContent(), 'csv');
        return new Response(print_r($csvData, true));
    }
}
