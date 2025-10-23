<!doctype html>
<html lang="th">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Northwind App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
      body {
        background: linear-gradient(135deg, #5b8efc 0%, #5b8efc 100%);
        font-family: 'Prompt', sans-serif;
      }

      .card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        background: #ffffff;
      }

      .card-header {
        background: linear-gradient(90deg, #0065eaff, #66CCFF);
        color: #fff;
        font-weight: 500;
      }

      .btn-success {
        background: linear-gradient(90deg, #52c234, #06beb6);
        border: none;
      }
      .btn-success:hover {
        background: linear-gradient(90deg, #3ca326, #049e91);
      }

      .btn-primary {
        background: linear-gradient(90deg, #5b86e5, #36d1dc);
        border: none;
      }
      .btn-primary:hover {
        background: linear-gradient(90deg, #4a73c9, #2bb5bf);
      }

      table.table {
        border-radius: 0.75rem;
        overflow: hidden;
      }

      thead.table-light {
        background: #97cbffff;
      }

      tfoot.table-secondary {
        background: linear-gradient(90deg, #66ccff, #0065ea);
        color: #fff;
        font-weight: 500;
      }

      .modal-content {
        border-radius: 1rem;
      }

      .modal-header {
        background: linear-gradient(90deg, #5b86e5, #36d1dc);
        color: #fff;
      }
    </style>
  </head>
  <body>

<?php
  include_once __DIR__ . '/include/connDB.php';
  $order_id = intval($_GET['order_id'] ?? 0);
  if ($order_id <= 0) { header('Location: orders_list.php'); exit; }
  $head = $pdo->prepare("SELECT o.*, CONCAT(e.c_FirstName,' ',e.c_LastName) emp_name, c.c_customername cust_name
                         FROM tb_orders o JOIN tb_employees e ON e.i_EmployeeID = o.i_EmployeeID
                         JOIN tb_customers c ON c.i_customerid = o.i_CustomerID
                         WHERE o.i_OrderID = :oid");
  $head->execute([':oid'=>$order_id]);
  $order = $head->fetch(PDO::FETCH_ASSOC);
  if(!$order) { header('Location: orders_list.php'); exit; }

  $stmt = $pdo->prepare("SELECT p.c_ProductName, p.i_Price, od.i_Quantity, (p.i_Price * od.i_Quantity) AS line_total
                         FROM tb_orderdetails od JOIN tb_products p ON p.i_ProductID = od.i_ProductID
                         WHERE od.i_OrderID = :oid ORDER BY od.i_OrderDetailID");
  $stmt->execute([':oid'=>$order_id]);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include_once __DIR__ . "/include/navbar.php"; ?>
<div class="container py-4">
  <div class="card shadow-sm">
    <div class="card-header"><i class="bi bi-receipt"></i> รายละเอียดการขาย #<?php echo $order_id; ?></div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-4"><strong>วันที่ขาย:</strong> <?php echo htmlspecialchars($order['c_OrderDate']); ?></div>
        <div class="col-md-4"><strong>พนักงานขาย:</strong> <?php echo htmlspecialchars($order['emp_name']); ?></div>
        <div class="col-md-4"><strong>ลูกค้า:</strong> <?php echo htmlspecialchars($order['cust_name']); ?></div>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light"><tr><th>สินค้า</th><th class="text-end">ราคา/หน่วย</th><th class="text-end">จำนวน</th><th class="text-end">ราคารวม</th></tr></thead>
          <tbody>
            <?php $sum_qty=0; $sum_price=0; foreach($rows as $r): $sum_qty += (int)$r['i_Quantity']; $sum_price += (float)$r['line_total']; ?>
            <tr>
              <td><?php echo htmlspecialchars($r['c_ProductName']); ?></td>
              <td class="text-end"><?php echo number_format((float)$r['i_Price'], 2); ?></td>
              <td class="text-end"><?php echo number_format((int)$r['i_Quantity']); ?></td>
              <td class="text-end"><?php echo number_format((float)$r['line_total'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot class="table-secondary"><tr>
            <td class="text-end">รวม</td><td></td>
            <td class="text-end"><?php echo number_format($sum_qty); ?></td>
            <td class="text-end"><?php echo number_format($sum_price, 2); ?></td>
          </tr></tfoot>
        </table>
      </div>
      <a href="orders_list.php" class="btn btn-primary"><i class="bi bi-arrow-left"></i> กลับไปหน้ารายงาน</a>
    </div>
  </div>
</div>

  </body>
</html>
