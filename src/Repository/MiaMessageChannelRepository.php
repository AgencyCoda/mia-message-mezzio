<?php

namespace Mia\Message\Repository;

use \Illuminate\Database\Capsule\Manager as DB;
use Mia\Message\Model\MiaMessageChannel;

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
            //$values = $search . '|' . implode('|', explode(' ', $search));
            //$query->whereRaw('(firstname REGEXP ? OR lastname REGEXP ? OR email REGEXP ?)', [$values, $values, $values]);
        }
        
        // Procesar parametros
        $configure->run($query);

        return $query->paginate($configure->getLimit(), ['*'], 'page', $configure->getPage());
    }
}
