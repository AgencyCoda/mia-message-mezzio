<?php

namespace Mia\Message\Handler;

use Mia\Message\Model\MiaMessage;
use Mia\Message\Model\MiaMessageChannel;
use Mia\Message\Model\MiaMessagePermission;

/**
 * Description of WriteHandler
 * 
 * @OA\Get(
 *     path="/mia_message/write",
 *     summary="MiaMessage Write",
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
class WriteHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
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
        // Obtenemos ID si fue enviado
        $itemId = $this->getParam($request, 'channel_id', '');
        // Buscar si existe el tour en la DB
        $perm = MiaMessagePermission::where('channel_id', $itemId)->where('user_id', $user->id)->first();
        // verificar si existe
        if($perm === null){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(1, 'Channel is not exist.');
        }

        // Create message
        $message = new MiaMessage();
        $message->channel_id = $itemId;
        $message->user_id = $user->id;
        $message->content = $this->getParam($request, 'content', '');
        $message->save();

        // Update channel date
        $perm->channel->touch();

        // Devolvemos respuesta
        return new \Mia\Core\Diactoros\MiaJsonResponse($message->toArray());
    }
}