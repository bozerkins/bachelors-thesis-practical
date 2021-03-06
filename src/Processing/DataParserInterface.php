<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 16.28.5
 * Time: 23:48
 */

namespace Application\Processing;


interface DataParserInterface
{
    /**
     * @param array $input
     * @return array $output
     */
    public function parse(array $input);
}