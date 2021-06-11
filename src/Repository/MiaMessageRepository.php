<?php

namespace Mia\Message\Repository;

use \Illuminate\Database\Capsule\Manager as DB;
use Mia\Message\Model\MiaMessage;

/**
 * Description of MiaMessageRepository
 *
 * @author matiascamiletti
 */
class MiaMessageRepository 
{
    /**
     * 
     * @param \Mia\Database\Query\Configure $configure
     * @return \Illuminate\Pagination\Paginator
     */
    public static function fetchByConfigure(\Mia\Database\Query\Configure $configure)
    {
        $query = MiaMessage::select('mia_message.*');
        
        if(!$configure->hasOrder()){
            $query->orderByRaw('id DESC');
        }
        $search = $configure->getSearch();
        if($search != ''){
            //$values = $search . '|' . implode('|', explode(' ', $search));
            //$query->whereRaw('(firstname REGEXP ? OR lastname REGEXP ? OR email REGEXP ?)', [$values, $values, $values]);
        }
        
        // Procesar parametros
        $configure->run($query);

        return $query->paginate($configure->getLimit(), ['*'], 'page', $configure->getPage());
    }

    public static function countNewMessages($userId)
    {
        $row = MiaMessage::selectRaw('COUNT(*) as total')
            ->whereRaw('(SELECT id FROM mia_message_permission WHERE mia_message_permission.channel_id = mia_message.channel_id AND mia_message_permission.user_id = '.$userId.' LIMIT 1) IS NOT NULL')
            ->where('mia_message.user_id', '<>', $userId)
            ->where('mia_message.is_read', 0)
            ->first();

        if($row === null||$row->total == null){
            return 0;
        }
        return $row->total;
    }
}
