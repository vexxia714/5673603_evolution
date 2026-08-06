<?php
include "db.php";

$limit = 10;
$search = trim($_GET['search'] ?? '');
$search_clean = mysqli_real_escape_string($conn, $search);
$where = $search !== '' ? "WHERE customerNumber LIKE '%$search_clean%' OR checkNumber LIKE '%$search_clean%'" : "";


$total_rows = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM payments $where"))['t'];
$total_pages = max(1, (int)ceil($total_rows / $limit));


$page = max(1, min((int)($_GET['page'] ?? 1), $total_pages));
$offset = ($page - 1) * $limit;


$result = mysqli_query($conn, "SELECT * FROM payments $where ORDER BY paymentDate DESC LIMIT $offset, $limit");


function pUrl($p, $s) { return "payment.php?page=$p&search=" . urlencode($s); }
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

    <header class="header">
        <div class="logo-section">
            <div class="logo"><img src="image/softeng.jpg" alt="Logo"></div>
            <h1>Classic models</h1>
        </div>
        <nav>
            <a href="employees.php">Employee</a> <span>|</span>
            <a href="payment.php" class="active">Payments</a> <span>|</span>
            <a href="orders.php">Orders</a> <span>|</span>
            <a href="index.html">logout</a>
        </nav>
    </header>

    <div class="container">
        <h2>Payments Data</h2>

        <form action="payment.php" method="get" class="filter-form">
            <label for="search">Search</label>
            <input type="text" id="search" name="search" placeholder="Customer Number..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
        </form>

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
                <?php if ($result && mysqli_num_rows($result) > 0): 
                    $i = $offset + 1;
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['customerNumber']) ?></td>
                            <td><?= htmlspecialchars($row['checkNumber']) ?></td>
                            <td><?= htmlspecialchars($row['paymentDate']) ?></td>
                            <td><?= number_format((float)$row['amount'], 2) ?></td>
                        </tr>
                    <?php endwhile; 
                else: ?>
                    <tr><td colspan="5">ไม่พบข้อมูลที่ค้นหา</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="<?= pUrl($page - 1, $search) ?>">&lt;&lt;</a>
            <?php endif; 

            $start = max(1, $page - 1);
            $end = min($total_pages, $page + 1);

            if ($start > 1) {
                echo '<a href="' . pUrl(1, $search) . '">1</a>';
                if ($start > 2) echo '<a>...</a>';
            }

            for ($p = $start; $p <= $end; $p++) {
                $cls = ($p == $page) ? 'class="active"' : '';
                echo "<a href='" . pUrl($p, $search) . "' $cls>$p</a>";
            }

            if ($end < $total_pages) {
                if ($end < $total_pages - 1) echo '<a>...</a>';
                echo '<a href="' . pUrl($total_pages, $search) . '">' . $total_pages . '</a>';
            }

            if ($page < $total_pages): ?>
                <a href="<?= pUrl($page + 1, $search) ?>">&gt;&gt;</a>
            <?php endif; ?>
        </div>
    </div>

    <footer class="footer">
        Copyright © 2026 Classic Models Database
    </footer>

</body>
</html>