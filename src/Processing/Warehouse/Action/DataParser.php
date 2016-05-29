<?php

namespace Application\Processing\Warehouse\Action;

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
        $output['avg_client_actions'] = (float) $input['avg_client_actions'];
        $output['avg_client_time_spent'] = (float) $input['avg_client_time_spent'];

        return $output;
    }
}