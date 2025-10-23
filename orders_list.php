<!doctype html>
<html lang="th">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Northwind App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </head>

  <style>
  /* 💠 พื้นหลังหลักแบบ Gradient Cool Tone */
  body {
    background: linear-gradient(135deg, #5b8efc 0%, #5b8efc 100%);
    background-attachment: fixed;
    font-family: 'Prompt', sans-serif;
    color: #2c2c2c;
  }

  /* 🧊 การ์ด */
  .card {
    border: none;
    border-radius: 1rem;
    background: #ffffff;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
  }

  /* 🎨 หัวการ์ด */
  .card-header {
    background: linear-gradient(90deg, #0065eaff, #5ed0ff);
    color: white;
    font-weight: 500;
    border-top-left-radius: 1rem;
    border-top-right-radius: 1rem;
  }

  /* 📋 ตาราง */
  .table {
    background: #ffffff;
    border-radius: 0.75rem;
    overflow: hidden;
  }

  thead.table-light {
    background: #eef5ff;
    color: #333;
  }

  tbody tr:hover {
    background-color: #f5faff;
  }

  tfoot {
    background-color: #f0f6ff;
  }

  /* 🔵 ปุ่มหลัก */
  .btn-primary {
    background: linear-gradient(90deg, #5b8efc, #5ed0ff);
    border: none;
    transition: 0.3s;
  }

  .btn-primary:hover {
    background: linear-gradient(90deg, #4b7ae8, #4fc3f7);
    transform: translateY(-2px);
  }

  /* 🟦 ปุ่มขอบน้ำเงิน */
  .btn-outline-primary {
    border-color: #5b8efc;
    color: #5b8efc;
    transition: 0.3s;
  }

  .btn-outline-primary:hover {
    background: linear-gradient(90deg, #5b8efc, #5ed0ff);
    color: #fff;
  }

  /* 🔹 Pagination */
  .pagination .page-link {
    color: #5b8efc;
    border-radius: 50%;
    margin: 0 3px;
  }

  .pagination .page-item.active .page-link {
    background: linear-gradient(90deg, #5b8efc, #5ed0ff);
    border: none;
    color: white;
  }

  .pagination .page-link:hover {
    background: #5b8efc;
    color: #fff;
  }

  /* 🌊 Navbar */
  nav.navbar {
    background: #212529;
    border-bottom: 4px solid #5b8efc;
  }
</style>


  <body class="bg-light">

<?php
  session_start();
  include_once __DIR__ . '/include/connDB.php';

  // --------------------------
  // 1. ตั้งค่าการแบ่งหน้า
  // --------------------------
  $limit = 10; // จำนวนแถวต่อหน้า
  $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
  $offset = ($page - 1) * $limit;

  // --------------------------
  // 2. นับจำนวนข้อมูลทั้งหมด
  // --------------------------
  $count_sql = "SELECT COUNT(*) FROM tb_orders";
  $total_rows = $pdo->query($count_sql)->fetchColumn();
  $total_pages = ceil($total_rows / $limit);

  // --------------------------
  // 3. ดึงข้อมูลตามหน้าที่เลือก
  // --------------------------
  $sql = "SELECT o.i_OrderID, o.c_OrderDate, 
                 CONCAT(e.c_FirstName,' ',e.c_LastName) AS emp_name, 
                 c.c_customername AS cust_name
          FROM tb_orders o
          JOIN tb_employees e ON e.i_EmployeeID = o.i_EmployeeID
          JOIN tb_customers c ON c.i_customerid = o.i_CustomerID
          ORDER BY o.i_OrderID DESC
          LIMIT :limit OFFSET :offset";
  $stmt_orders = $pdo->prepare($sql);
  $stmt_orders->bindValue(':limit', $limit, PDO::PARAM_INT);
  $stmt_orders->bindValue(':offset', $offset, PDO::PARAM_INT);
  $stmt_orders->execute();
  $orders = $stmt_orders->fetchAll(PDO::FETCH_ASSOC);

  // --------------------------
  // 4. Statement สำหรับรวมยอด
  // --------------------------
  $stmt = $pdo->prepare("SELECT SUM(od.i_Quantity) qty_sum, SUM(od.i_Quantity * p.i_Price) price_sum
                         FROM tb_orderdetails od 
                         JOIN tb_products p ON p.i_ProductID = od.i_ProductID
                         WHERE od.i_OrderID = :oid");
?>

<?php include_once __DIR__ . "/include/navbar.php"; ?>

<div class="container py-4">
  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
      <i class="bi bi-clipboard2-data"></i> รายงานสรุปยอดขายสินค้า
    </div>
    <div class="card-body">
      <?php if(isset($_SESSION['flash'])): $f=$_SESSION['flash']; unset($_SESSION['flash']); ?>
        <div class="alert alert-<?php echo $f['type']; ?> alert-dismissible fade show" role="alert">
          <?php echo htmlspecialchars($f['msg']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>เลขที่การขาย</th>
              <th>วันที่ขาย</th>
              <th>พนักงานขาย</th>
              <th>ลูกค้า</th>
              <th class="text-end">จำนวนสินค้ารวม</th>
              <th class="text-end">ราคารวม (บาท)</th>
              <th>รายละเอียด</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              $grand_qty = 0; 
              $grand_price = 0;
              foreach($orders as $o):
                $stmt->execute([':oid'=>$o['i_OrderID']]);
                $t = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['qty_sum'=>0,'price_sum'=>0];
                $grand_qty += (int)$t['qty_sum']; 
                $grand_price += (float)$t['price_sum'];
            ?>
              <tr>
                <td><?php echo $o['i_OrderID']; ?></td>
                <td><?php echo htmlspecialchars($o['c_OrderDate']); ?></td>
                <td><?php echo htmlspecialchars($o['emp_name']); ?></td>
                <td><?php echo htmlspecialchars($o['cust_name']); ?></td>
                <td class="text-end"><?php echo number_format((int)$t['qty_sum']); ?></td>
                <td class="text-end"><?php echo number_format((float)$t['price_sum'], 2); ?></td>
                <td>
                  <a class="btn btn-outline-primary btn-sm" href="order_detail.php?order_id=<?php echo $o['i_OrderID']; ?>">
                    <i class="bi bi-receipt"></i> รายละเอียด
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr class="table-secondary fw-bold">
              <td colspan="4" class="text-end">รวมทั้งสิ้น</td>
              <td class="text-end"><?php echo number_format($grand_qty); ?></td>
              <td class="text-end"><?php echo number_format($grand_price, 2); ?></td>
              <td></td>
            </tr>
          </tfoot>
        </table>
      </div>

      <a href="orders_create.php" class="btn btn-primary">
        <i class="bi bi-bag-plus"></i> สร้างคำสั่งขายสินค้า
      </a>

      <!-- ปุ่มแบ่งหน้า -->
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center mt-4">
          <!-- ปุ่มย้อนกลับ -->
          <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo $page - 1; ?>"><i class="bi bi-chevron-left"></i></a>
          </li>

          <!-- หมายเลขหน้า -->
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
              <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>

          <!-- ปุ่มหน้าถัดไป -->
          <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo $page + 1; ?>"><i class="bi bi-chevron-right"></i></a>
          </li>
        </ul>
      </nav>

    </div>
  </div>
</div>

  </body>
</html>
