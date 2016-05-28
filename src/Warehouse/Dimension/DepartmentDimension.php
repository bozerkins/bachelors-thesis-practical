<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 16.29.5
 * Time: 02:29
 */

namespace Application\Warehouse\Dimension;


class DepartmentDimension extends DimensionAbstract
{

    /**
     * @return string
     */
    protected function getTable()
    {
        return 'dim_department';
    }
}