<?php

namespace Mia\Message\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Mia\Auth\Model\MIAUser;

/**
 * Description of Model
 * @property int $id ID of item
 * @property mixed $creator_id Description for variable
 * @property mixed $title Description for variable
 * @property mixed $type Description for variable
 * @property mixed $status Description for variable
 * @property mixed $created_at Description for variable
 * @property mixed $updated_at Description for variable

 *
 * @OA\Schema()
 * @OA\Property(
 *  property="id",
 *  type="integer",
 *  description=""
 * )
 * @OA\Property(
 *  property="creator_id",
 *  type="integer",
 *  description=""
 * )
 * @OA\Property(
 *  property="title",
 *  type="string",
 *  description=""
 * )
 * @OA\Property(
 *  property="type",
 *  type="integer",
 *  description=""
 * )
 * @OA\Property(
 *  property="status",
 *  type="integer",
 *  description=""
 * )
 * @OA\Property(
 *  property="created_at",
 *  type="",
 *  description=""
 * )
 * @OA\Property(
 *  property="updated_at",
 *  type="",
 *  description=""
 * )

 *
 * @author matiascamiletti
 */
class MiaMessageChannel extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'mia_message_channel';
    
    //protected $casts = ['data' => 'array'];
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    //public $timestamps = false;

    /**
    * 
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function creator()
    {
        return $this->belongsTo(MIAUser::class, 'creator_id');
    }
    /**
    * 
    * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
    */
    public function users()
    {
        return $this->hasManyThrough(MIAUser::class, MiaMessagePermission::class, 'channel_id', 'id', 'id', 'user_id');
    }
    /**
     * 
     *
     * @return HasMany
     */
    public function lastMessage()
    {
        return $this->hasMany(MiaMessage::class, 'channel_id')->orderBy('created_at', 'desc')->take(1);
    }
}