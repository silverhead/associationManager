<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 05/05/2017
 *
 */

namespace AppBundle\Manager;


trait ListManagerTrait
{
    /**
     * @param array $options[
     *  'start' => 0,
     *  'limit' => 10,
     *  'orders' => ['id' => 'ASC'],
     *  'criteria' => ['id' => 1],
     * ]
     * @return Periodicity[]|array
     */
    public function findAll(array $options = [])
    {
        $criteria = isset($options['criteria'])?$options['criteria']:[];
        $orders = isset($options['orders'])?$options['orders']:['id' => 'ASC'];
        $start = isset($options['start'])?$options['start']: 0;
        $limit = isset($options['limit'])?$options['limit']: 10;

        return $this->getRepository()->findBy(
            $criteria,
            $orders,
            $limit,
            $start
        );
    }
}