<?php
$servername = "localhost";
$username = "std67103";    
$password = "pro67103";        
$dbname = "classicmodels";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM payments 
            WHERE customerNumber LIKE '%$search%' 
            OR checkNumber LIKE '%$search%' 
            ORDER BY paymentDate DESC";
} else {
    $sql = "SELECT * FROM payments ORDER BY paymentDate DESC";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Payments</title>
      <link href="https://fonts.googleapis.com/css2?family=Charm:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="database.css"> 
</head>
<body>

<div class="container">
    <p class="R1"> software Engineering.LPRU</p>
    <h2 class="left">Classic Model <br>
data base</h2>

    <h2 class="title">📑 Payments</h2>


    <form method="get" class="search-box">
        <input type="text" name="search" placeholder="ค้นหาด้วย CustomerNumber หรือ CheckNumber" 
               value="<?= $search ?>">
        <button type="submit">ค้นหา</button>
        <a href="add_payment.php" class="btn-add">➕ กดเพิ่มข้อมูล</a>
    </form>


    <table>
        <thead>
            <tr>
                <th>CustomerNumber</th>
                <th>CheckNumber</th>
                <th>PaymentDate</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row["customerNumber"] ?></td>
                    <td><?= $row["checkNumber"] ?></td>
                    <td><?= $row["paymentDate"] ?></td>
                    <td><?= number_format($row["amount"], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">❌ ไม่พบข้อมูล</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php $conn->close(); ?>
