<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 16.28.5
 * Time: 23:48
 */

namespace Application\Processing;


interface DataImportInterface
{
    /**
     * @param array $condition
     * @return array $input
     */
    public function import(array $condition);
}