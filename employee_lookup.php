<?php
// Connect to Redis
$redis = new Redis();
$redis->connect('redis_container', 6379);

// Get all employee SSNs from Redis
$keys = $redis->keys('employee:*');

// Extract SSNs from the Redis keys
$ssns = array_map(function($key) {
return str_replace('employee:', '', $key);
}, $keys);
?>

<!DOCTYPE html>
<html>
<head>
<title>Find Employee</title>
</head>
<body>
<h1>Find Employee Name</h1>
<form action="employee_result.php" method="POST">
<label for="ssn">Select Social Security Number (SSN):</label>
<select name="ssn" id="ssn" required>
<option value="">Please Select</option>
<?php
// Add each SSN to the dropdown menu
foreach ($ssns as $ssn) {
echo "<option value='" . $ssn . "'>" . $ssn . "</option>";
}
?>
</select>
<br><br>
<input type="submit" value="Find Employee Name">
</form>

<?php
// Link to go back to the previous page
echo '<p><a href="table_lookup.php">Back to previous page</a></p>';
// Link to go back to the index page
echo '<p><a href="index.php">Back to index page</a></p>';
?>
</body>
</html>

<?php
// Close the Redis connection
$redis->close();
?>
