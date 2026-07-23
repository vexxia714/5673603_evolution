<?php
// --- การเชื่อมต่อ MySQL ---
 include "condb.php";

// --- SQL ดึงข้อมูลออเดอร์ + ลูกค้า ---
$sql = "SELECT * FROM `view_order`";

// --- รับค่า orderNumber จาก URL เช่น order_detail.php?orderNumber=10100 ---
$orderNumber = isset($_GET['orderNumber']) ? intval($_GET['orderNumber']) : 10100;

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
            <!-- ค้นหา -->
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
