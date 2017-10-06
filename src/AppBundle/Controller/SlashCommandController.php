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

        $content = [
            "text" => "*Lunch is here*",
            "unfurl_links" => true
        ];

        $attachment = new Attachment();
        $attachment->setTitle('Your order for today:');
        $attachment->setPreText('pretext...');
        $attachment->setColor('#7CD197');
        $attachment->setText('This is a line of text');

        $attachments = [];

        $attachments[] = $attachment;
        $attachment->setText('This is a line of text');
        $attachments[] = $attachment;

        $messageService->chatDelete('D7F4VAJCW');
        $messageService->sendMessage('D7F4VAJCW', $content["text"], $attachments);
    }

    private function sendMenuToUserMessage($userId) {
        $messageService = $this->get("AppBundle\Service\MessageService");
        $messageService->sendMessage();
    }

    private function sendMenuForDay($day) {
        $messageService = $this->get("AppBundle\Service\MessageService");
        $messageService->sendMessage();
    }
}