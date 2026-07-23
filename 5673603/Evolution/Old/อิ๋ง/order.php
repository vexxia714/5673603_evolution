<?php
// --- การเชื่อมต่อ MySQL ---
$servername = "localhost";   // ชื่อเซิร์ฟเวอร์
$username   = "swcentric68";        // ชื่อผู้ใช้ MySQL
$password   = "Scentric#000";            // รหัสผ่าน MySQL
$dbname     = "swcentric68db"; // ชื่อฐานข้อมูล

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// --- SQL ดึงข้อมูลออเดอร์ + ลูกค้า ---
$sql = "
    SELECT o.orderNumber, o.orderDate, o.status, c.customerName
    FROM orders o
    JOIN customers c ON o.customerNumber = c.customerNumber
    ORDER BY o.orderDate DESC
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการออเดอร์</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- แบนเนอร์ -->
    <header class="banner">
        <h1>ออเดอร์</h1>
    </header>

    <div class="container">
        <!-- เมนูด้านซ้าย -->
        <aside class="sidebar" id="sidebar">
            <div class="menu-toggle" onclick="toggleMenu()">&#9776;</div>
            <h2>เมนู</h2>
            <ul>
                <li><a href="order.php">ออเดอร์ทั้งหมด</a></li>
                <li><a href="order_detail.php">รายละเอียดออเดอร์</a></li>
            </ul>
        </aside>

        <!-- เนื้อหา -->
        <main class="content">
            <div class="search-bar">
                <form method="get">
                    <label for="search">ค้นหาออเดอร์</label>
                    <input type="number" id="search" name="orderNumber" placeholder="กรอกเลขออเดอร์">
                    <button type="submit">🔍</button>
                </form>
            </div>
            <h2>รายการออเดอร์</h2>

            <table class="order-table">
                <thead>
                    <tr>
                        <th>หมายเลขออเดอร์</th>
                        <th>วันที่สั่งซื้อ</th>
                        <th>วันที่กำหนดส่ง</th>
                        <th>วันที่จัดส่ง</th>
                        <th>สถานะ</th>
                        <th>ชื่อลูกค้า</th>
                        <th>คอมเมนต์</th>
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row['orderNumber']}</td>";
                            echo "<td>{$row['orderDate']}</td>";
                            echo "<td>{$row['requiredDate']}</td>";
                            echo "<td>{$row['shippedDate']}</td>";
                            echo "<td>{$row['status']}</td>";
                            echo "<td>{$row['customerName']}</td>";
                            echo "<td>{$row['comments']}</td>";
                            echo "<td><a href='order_detail.php?orderNumber={$row['orderNumber']}'>ดูรายละเอียด</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>ไม่พบข้อมูล</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>

    <!-- JavaScript -->
    <script>
        function toggleMenu() {
            document.getElementById("sidebar").classList.toggle("collapsed");
        }
    </script>
</body>
</html>
