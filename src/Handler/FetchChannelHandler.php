<?php

namespace Mia\Message\Handler;

use Mia\Message\Model\MiaMessageChannel;
use Mia\Message\Model\MiaMessagePermission;
use Mia\Message\Repository\MiaMessageChannelRepository;

/**
 * Description of WriteHandler
 * 
 * @OA\Get(
 *     path="/mia_message/fetch-channel",
 *     summary="MiaMessage Fetch Channel",
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
class FetchChannelHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
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
        $channelId = $this->getParam($request, 'id', 0);
        // Verify if exist channel
        $perm = MiaMessagePermission::where('user_id', $user->id)->where('channel_id', $channelId)->first();
        if($perm === null){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(1, 'Channel is not exist.');
        }
        $data = $perm->channel->toArray();
        $data['users'] = $perm->channel->users->toArray();
        // Devolvemos respuesta
        return new \Mia\Core\Diactoros\MiaJsonResponse($data);
    }
}