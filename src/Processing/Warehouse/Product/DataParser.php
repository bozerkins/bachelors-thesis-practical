<?php

namespace Application\Processing\Warehouse\Product;

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
        $output['emp_no'] = $input['emp_no'];
        $output['markup'] = $input['markup'];
        $output['price_net'] = $input['price_net'];
        $output['price'] = $input['price'];

        $model = new CityDimension();
        $cityCondition = array();
        $cityCondition['city_name'] = $input['client_city'];
        $cityCondition['country_code'] = $input['client_country'];
        $output['dim_city_key'] = $model->link($cityCondition)['id'];
        $model = new DateDimension();
        $output['dim_reservation_key'] = $model->link(array('date' => $input['reservation_date']), false)['date'];
        $output['dim_purchase_key'] = $model->link(array('date' => $input['purchase_date']), false)['date'];

        return $output;
    }
}