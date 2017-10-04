<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    public function readMenu(Request $request)
    {
        $buzz = $this->container->get('buzz');
        $response = $buzz->get('https://docs.google.com/spreadsheets/d/1bj7NpJl3TnnmvMDI65AzaAR4c3hb3_Ifr_Nw7FL-lEs/export?format=csv&id=1bj7NpJl3TnnmvMDI65AzaAR4c3hb3_Ifr_Nw7FL-lEs&gid=0');
        return $response;
    }
}
