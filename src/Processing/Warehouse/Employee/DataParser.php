<?php

namespace Application\Processing\Warehouse\Employee;

use Application\Processing\DataParserInterface;

use Application\Warehouse\Dimension\AmountDimension;
use Application\Warehouse\Dimension\CityDimension;
use Application\Warehouse\Dimension\DateDimension;
use Application\Warehouse\Dimension\DepartmentDimension;
use Application\Warehouse\Dimension\GenderDimension;

class DataParser implements DataParserInterface
{

    /**
     * @param array $input
     * @return array $output
     */
    public function parse(array $input)
    {
        $output['emp_no'] = $input['emp_no'];
        $model = new GenderDimension();
        $output['dim_gender_key'] = $model->get(array('gender_code' => $input['gender']))['gender_code'];
        $model = new DateDimension();
        $output['dim_birth_date_key'] = $model->get(array('date' => $input['birth_date']))['date'];
        $output['dim_hire_date_key'] = $model->get(array('date' => $input['hire_date']))['date'];
        $model = new DepartmentDimension();
        $departmentCondition = array();
        $departmentCondition['dept_no'] = $input['dept_no'];
        $departmentCondition['dept_name'] = $input['dept_name'];
        $output['dim_dept_key'] = $model->link($departmentCondition)['id'];
        $model = new AmountDimension();
        $deptWorkedAge = (int) floor((strtotime($input['hire_date']) - strtotime($input['birth_date'])) / (60*60*24*360));
        $output['dim_dept_worked_key'] = $model->get(array('amount' => $deptWorkedAge))['amount'];
        $output['is_active'] = $input['dept_to_date'] === '9999-01-01' ? 1 : 0;
        $output['is_manager'] = $input['mng_dept_no'] ? 1 : 0;

        return $output;
    }
}