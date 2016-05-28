<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 16.29.5
 * Time: 02:18
 */

namespace Application\Warehouse\Dimension;

class CityDimension extends DimensionAbstract
{
    protected function getTable()
    {
        return 'dim_city';
    }
}