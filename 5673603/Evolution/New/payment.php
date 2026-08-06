<?php
// 1. การเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username   = "std67103";
$password   = "pro67103";
$dbname     = "classicmodels"; 

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

$limit = 10; 

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_clean = mysqli_real_escape_string($conn, $search);

$where_clause = "";
if (!empty($search)) {
    $where_clause = " WHERE customerNumber LIKE '%$search_clean%' OR checkNumber LIKE '%$search_clean%' ";
}

$count_sql = "SELECT COUNT(*) as total FROM payments " . $where_clause;
$count_result = mysqli_query($conn, $count_sql);
$count_row = mysqli_fetch_assoc($count_result);
$total_rows = $count_row['total'];

$total_pages = ceil($total_rows / $limit);
if ($total_pages < 1) $total_pages = 1;
if ($page > $total_pages) $page = $total_pages;

$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM payments " . $where_clause . " ORDER BY paymentDate DESC LIMIT $offset, $limit";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments Data - Classic Models</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

    <!-- Header -->
    <header>
        <div class="header-left">
            <div class="logo">
                <img src="image/softeng.jpg" alt="Logo">
            </div>
            <h1>Classic models</h1>
        </div>

        <nav class="nav-menu">
            <a href="employee.php">Employee</a>
            <span class="divider">|</span>
            <a href="payment.php" class="active">Payments</a>
            <span class="divider">|</span>
            <a href="orders.php">Orders</a>
             <span class="divider">|</span>
            <a href="index.html">logout</a>
        </nav>
    </header>

    
    <main class="content-wrapper">
        <div class="inner-box">
            
            <h1 class="page-title">Payments Data</h1>

            
            <form action="payment.php" method="get" class="search-section">
                <label for="search">Search</label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    placeholder="Customer Number..." 
                    value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn-search">Search</button>
            </form>

            <!-- Table Section -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer Number</th>
                            <th>Check Number</th>
                            <th>Payment Date</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = $offset + 1;
                        if ($result && mysqli_num_rows($result) > 0): 
                            while ($row = mysqli_fetch_assoc($result)): 
                        ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo htmlspecialchars($row['customerNumber']); ?></td>
                                <td><?php echo htmlspecialchars($row['checkNumber']); ?></td>
                                <td><?php echo htmlspecialchars($row['paymentDate']); ?></td>
                                <td><?php echo number_format((float)$row['amount'], 2); ?></td>
                            </tr>
                        <?php 
                            endwhile; 
                        else: 
                        ?>
                            <tr>
                                <td colspan="5" class="no-data">ไม่พบข้อมูลที่ค้นหา</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Smart Pagination Bar -->
            <div class="pagination">
                <!-- ปุ่มย้อนกลับ << -->
                <?php if ($page > 1): ?>
                    <a href="payment.php?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" class="page-btn">&lt;&lt;</a>
                <?php else: ?>
                    <span class="page-btn disabled">&lt;&lt;</span>
                <?php endif; ?>

                <?php
                $range = 1;
                $start_page = max(1, $page - $range);
                $end_page = min($total_pages, $page + $range);

               
                if ($start_page > 1) {
                    echo '<a href="payment.php?page=1&search=' . urlencode($search) . '" class="page-btn">1</a>';
                    if ($start_page > 2) {
                        echo '<span class="page-btn dots">...</span>';
                    }
                }

                
                for ($p = $start_page; $p <= $end_page; $p++) {
                    $active = ($p == $page) ? 'active' : '';
                    echo '<a href="payment.php?page=' . $p . '&search=' . urlencode($search) . '" class="page-btn ' . $active . '">' . $p . '</a>';
                }

                // แสดงจุดไข่ปลา และหน้าสุดท้าย
                if ($end_page < $total_pages) {
                    if ($end_page < $total_pages - 1) {
                        echo '<span class="page-btn dots">...</span>';
                    }
                    echo '<a href="payment.php?page=' . $total_pages . '&search=' . urlencode($search) . '" class="page-btn">' . $total_pages . '</a>';
                }
                ?>

                <!-- ปุ่มถัดไป >> -->
                <?php if ($page < $total_pages): ?>
                    <a href="payment.php?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" class="page-btn">&gt;&gt;</a>
                <?php else: ?>
                    <span class="page-btn disabled">&gt;&gt;</span>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer>
        Copyright © 2026 Classic Models Database
    </footer>

</div>

</body>
</html>