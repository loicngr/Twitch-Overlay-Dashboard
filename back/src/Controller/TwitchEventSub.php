<?php

namespace App\Controller;

use App\Entity\Stream;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TwitchEventSub extends BaseController
{
    #[Route(
        path: '/twitch/event-sub',
        name: 'twitch-event-sub',
        methods: Request::METHOD_POST,
    )]
    public function twitchEventSub(Request $request): Response
    {
        $content = $request->toArray();
        $status = $content['subscription']['status'] ?? null;
        $type = $content['subscription']['type'] ?? null;
        $isWebhookVerification = 'webhook_callback_verification_pending' === $status;
        $isEnabled = 'enabled' === $status;
        $response = new Response(
            '',
            Response::HTTP_OK,
            ['content-type' => 'text/html'],
        );

        if ($isWebhookVerification) {
            $response->headers->set('Content-Type', 'text/plain');
            $response->setContent($content['challenge']);
        }

        if ($isEnabled && 'channel.subscribe' === $type) {
            $event = $content['event'];

            $user = (new User())
                ->setId($event['user_id'])
                ->setLogin($event['user_login'])
                ->setDisplayName($event['user_name'])
                ->setEmail('email@' . $event['user_id'] . '.fr')
                ->setCreatedAt(new DateTimeImmutable())
            ;

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        if ($isEnabled && 'stream.online' === $type) {
            $event = $content['event'];

            /** @var UserRepository $streamUser */
            $streamUser = $this->entityManager->getRepository(User::class);
            /** @var User $user */
            $user = $streamUser->find(1);

            $stream = (new Stream())
                ->setId($event['id'])
                ->setUser($user)
                ->setTitle('titre')
                ->setType(Stream::TYPE_LIVE)
                ->setStartAt(new DateTimeImmutable($event['started_at']))
            ;

            $this->entityManager->persist($stream);
            $this->entityManager->flush();
        }

        return $response;
    }
}
