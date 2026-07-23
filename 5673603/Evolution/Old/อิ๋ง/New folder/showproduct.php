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
    <?php
        include "condb.php";
        $sql = "SELECT productCode, productName, productLine,productScale,quantityInStock FROM `products`;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
    ?>
        <div class="container mt-3">
        <center><h2>Employee Data</h2></center>
    <table class="table table-hover">
        <thead class="table-success">
            <tr>
                <th>ProductCode</th>
                <th>ProductName</th>
                <th>ProductLine</th>
                <th>ProductScale</th>
                <th>QuantityInStock</th>
            </tr>
        </thead>
        <tbody>
        <?php
            while($row = $result->fetch_assoc()) {
        ?>
                <tr>
                    <td><?php echo $row["productCode"] ?></td>
                    <td><?php echo $row["productName"] ?></td>
                    <td><?php echo $row["productLine"] ?></td>
                    <td><?php echo $row["productScale"] ?></td>
                    <td><?php echo $row["quantityInStock"] ?></td>
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