<?php/////////////////////////////////////////////////////////////////////////////////// Licensed Source Code - Property of f-project.net//// © Copyright f-project.net 2014. All Rights Reserved./////////////////////////////////////////////////////////////////////////////////Yii::import('application.services.amf.*');Yii::import('application.services.vo.*');/** * This service is exposed to AMF clients to handle AppContext information * @author Bui Sy Nguyen <nguyenbs@gmail.com> */class EmployeeService{    /**     * Get an employee by ID.     * @param mixed $id the ID of employee to find     * @return FEmployee the matched employee     */    public function getEmployee($id)	{        return FEmployee::staticInstance()->findByPk($id);	}    /**     *     * Get an employee by name.     * @param string $name the name to find.     * This will be used as condition value for WHERE clause.     * @return FEmployee[] the matched employees     */    public function findByName($name)    {        $q = new CDbCriteria;        $q->addSearchCondition('name', $name);        return FEmployee::staticInstance()->findAll($q);    }    /**     * Get all employees.     * @return FEmployee[] all employees     */    public function getAllEmployees()    {        return FEmployee::staticInstance()->findAll();	}    /**     * Save an employee     * @param FEmployee $employee the employee to save     * @return FEmployee the saved employee     */    public function saveEmployee($employee)    {        $employee->save();        return $employee;    }    /**     * Delete an employee by ID     * @param mixed $id ID of the deleting model     * @return int the number of deleted record(s)     */    public function deleteEmployee($id)    {        return FEmployee::staticInstance()->deleteByPk($id);    }}