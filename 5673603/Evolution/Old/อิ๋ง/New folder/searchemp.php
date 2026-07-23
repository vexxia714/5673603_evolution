<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>show view</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
    <div class="container mt-3">
        <h2>Employee Data</h2>
        <div class="row">
            <form action="searchemp.php" method="POST">
                <input class="form-control me-2" type="text" placeholder="EmployeeNumber" name="empid">
                <button class="btn btn-primary" type="submit">Search</button>
            </form>
        </div>

        <?php
            include "condb.php";
            if (isset($_REQUEST['empid'])) {
                $empid = $_REQUEST['empid'];
                $sql = "CALL search_emp(".$empid.");";
            }
            else{
                $sql = "SELECT * FROM `view_employee`";
            }
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
        ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>EmployeeNumber</th>
                            <th>EmployeeName</th>
                            <th>JobTitle</th>
                            <th>Tel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while($row = $result->fetch_assoc()) {
                        ?>
                                <tr>
                                    <td><?php echo $row["employeeNumber"] ?></td>
                                    <td><?php echo $row["firstName"]." " .$row["lastName"] ?></td>
                                    <td><?php echo $row["jobTitle"] ?></td>
                                    <td><?php echo $row["phone"] ?></td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "0 results";
                        }
                        $conn->close();
                    ?>
                    </tbody>
                </table>
    </div>
</body>
</html>