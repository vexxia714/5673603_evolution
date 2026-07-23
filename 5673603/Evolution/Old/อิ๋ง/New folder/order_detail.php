<?php
// --- การเชื่อมต่อ MySQL ---
include "condb.php";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// --- รับค่า orderNumber จาก URL เช่น order_detail.php?orderNumber=10100 ---
$orderNumber = isset($_GET['orderNumber']) ? intval($_GET['orderNumber']) : 10100;

// --- ดึงข้อมูลออเดอร์ + ลูกค้า ---
$sql_order = "
    SELECT o.orderNumber, o.orderDate, o.requiredDate, o.shippedDate,
           c.customerNumber, c.customerName
    FROM orders o
    JOIN customers c ON o.customerNumber = c.customerNumber
    WHERE o.orderNumber = $orderNumber
";
$result_order = $conn->query($sql_order);
$order = $result_order->fetch_assoc();

// --- ดึงข้อมูลสินค้าในออเดอร์ ---
$sql_products = "
    SELECT od.productCode, p.productName, od.quantityOrdered, od.priceEach,
           (od.quantityOrdered * od.priceEach) AS totalPrice
    FROM orderdetails od
    JOIN products p ON od.productCode = p.productCode
    WHERE od.orderNumber = $orderNumber
";
$result_products = $conn->query($sql_products);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดออเดอร์</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- แบนเนอร์ -->
    <header class="banner">
        <h1>รายละเอียดออเดอร์</h1>
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

            <!-- ข้อมูลออเดอร์ -->
            <?php if ($order): ?>
            <div class="order-info">
                <div>
                    <label>หมายเลขออเดอร์</label>
                    <span class="info-box"><?= $order['orderNumber'] ?></span>
                </div>
                <div>
                    <label>รหัสลูกค้า</label>
                    <span class="info-box"><?= $order['customerNumber'] ?></span>
                </div>
                <div>
                    <label>ชื่อลูกค้า</label>
                    <span class="info-box"><?= $order['customerName'] ?></span>
                </div>
            </div>
            <?php else: ?>
                <p>ไม่พบข้อมูลออเดอร์</p>
            <?php endif; ?>

            <!-- ตารางสินค้า -->
            <table class="order-table">
                <thead>
                    <tr>
                        <th>รหัสสินค้า</th>
                        <th>ชื่อสินค้า</th>
                        <th>จำนวนที่สั่งซื้อ</th>
                        <th>ราคาต่อชิ้น</th>
                        <th>ราคารวม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_qty = 0;
                    $total_price = 0;

                    if ($result_products->num_rows > 0) {
                        while ($row = $result_products->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row['productCode']}</td>";
                            echo "<td>{$row['productName']}</td>";
                            echo "<td>{$row['quantityOrdered']}</td>";
                            echo "<td>{$row['priceEach']}</td>";
                            echo "<td>{$row['totalPrice']}</td>";
                            echo "</tr>";

                            $total_qty += $row['quantityOrdered'];
                            $total_price += $row['totalPrice'];
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">ยอดรวม</td>
                        <td><?= $total_qty ?></td>
                        <td>-</td>
                        <td><?= $total_price ?></td>
                    </tr>
                </tfoot>
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
