<?php
// Connect to Redis
$redis = new Redis();
$redis->connect('redis_container', 6379);

// Check if SSN is received
if (isset($_POST['ssn'])) {
    $ssn = $_POST['ssn'];
    $key = "employee:" . $ssn;

    // Check if the employee data exists in Redis
    if ($redis->exists($key)) {
        // Get the employee's first and last name from Redis
        $fname = $redis->hget($key, 'first_name');
        $lname = $redis->hget($key, 'last_name');

        echo "<h1>Employee Name</h1>";
        echo "<p>Social Security Number (SSN): " . htmlspecialchars($ssn) . "</p>";
        echo "<p>First Name: " . htmlspecialchars($fname) . "</p>";
        echo "<p>Last Name: " . htmlspecialchars($lname) . "</p>";
    } else {
        echo "<p>No employee found with the provided SSN.</p>";
    }
} else {
    echo "<p>No Social Security Number (SSN) received.</p>";
}

// Link to go back to the previous page
echo '<p><a href="employee_lookup.php">Back to previous page</a></p>';
// Link to go back to the index page
echo '<p><a href="index.php">Back to index page</a></p>';

// Close the Redis connection
$redis->close();
?>