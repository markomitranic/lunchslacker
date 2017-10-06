<?php

namespace AppBundle\Controller;

use AppBundle\Document\Order;
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

    private function sendLunchArrivedAnnouncment()
    {
        $messageService = $this->get("AppBundle\Service\MessageService");

        $userRepository = $this->container->get('doctrine_mongodb')->getManager()->getRepository('AppBundle:User');
        $users = $userRepository->findAll();
        $attachments = [];
        foreach ($users as $user) {

            $orderRepository = $this->container->get('doctrine_mongodb')->getManager()->getRepository('AppBundle:Order');
            $orders = $orderRepository->findByUserAndDay($user, strtolower('l'));

            foreach ($orders as $order) {

                $attachment = new Attachment();
                $attachment->setPreText('*Your order for today*');
                $attachment->setColor('#7CD197');
                $attachment->setText($order->getMeal()->getName());
                $attachments[] = $attachment;

            }

            if (!count($orders)) {
                $messageService->sendMessage($user->getChannelId(), '*Lunch is here!*', $attachments);
            }
        }

        return new JsonResponse(['status' => 'ok']);
    }

    private function sendMenuToUserMessage($userId)
    {
        $messageService = $this->get("AppBundle\Service\MessageService");
        $messageService->sendMessage();
    }

    /**
     * @param string $userId
     * @param string $day
     */
    private function sendMenuForDay($userId, $day)
    {
        if (strtolower($day) == 'today') {
            $day = strtolower(date('l'));
        }
        $messageService = $this->get("AppBundle\Service\MessageService");
        $dm = $this->get('doctrine_mongodb')->getManager();
        $user = $dm->getRepository('AppBundle:User')->findOneBy(['userId' => $userId]);
        if ($user) {
            /** @var  Order[] $orders */
            $orders = $dm->getRepository('AppBundle:Order')->findByUserAndDay($user, $day);
            $attachments = [];
            if (count($orders)) {
                foreach ($orders as $order) {
                    $attachment = new Attachment();
                    $attachment->setPreText('pretext...');
                    $attachment->setColor('#7CD197');
                    $attachment->setText($order->getMeal()->getName());
                    $attachments[] = $attachment;
                }
                $messageService->sendMessage($user->getChannelId(), '*Your order for ' . $day . '*', $attachments);
            } else {
                $messageService->sendMessage($user->getChannelId(), '*You don\'t have orders for ' . $day . '*', $attachments);
            }
        }
    }
}