<?php

namespace Mia\Message\Handler;

use Mia\Message\Repository\MiaMessageRepository;

/**
 * Description of WriteHandler
 * 
 * @OA\Get(
 *     path="/mia_message/new-messages",
 *     summary="MiaMessage News messages",
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
class NewMessagesHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
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
        // Devolvemos respuesta
        return new \Mia\Core\Diactoros\MiaJsonResponse(['total' => MiaMessageRepository::countNewMessages($user->id)]);
    }
}