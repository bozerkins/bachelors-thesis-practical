<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 16.29.5
 * Time: 02:25
 */

namespace Application\Warehouse\Dimension;


class DateDimension extends DimensionAbstract
{

    /**
     * @return string
     */
    protected function getTable()
    {
        return 'dim_date';
    }
}