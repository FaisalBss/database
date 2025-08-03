<?php 

class Employee {

    public $id;
    public $name;
    public $address;
    public $age;
    public $tax;
    public $salary;


    public function calculateSalary() {
        return $this->salary - ($this->salary * $this->tax / 100);
    }
    // public function __construct($name, $age, $tax, $salary){

    //     $this->name = $name;
    //     $this->age = $age;
    //     $this->salary = $salary;
    //     $this->tax = $tax;
    // }

}