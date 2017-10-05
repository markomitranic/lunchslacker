<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     *  @Route ("/aledujke", name="aledujke")
     *  @param Request $request
     */
    public function aledujkeAction(Request $request)
    {
        return new Response(print_r($this->get("AppBundle\Service\LunchFetchService")->fetchSheet(), true));
    }
}
