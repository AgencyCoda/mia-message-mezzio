<?php

namespace Mia\Message\Handler;

use Mia\Message\Model\MiaMessagePermission;
use Mia\Message\Repository\MiaMessageChannelRepository;

/**
 * Description of ListHandler
 * 
 * @OA\Post(
 *     path="/mia-message/channels",
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
class AddUserInChannelHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
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
        $addUserId = $this->getParam($request, 'user_id', 0);
        // Verify if exist channel
        $perm = MiaMessagePermission::where('user_id', $user->id)->where('channel_id', $channelId)->first();
        if($perm === null){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(1, 'Channel is not exist.');
        }
        // Verify if user has exist
        $perm2 = MiaMessagePermission::where('user_id', $addUserId)->where('channel_id', $channelId)->first();
        if($perm2 !== null){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(1, 'User has exist in channel');
        }
        // Create new permission
        $perm = new MiaMessagePermission();
        $perm->channel_id = $channelId;
        $perm->user_id = $addUserId;
        $perm->save();
        // Devolvemos respuesta
        return new \Mia\Core\Diactoros\MiaJsonResponse(true);
    }
}