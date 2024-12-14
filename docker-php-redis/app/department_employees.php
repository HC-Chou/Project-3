<?php
// Connect to Redis
$redis = new Redis();
$redis->connect('redis_container', 6379);

// Check if department number is received
if (isset($_POST['dno'])) {
    $dno = $_POST['dno'];

    echo "<h1>Employees in Department $dno</h1>";

    // Get all employee keys
    $employeeKeys = $redis->keys('employee:*');

    echo "<table border='1'>";
    echo "<tr><th>Last Name</th><th>Salary</th></tr>";

    $hasResults = false;

    // Iterate through all employees and filter those in the selected department
    foreach ($employeeKeys as $key) {
        $employeeDnum = $redis->hget($key, 'department_number');
        if ($employeeDnum === $dno) {
            $lname = $redis->hget($key, 'last_name');
            $salary = $redis->hget($key, 'salary');
            $hasResults = true;
            echo "<tr>";
            echo "<td>" . htmlspecialchars($lname) . "</td>";
            echo "<td>" . htmlspecialchars(number_format($salary, 2)) . "</td>";
            echo "</tr>";
        }
    }

    if (!$hasResults) {
        echo "<tr><td colspan='2'>No employees found.</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>Please select a department.</p>";
}

// Display department selection dropdown
echo '<form method="POST" action="">';
echo '<label for="dno">Department number: </label>';
echo '<select name="dno" id="dno">';

// Get all department numbers and create options
$departmentKeys = $redis->keys('department:*');

if (!empty($departmentKeys)) {
    foreach ($departmentKeys as $key) {
        $dnumber = str_replace('department:', '', $key);
        echo '<option value="' . htmlspecialchars($dnumber) . '">' . htmlspecialchars($dnumber) . '</option>';
    }
} else {
    echo '<option value="">No departments available</option>';
}

echo '</select>';
echo '<input type="submit" value="Show employees">';
echo '</form>';

// Link to go back to the index page
echo '<p><a href="index.php">Back to index page</a></p>';

// Close the Redis connection
$redis->close();
?>