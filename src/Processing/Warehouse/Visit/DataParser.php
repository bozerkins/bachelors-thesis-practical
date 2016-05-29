<?php

namespace Application\Processing\Warehouse\Visit;

use Application\Processing\DataParserInterface;

use Application\Warehouse\Dimension\CityDimension;
use Application\Warehouse\Dimension\DateDimension;

class DataParser implements DataParserInterface
{

    /**
     * @param array $input
     * @return array $output
     */
    public function parse(array $input)
    {
        $output = array ();
        $model = new DateDimension();
        $output['dim_date_key'] = $model->get(array('date' => $input['date']))['date'];
        $output['clients'] = (int) $input['clients'];
        $output['visits'] = (int) $input['visits'];


        return $output;
    }
}