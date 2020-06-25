<?php

namespace components\base;

use components\base\View;
use components\base\Db;

class Model 
{


    /**
     * The method must be redefined. 
     * Returns the name of a table in the database. Returns the class name by default.
     * @return string
     */
    public function getTableName() {
        return get_class($this);
    }

    /**
     * The method must be redefined. Returns an array with rules for validation.
     * @return array
     */
    public function rules() {
        return [];
    }

    public function addError($message) {
            View::$Errors[] = $message;
    }


    /**
     * Validation of data by rules from rules()
     * @return bool
     */
    public function validate() {
        $rules =  $this->rules();
        foreach($rules as $attribute => $rule) {
            $min = $rule['min'];
            $max = $rule['max'];
            $length = $rule['length'];
            $pattern = $rule['pattern'];
            $message = $rule['message'];
            $unique = $rule['unique'];
            if (!empty($length)) {
                if (strlen($this->$attribute) != $length) {
                    $this->addError($message);
                }
                continue;
            }
            if (!empty($max)) {
                if (!(strlen($this->$attribute) <= $max)) {
                    $this->addError($message);
                }
            }
            if (!empty($min)) {
                if (!(strlen($this->$attribute) >= $min)) {
                    $this->addError($message);
                }
            }
            if (!empty($pattern)) {
                if (!preg_match("/$pattern/", $this->$attribute)) {
                    $this->addError($message);
                }
            }
            if ($unique) {
                $query = "{$attribute} = :{$attribute}";
                $values = [":{$attribute}" => $this->$attribute];
                if ($this->findOne($query, $values)) {
                    $this->addError($message);
                }
            }
        }

        return empty(View::$Errors);
    }


    /**
     * Returns an array with query results
     * @param string $query Query string. For example: 'id = :id'
     * @param array $values Array of values. For example: [':id' => 1]
     * @return array The first record found from the database
     */
    public function findOne(string $query, array $values, bool $asArray = false) {
        
        if (empty($query) or empty($values) or !is_array($values)) {
            return false;
        }

        $R = Db::getConnection();
        $result = $R::findOne($this->getTableName(), $query, $values);
        
        if ($asArray) {
            return $result;
        }

        return $this->createObject(get_class($this), $result);
        
    }


    /**
     * Returns all entries found in the database
     * @param string $query Query string. For example: 'id = :id'
     * @param array $values Array of values. For example: [':id' => 1]
     * @return array Array of all records found by query
     */
    public function findAll(string $query, array $values)
    {
        
        if (empty($query) or empty($values) or !is_array($values)) {
            return false;
        }

        $R = Db::getConnection();
        var_dump($values);
        $result = $R::find($this->getTableName(), $query, $values);

        return $result;
        
    }


    /**
     * Saves object parameters to the database. If the id field is not empty it updates the data
     * @return bool Returns "true" if everything went smoothly. Otherwise "false"
     */
    public function save()
    {

        $R = Db::getConnection();

        $className = get_class($this);

        $params = get_class_vars($className);
        if ($this->id != null) {
            $record = $R::load($this->getTableName(), $this->id);
        } else {
            $record = $R::dispense($this->getTableName());
        }

        foreach ($params as $key => $value) {
            
            if ($this->$key == null) {
                continue;
            }
            $record->$key = $this->$key;

        }

        return $R::store($record);
    }

    /**
     * Creates a new object
     * @param string $className Class name
     * @param array $params Object parameter
     */
    protected function createObject($className, $params) 
    {

        if (!is_object($params) || !class_exists($className)) {
            return false;
        }

        $object = new $className();

        foreach($params as $name => $value) {
            $object->$name = $value;
        }

        return $object;

    }

}