<?php 
require_once 'db.php';
require_once 'employee.php';


    if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])){
        $id = filter_input(INPUT_GET, 'id' , FILTER_SANITIZE_NUMBER_INT);
        if($id > 0) {
            $sql = 'SELECT * FROM employee WHERE id = :id';
            $result = $connection->prepare($sql);
            $foundUser = $result->execute(array(':id' => $id));
            if($foundUser === true) {
                $user = $result->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Employee', array('name', 'age', 'address', 'salary', 'tax'));
                $user = array_shift($user);
            }
        }
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $name = htmlspecialchars($_POST['name']);
        $address = htmlspecialchars($_POST['address']);
        $age = filter_input(INPUT_POST, 'age' , FILTER_SANITIZE_NUMBER_INT);
        $salary = filter_input(INPUT_POST, 'salary' , FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $tax = filter_input(INPUT_POST, 'tax' , FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        
        $parms = array(
            ':name' => $name,
            ':age' => $age,
            ':address' => $address,
            ':salary' => $salary,
            ':tax' => $tax,
        );

        if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
            $id = filter_input(INPUT_GET, 'id' , FILTER_SANITIZE_NUMBER_INT);
            $sql = 'UPDATE employee SET name = :name,  address = :address, salary = :salary, tax = :tax, age = :age WHERE id = :id';
            $parms[':id'] = $id;
        } else{
            $sql = 'INSERT INTO employee SET name = :name,  address = :address, salary = :salary, tax = :tax, age = :age';
        }
       
        $stmt = $connection->prepare($sql);
        
        if ($stmt->execute($parms) === true) {
            $messege = 'Employee, ' . htmlspecialchars($name) . ' has been saved to the database.';
        } else {
            $error = true;
            $messege = 'There was a problem saving the employee ' . htmlspecialchars($name);
        }
    
     }

     $sql = 'SELECT * FROM employee';
     $stmt = $connection->query($sql);
     $result = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Employee' , array('name', 'age', 'address', 'tax', 'salary'));
     $result = (is_array($result) && !empty($result)) ? $result : false;

?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PDO</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
    <div class="empForm">
    <form class="appForm" method="POST" enctype="application/x-www-form-urlencoded">
        <fieldset>
            <legend>Employee information</legend>
            <?php if(isset($messege)) { ?>
        <p class="messege"><?= isset($error) ? 'error' : '' ?><?= isset($messege) ? $messege : '' ?></p>
        <?php } ?>
        <table>
            <tr>
                <td>
                    <label for="name">Employee name</label>

                </td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="name" id="name" placeholder="Write employee name" maxlength="50" value="<?= isset($user) ? $user->name : '' ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="name">Employee Age</label>

                </td>
            </tr>
            <tr>
                <td>
                    <input type="number" name="age" id="age" min="20" max="70" value="<?= isset($user) ? $user->age : '' ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="adress">Employee address</label>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="address" id="address" placeholder="Write employee address" maxlength="100" value="<?= isset($user) ? $user->address : ''?>">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="salary">Employee salary</label>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="number" step="0.01" name="salary" id="salary" min="1500" max="90000" maxlength="50" value="<?= isset($user) ? $user->salary : '' ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="tax">Employee tax</label>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="number" step="0.01" name="tax" id="tax" min="1" max="5" maxlength="50" value="<?= isset($user) ? $user->tax : '' ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" name="submit" value="save">
                </td>
            </tr>
        </table>
        </fieldset>
    </form>
    </div>

    <div class="employees">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Address</th>
                    <th>Salary</th>
                    <th>Tax (%)</th>
                    <th>Control</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if(false !== $result){
                        foreach ($result as $employee) {
                ?>
                <tr>
                    <td><?= htmlspecialchars($employee->name) ?></td>
                    <td><?= htmlspecialchars($employee->age) ?></td>
                    <td><?= htmlspecialchars($employee->address) ?></td>
                    <td><?= htmlspecialchars($employee->calculateSalary()) ?>SR</td>
                    <td><?= htmlspecialchars($employee->tax) ?></td>
                    <td>
                        <a href="/practice/?action=edit&id=<?= $employee->id?>">edit</a>
                        <a href="/practice/?action=edit&delete=<?= $employee->id?>">delete</a>
                    </td>
                </tr>
                <?php 
                    }
                 } else {
                ?>
                <td colspan="5"><p>Sorry there is no employee to list</p></td>
                <?php 
                    }
                ?>
                
                
            </tbody>
        </table>
    </div>
    </div>

</body>
