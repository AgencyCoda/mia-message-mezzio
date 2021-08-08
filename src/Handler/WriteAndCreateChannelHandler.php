<?php

namespace Mia\Message\Handler;

use Mia\Message\Model\MiaMessage;
use Mia\Message\Model\MiaMessageChannel;
use Mia\Message\Model\MiaMessagePermission;
use Mia\Message\Repository\MiaMessageChannelRepository;

/**
 * Description of WriteHandler
 * 
 * @OA\Get(
 *     path="/mia_message/write-and-create-channel",
 *     summary="MiaMessage Write And Create Channel",
 *     tags={"MiaMessage"},
 *     @OA\Parameter(
 *         name="id",
 *         description="Id of Item",
 *         required=true,
 *         in="path"
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/MiaMessage")
 *     ),
 *     security={
 *         {"bearerAuth": {}}
 *     }
 * )
 *
 * @author matiascamiletti
 */
class WriteAndCreateChannelHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    /**
     * 
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Get Current user
        $user = $this->getUser($request);
        // Get user IDs
        $users = $this->getParam($request, 'users', []);
        // Create new channel
        $channel = $this->createChannel($user);
        // Process Users
        $this->processUsers($channel, $users);
        // Create message
        $message = new MiaMessage();
        $message->channel_id = $channel->id;
        $message->user_id = $user->id;
        $message->content = $this->getParam($request, 'content', '');
        $message->data = $this->getParam($request, 'data', []);
        $message->save();
        // Devolvemos respuesta
        return new \Mia\Core\Diactoros\MiaJsonResponse($message->toArray());
    }

    protected function createChannel($user)
    {
        $channel = new MiaMessageChannel();
        $channel->creator_id = $user->id;
        $channel->title = 'channel-by-' . $user->id;
        $channel->save();

        $perm = new MiaMessagePermission();
        $perm->channel_id = $channel->id;
        $perm->user_id = $user->id;
        $perm->save();

        return $channel;
    }

    protected function processUsers($channel, $userIds) 
    {
        foreach($userIds as $userId) {
            $perm2 = new MiaMessagePermission();
            $perm2->channel_id = $channel->id;
            $perm2->user_id = $userId;
            $perm2->save();
        }
    }
}