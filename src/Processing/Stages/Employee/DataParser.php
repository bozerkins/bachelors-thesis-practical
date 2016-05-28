<?php

namespace Application\Processing\Stages\Employee;

use Application\Processing\DataParserInterface;

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
        $output['birth_date'] = $input['birth_date'];
        $output['first_name'] = $input['first_name'];
        $output['last_name'] = $input['last_name'];
        $output['gender'] = $input['gender'];
        $output['hire_date'] = $input['hire_date'];
        $output['dept_no'] = $input['department']['dept_no'];
        $output['dept_name'] = $input['department_title']['dept_name'];
        $output['dept_from_date'] = $input['department']['from_date'];
        $output['dept_to_date'] = $input['department']['to_date'];
        $output['mng_dept_no'] = $input['management']['dept_no'];
        return $output;
    }
}