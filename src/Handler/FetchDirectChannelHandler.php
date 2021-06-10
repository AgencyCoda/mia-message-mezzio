<?php

namespace Mia\Message\Handler;

use Mia\Message\Repository\MiaMessageChannelRepository;

/**
 * Description of WriteHandler
 * 
 * @OA\Get(
 *     path="/mia_message/fetch-direct-channel",
 *     summary="MiaMessage Fetch Direct Channel",
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
class FetchDirectChannelHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
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
        $channel = MiaMessageChannelRepository::fetchDirectChannel($user->id, $userId);
        if($channel === null){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(1, 'Channel is not exist.');
        }
        $data = $channel->toArray();
        $data['users'] = $channel->users->toArray();
        // Devolvemos respuesta
        return new \Mia\Core\Diactoros\MiaJsonResponse($data);
    }
}