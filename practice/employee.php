<?php 

class Employee {

    public $id;
    public $name;
    public $address;
    public $age;
    public $salary;
    public $tax;


    public function __construct($name, $age, $address, $salary, $tax){

        $this->name = $name;
        $this->age = $age;
        $this->address = $address;
        $this->salary = $salary;
        $this->tax = $tax;
    }

    public function calculateSalary() {
        return $this->salary - ($this->salary * $this->tax / 100);
    }
    
}
