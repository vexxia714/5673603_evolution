<?php
include "db.php";

// รับค่าจาก search และ filter
$search = isset($_GET['search']) ? $_GET['search'] : "";
$department = isset($_GET['department']) ? $_GET['department'] : "";

// pagination
$limit = 10;
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <div class="logo-section">
            <div class="logo">
                <img src="logo.png" alt="Logo">
            </div>
            <h1>Classic models</h1>
        </div>
        <nav>
            <a href="employees.php">Employee</a>
            <span>|</span>
            <a href="#">Payments</a>
            <span>|</span>
            <a href="#">Orders</a>
        </nav>
    </header>
    <div class="container">
        <h2>Employees Data</h2>
        <form method="get" class="filter-form">
            <label>Search</label>
            <input type="text" name="search" placeholder="Search by name" value="<?= htmlspecialchars($search) ?>">
            <select name="department">
                <option value="">All Job</option>
                <?php
            $deptResult = $conn->query("SELECT DISTINCT jobTitle FROM employees");
            while($row = $deptResult->fetch_assoc()){
                $selected = ($department==$row['jobTitle']) ? "selected":"";
                echo "<option value='{$row['jobTitle']}' $selected>{$row['jobTitle']}</option>";
            }
            ?>
            </select>
            <button type="submit">Search</button>
        </form>
        <table>
            <tr>
                <th>#</th>
                <th>Emp No</th>
                <th>Name</th>
                <th>Job Title</th>
                <th>Email</th>
            </tr>
            <?php
        if($result->num_rows>0){
            $i=$offset+1;
            while($row=$result->fetch_assoc()){
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
        }else{
            echo "<tr><td colspan='5'>No records found</td></tr>";
        }
        ?>
        </table>
        <!-- Pagination เดิม -->
        <div class="pagination">
            <?php if($page>1){ ?>
            <a href="?page=<?= $page-1 ?>&search=<?= $search ?>&department=<?= $department ?>">Prev</a>
            <?php } ?>
            <?php
        for($p=1;$p<=$totalPages;$p++){
        ?>
            <a href="?page=<?= $p ?>&search=<?= $search ?>&department=<?= $department ?>"
                class="<?= ($p==$page)?'active':'' ?>">
                <?= $p ?>
            </a>
            <?php } ?>
            <?php if($page<$totalPages){ ?>
            <a href="?page=<?= $page+1 ?>&search=<?= $search ?>&department=<?= $department ?>">Next</a>
            <?php } ?>
        </div>
    </div>
    <footer class="footer">
        Copyright © 2026 Classic Models Database
    </footer>
</body>
</html>
