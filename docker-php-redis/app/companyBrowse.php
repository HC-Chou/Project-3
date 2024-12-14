<?php
// Connect to Redis
$redis = new Redis();
$redis->connect('redis_container', 6379);

// Get all department keys
$departmentKeys = $redis->keys('department:*');

echo "<h1>Departments of Company</h1>";
echo "<table border='1'>";
echo "<tr><th>Department Number</th><th>Department Name</th></tr>";

// Check if there are any department records
if (!empty($departmentKeys)) {
    foreach ($departmentKeys as $key) {
        // Extract the department number from the key
        $dnumber = str_replace('department:', '', $key);
        // Get the department name
        $dname = $redis->hget($key, 'name');

        echo "<tr>";
        echo "<td>" . htmlspecialchars($dnumber) . "</td>";
        echo "<td><a href='deptView.php?dno=" . htmlspecialchars($dnumber) . "'>" . htmlspecialchars($dname) . "</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='2'>No departments available</td></tr>";
}

echo "</table>";

// Close the Redis connection
$redis->close();

// Link to go back to the index page
echo '<p><a href="index.php">Back to index page</a></p>';
?>