<?php
include "db.php";

// รับค่าจาก search และ filter
$search = isset($_GET['search']) ? $_GET['search'] : "";
$department = isset($_GET['department']) ? $_GET['department'] : "";

// pagination
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// query base
$sql = "SELECT employeeNumber, firstName, lastName, jobTitle, email FROM employees WHERE 1";

// search by name
if (!empty($search)) {
    $sql .= " AND (firstName LIKE '%$search%' OR lastName LIKE '%$search%')";
}

// filter by jobTitle
if (!empty($department)) {
    $sql .= " AND jobTitle = '".$conn->real_escape_string($department)."'";
}

// total count
$countResult = $conn->query($sql);
$totalRows = $countResult->num_rows;
$totalPages = ceil($totalRows / $limit);

// limit & offset
$sql .= " ORDER BY employeeNumber ASC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Employees Management</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="banner">Employee Management System</div>
    <div class="container">
        <h2>Employees Management</h2>

        <!-- search & filter form -->
        <form method="get" class="filter-form">
            <input type="text" name="search" placeholder="Search by name" value="<?= htmlspecialchars($search) ?>">
            <select name="department">
                <option value="">Filter by Department</option>
                <?php
                $deptResult = $conn->query("SELECT DISTINCT jobTitle FROM employees");
                while ($row = $deptResult->fetch_assoc()) {
                    $selected = ($department == $row['jobTitle']) ? "selected" : "";
                    echo "<option value='{$row['jobTitle']}' $selected>{$row['jobTitle']}</option>";
                }
                ?>
            </select>
            <button type="submit">Go</button>
        </form>

        <!-- table -->
        <table>
            <tr>
                <th>#</th>
                <th>Emp no</th>
                <th>Name</th>
                <th>Job Title</th>
                <th>Email</th>
            </tr>
            <?php 
            if ($result->num_rows > 0) {
                $i = $offset + 1;
                while ($row = $result->fetch_assoc()) { 
            ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= $row['employeeNumber'] ?></td>
                <td><?= $row['firstName']." ".$row['lastName'] ?></td>
                <td><?= $row['jobTitle'] ?></td>
                <td><?= $row['email'] ?></td>
            </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='5'>No records found</td></tr>";
            }
            ?>
        </table>

        <!-- pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page-1 ?>&search=<?= $search ?>&department=<?= $department ?>">Prev</a>
            <?php endif; ?>

            <?php for ($p=1; $p<=$totalPages; $p++): ?>
                <a href="?page=<?= $p ?>&search=<?= $search ?>&department=<?= $department ?>" class="<?= ($p==$page)?'active':'' ?>"><?= $p ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page+1 ?>&search=<?= $search ?>&department=<?= $department ?>">Next</a>
            <?php endif; ?>
        </div>

        <!-- add new employee -->
        <div class="add-btn">
            <a href="add_employee.php">Add New Employee</a>
        </div>
    </div>
</body>
</html>
