<?php

namespace Mia\Message\Handler;

use Mia\Message\Model\MiaMessageChannel;
use Mia\Message\Model\MiaMessagePermission;
use Mia\Message\Repository\MiaMessageChannelRepository;

/**
 * Description of WriteHandler
 * 
 * @OA\Get(
 *     path="/mia_message/create-direct-channel",
 *     summary="MiaMessage Create Direct Channel",
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
class CreateDirectChannelHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
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
        // Get user for create channel
        $userId = $this->getParam($request, 'user_id', 0);
        // Verify if exist channel
        $perm = MiaMessageChannelRepository::fetchDirectChannel($user->id, $userId);
        if($perm === null){
            // Create new channel
            $channel = new MiaMessageChannel();
            $channel->creator_id = $user->id;
            $channel->title = 'direct-channel';
            $channel->save();
            // Create Permissions
            $perm = new MiaMessagePermission();
            $perm->channel_id = $channel->id;
            $perm->user_id = $user->id;
            $perm->save();

            $perm2 = new MiaMessagePermission();
            $perm2->channel_id = $channel->id;
            $perm2->user_id = $userId;
            $perm2->save();
        } else {
            $channel = $perm->channel;
        }
        $data = $channel->toArray();
        $data['users'] = $channel->users->toArray();

        // Devolvemos respuesta
        return new \Mia\Core\Diactoros\MiaJsonResponse($data);
    }
}