<?php

namespace Mia\Message\Repository;

use \Illuminate\Database\Capsule\Manager as DB;
use Mia\Message\Model\MiaMessageChannel;
use Mia\Message\Model\MiaMessagePermission;

/**
 * Description of MiaMessageChannelRepository
 *
 * @author matiascamiletti
 */
class MiaMessageChannelRepository 
{
    /**
     * 
     * @param \Mia\Database\Query\Configure $configure
     * @return \Illuminate\Pagination\Paginator
     */
    public static function fetchByConfigure(\Mia\Database\Query\Configure $configure)
    {
        $query = MiaMessageChannel::select('mia_message_channel.*');
        
        if(!$configure->hasOrder()){
            $query->orderByRaw('updated_at DESC');
        }
        $search = $configure->getSearch();
        if($search != ''){
            $values = $search . '|' . implode('|', explode(' ', $search));
            $query->whereRaw('(SELECT CONCAT(mia_message.content, " ", mia_message.data) FROM mia_message WHERE mia_message.channel_id = mia_message_channel.id ORDER BY id DESC LIMIT 1) REGEXP ?', $values);
        }
        
        // Procesar parametros
        $configure->run($query);

        return $query->paginate($configure->getLimit(), ['*'], 'page', $configure->getPage());
    }
    /**
     * Fecth direct channel
     *
     * @param int $creatorId
     * @param int $userId
     * @return MiaMessageChannel
     */
    public static function fetchDirectChannel($creatorId, $userId)
    {
        $channels = MiaMessagePermission::where('user_id', $creatorId)->get()->toArray();
        $channelIds = array_map(function($c){
            return $c['id'];
        }, $channels);

        return MiaMessagePermission::where('user_id', $userId)->whereIn('channel_id', $channelIds)->first();
    }
}
