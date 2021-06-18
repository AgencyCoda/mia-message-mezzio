<?php

namespace Mia\Message\Handler;

use DateTime;
use Mia\Message\Model\MiaMessageChannel;
use Mia\Message\Model\MiaMessagePermission;
use Mia\Message\Repository\MiaMessageRepository;

/**
 * Description of ListHandler
 * 
 * @OA\Post(
 *     path="/mia-message/messages-group-date",
 *     summary="Mia Message List",
 *     tags={"MiaMessage"},
 *     @OA\RequestBody(
 *         description="Object query",
 *         required=false,
 *         @OA\MediaType(
 *             mediaType="application/json",                 
 *             @OA\Schema(
 *                  @OA\Property(
 *                      property="page",
 *                      type="integer",
 *                      description="Number of pace",
 *                      example="1"
 *                  ),
 *                  @OA\Property(
 *                      property="where",
 *                      type="string",
 *                      description="Wheres | Searchs",
 *                      example=""
 *                  ),
 *                  @OA\Property(
 *                      property="withs",
 *                      type="array",
 *                      description="Array of strings",
 *                      example="[]"
 *                  ),
 *                  @OA\Property(
 *                      property="search",
 *                      type="string",
 *                      description="String of search",
 *                      example=""
 *                  ),
 *                  @OA\Property(
 *                      property="ord",
 *                      type="string",
 *                      description="Ord",
 *                      example=""
 *                  ),
 *                  @OA\Property(
 *                      property="asc",
 *                      type="integer",
 *                      description="Integer",
 *                      example="1"
 *                  ),
 *                  @OA\Property(
 *                      property="limit",
 *                      type="integer",
 *                      description="Limit",
 *                      example="50"
 *                  )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(
 *              allOf={
 *                  @OA\Property(ref="#/components/schemas/MiaJsonResponse"),
 *                  @OA\Property(
 *                      property="response",
 *                      type="array",
 *                      @OA\Items(type="object", ref="#/components/schemas/MiaFinder")
 *                  )
 *              }
 *          )
 *     ),
 *     security={
 *         {"bearerAuth": {}}
 *     },
 * )
 *
 * @author matiascamiletti
 */
class MessagesGroupDateHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    /**
     * 
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface 
    {
        // Get Current User
        $user = $this->getUser($request);
        // Obtenemos ID si fue enviado
        $itemId = $this->getParam($request, 'channel_id', '');
        // Buscar si existe el tour en la DB
        $perm = MiaMessagePermission::where('channel_id', $itemId)->where('user_id', $user->id)->first();
        // verificar si existe
        if($perm === null){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(1, 'Channel is not exist.');
        }
        // Configurar query
        $configure = new \Mia\Database\Query\Configure($this, $request);
        $configure->addWhere('channel_id', $itemId);
        // Process Query
        $rows = MiaMessageRepository::fetchByConfigure($configure)->groupBy(function($item) {
            return DateTime::createFromFormat('Y-m-d H:i:s', $item->created_at)->format('Y-m-d');
        });
        // Return data
        return new \Mia\Core\Diactoros\MiaJsonResponse($rows->toArray());
    }
}