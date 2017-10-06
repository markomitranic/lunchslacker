<?php

namespace AppBundle\Controller;

use CL\Slack\Model\Attachment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SlashCommandController extends Controller
{
    /**
     * @Route ("/slash-command")
     * @param Request $request
     */
    public function slashCommandAction(Request $request)
    {
        $channelId = $request->get("channel_id");
        $userId = $request->get("user_id");
        $text = $request->get("text");

        switch ($text) {
            case 'arrived': {
                $this->sendLunchArrivedAnnouncment();
                break;
            }
            case 'menu': {
                $this->sendMenuToUserMessage($userId);
                break;
            }
            case 'today':
            case 'monday':
            case 'tuesday':
            case 'wednesday':
            case 'thursday':
            case 'friday':
            case 'saturday':
            case 'sunday': {
                $this->sendMenuForDay($userId, $text);
                break;
            }
        }

        return new JsonResponse([
            "channel_id" => $channelId,
            "user_id" => $userId,
            "text" => $text,
        ]);
    }

    private function sendLunchArrivedAnnouncment() {
        $messageService = $this->get("AppBundle\Service\MessageService");

        $dm = $this->getDoctrine()->getManager();
        $userRepository = $this->container->get('doctrine_mongodb')->getManager()->getRepository('AppBundle:User');
        $users = $userRepository->findAll();

        foreach ($users as $user) {
            $attachment = new Attachment();
            $attachment->setTitle('Your order for today:');
            $attachment->setPreText('pretext...');
            $attachment->setColor('#7CD197');
            $attachment->setText('This is a line of text');

            $attachments = [];

            $attachments[] = $attachment;
            $attachment->setText('This is a line of text');
            $attachments[] = $attachment;

            $messageService->sendMessage($user->getChannelId(), '*Lunch is here!*', $attachments);
        }
    }

    private function sendMenuToUserMessage($userId) {
        $messageService = $this->get("AppBundle\Service\MessageService");
        $messageService->sendMessage();
    }

    /**
     * @param string $userId
     * @param string $day
     */
    private function sendMenuForDay($userId, $day) {
        $messageService = $this->get("AppBundle\Service\MessageService");
        $dm = $this->get('doctrine_mongodb')->getManager();
        $user = $dm->getRepository('AppBundle:User')->findOneBy(['userId' => $userId]);
        if ($user) {
            $orders = $dm->getRepository('AppBundle:Order')->findBy(['$id' => $userId, 'day' => $day]);
            $attachments = [];
            foreach ($orders as $order) {
                $attachment = new Attachment();
                $attachment->setPreText('pretext...');
                $attachment->setColor('#7CD197');
                $attachment->setText($order->getMeal()->getName());
                $attachment->setText('This is a line of text');
                $attachments[] = $attachment;

            }
            $messageService->sendMessage($user->getChannelId(), '*Your order for ' . $day .'*', $attachments);
        }
    }
}