<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "swcentric68db" ;

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname );

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>

<!DOCTYPE html>
<html>
<body>
 
<?php
   include "cond.php"
   $empid = '1002';
$sql = "Call search_emp(".$empid.");";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "employee: " . $row["employeeNumbar"]. " - Name: " . $row["firstName"]. " " . $row["lastName"]. "<br>";
  }
} else {
  echo "0 results";
}
$conn->close();
?>

</body>
</html>