<?php
// Connect to Redis
$redis = new Redis();
$redis->connect('redis_container', 6379);

// Check if department number is received
if (isset($_GET['dno'])) {
    $dno = $_GET['dno'];
    $deptKey = "department:$dno";

    // Check if the department exists in Redis
    if ($redis->exists($deptKey)) {
        // Get department information
        $dname = $redis->hget($deptKey, 'name');
        $mgrssn = $redis->hget($deptKey, 'manager_ssn');
        $mgrstartdate = $redis->hget($deptKey, 'established_date');

        echo "<h1>Department: " . htmlspecialchars($dname) . "</h1>";
        echo "<p>Manager SSN: " . htmlspecialchars($mgrssn) . "</p>";
        echo "<p>Manager Start Date: " . htmlspecialchars($mgrstartdate) . "</p>";

        // Get manager's name
        $mgrKey = "employee:$mgrssn";
        if ($redis->exists($mgrKey)) {
            $mgrFName = $redis->hget($mgrKey, 'first_name');
            $mgrLName = $redis->hget($mgrKey, 'last_name');
            echo "<p>Manager: <a href='empView.php?ssn=" . htmlspecialchars($mgrssn) . "'>" . htmlspecialchars($mgrFName) . " " . htmlspecialchars($mgrLName) . "</a></p>";
        }

        // Display department locations
        echo "<h2>Department Locations:</h2>";
        $locationKeys = $redis->keys("department_location:$dno:*");

        if (!empty($locationKeys)) {
            foreach ($locationKeys as $locKey) {
                $location = $redis->get($locKey);
                echo "<p>" . htmlspecialchars($location) . "</p>";
            }
        } else {
            echo "<p>No locations found for this department.</p>";
        }

        // Display employees in the department
        echo "<h2>Employees:</h2>";
        $employeeKeys = $redis->keys("employee:*");

        echo "<table border='1'>";
        echo "<tr><th>Employee SSN</th><th>Last Name</th><th>First Name</th></tr>";

        $hasEmployees = false;
        foreach ($employeeKeys as $empKey) {
            $employeeDno = $redis->hget($empKey, 'department_number');
            if ($employeeDno === $dno) {
                $ssn = str_replace('employee:', '', $empKey);
                $fname = $redis->hget($empKey, 'first_name');
                $lname = $redis->hget($empKey, 'last_name');

                echo "<tr>";
                echo "<td><a href='empView.php?ssn=" . htmlspecialchars($ssn) . "'>" . htmlspecialchars($ssn) . "</a></td>";
                echo "<td>" . htmlspecialchars($lname) . "</td>";
                echo "<td>" . htmlspecialchars($fname) . "</td>";
                echo "</tr>";

                $hasEmployees = true;
            }
        }

        if (!$hasEmployees) {
            echo "<tr><td colspan='3'>No employees found in this department.</td></tr>";
        }
        echo "</table>";

        // Display projects in the department
        echo "<h2>Projects:</h2>";
        $projectKeys = $redis->keys("project:*");

        echo "<table border='1'>";
        echo "<tr><th>Project Number</th><th>Project Name</th><th>Location</th></tr>";

        $hasProjects = false;
        foreach ($projectKeys as $projKey) {
            $projectDno = $redis->hget($projKey, 'department_number');
            if ($projectDno === $dno) {
                $pnumber = str_replace('project:', '', $projKey);
                $pname = $redis->hget($projKey, 'project_name');
                $plocation = $redis->hget($projKey, 'location');

                echo "<tr>";
                echo "<td><a href='projectView.php?pnumber=" . htmlspecialchars($pnumber) . "'>" . htmlspecialchars($pnumber) . "</a></td>";
                echo "<td>" . htmlspecialchars($pname) . "</td>";
                echo "<td>" . htmlspecialchars($plocation) . "</td>";
                echo "</tr>";

                $hasProjects = true;
            }
        }

        if (!$hasProjects) {
            echo "<tr><td colspan='3'>No projects found in this department.</td></tr>";
        }
        echo "</table>";

    } else {
        echo "<p>No department found with the provided department number.</p>";
    }

} else {
    echo "<p>Department number not received.</p>";
}

// Add links to go back to the previous page and the index page
echo '<p><a href="javascript:history.back()">Back to previous page</a></p>';
echo '<p><a href="index.php">Back to index page</a></p>';

// Close the Redis connection
$redis->close();
?>