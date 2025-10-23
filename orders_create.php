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

  .modal-content {
    border-radius: 1rem;
  }

  .modal-header {
    background: linear-gradient(90deg, #5b86e5, #36d1dc);
  }
</style>

  </head>

  <body>
<?php
  include_once __DIR__ . '/include/connDB.php';
  include_once __DIR__ . '/include/funcMod.php';
  $employees = $pdo->query("SELECT i_EmployeeID, CONCAT(c_FirstName,' ',c_LastName) as full FROM tb_employees ORDER BY i_EmployeeID")->fetchAll(PDO::FETCH_ASSOC);
  $customers = $pdo->query("SELECT i_customerid, c_customername FROM tb_customers ORDER BY i_customerid")->fetchAll(PDO::FETCH_ASSOC);
  $shippers  = $pdo->query("SELECT i_SupplierID, c_SupplierName FROM tb_suppliers ORDER BY i_SupplierID")->fetchAll(PDO::FETCH_ASSOC);
  $products  = $pdo->query("SELECT i_ProductID, c_ProductName FROM tb_products ORDER BY i_ProductID")->fetchAll(PDO::FETCH_ASSOC);
  $newOrderId = getNewID($pdo, 'tb_orders', 'i_OrderID'); if (!$newOrderId) $newOrderId = 10001;
  $today = date('j/n/Y');
?>
<?php include_once __DIR__ . "/include/navbar.php"; ?>

<div class="container py-5">
  <div class="card shadow-lg">
    <div class="card-header text-white">
      <i class="bi bi-bag-plus"></i> สร้างคำสั่งขายสินค้า
    </div>
    <div class="card-body bg-white">
      <form method="post" action="orders_store.php">
        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label">เลขที่การขาย (อัตโนมัติ)</label>
            <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($newOrderId); ?>" disabled>
            <input type="hidden" name="i_OrderID" value="<?php echo htmlspecialchars($newOrderId); ?>">
          </div>
          <div class="col-md-3">
            <label class="form-label">วันที่ขาย (อัตโนมัติ)</label>
            <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($today); ?>" disabled>
            <input type="hidden" name="c_OrderDate" value="<?php echo htmlspecialchars($today); ?>">
          </div>
          <div class="col-md-3">
            <label class="form-label">พนักงานขาย</label>
            <select name="i_EmployeeID" class="form-select" required>
              <option value="">-- เลือกพนักงานขาย --</option>
              <?php foreach($employees as $e): ?>
                <option value="<?php echo $e['i_EmployeeID']; ?>"><?php echo htmlspecialchars($e['full']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">ลูกค้า</label>
            <select name="i_CustomerID" class="form-select" required>
              <option value="">-- เลือกชื่อลูกค้า --</option>
              <?php foreach($customers as $c): ?>
                <option value="<?php echo $c['i_customerid']; ?>"><?php echo htmlspecialchars($c['c_customername']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">บริษัทขนส่ง</label>
            <select name="i_ShipperID" class="form-select" required>
              <option value="">-- เลือกบริษัทขนส่ง --</option>
              <?php foreach($shippers as $s): ?>
                <option value="<?php echo $s['i_SupplierID']; ?>"><?php echo htmlspecialchars($s['c_SupplierName']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <hr class="my-4">
        <h5 class="mb-3 text-primary"><i class="bi bi-box-seam"></i> รายการสินค้า (ได้ไม่เกิน 10 รายการ)</h5>

        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr><th>สินค้า</th><th style="width:20%">จำนวน</th></tr>
            </thead>
            <tbody>
              <?php for($i=0;$i<10;$i++): ?>
              <tr>
                <td>
                  <select name="items[<?php echo $i; ?>][i_ProductID]" class="form-select">
                    <option value="">-- เลือกสินค้า --</option>
                    <?php foreach($products as $p): ?>
                      <option value="<?php echo $p['i_ProductID']; ?>"><?php echo htmlspecialchars($p['c_ProductName']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td><input type="number" name="items[<?php echo $i; ?>][i_Quantity]" class="form-control" min="1"></td>
              </tr>
              <?php endfor; ?>
            </tbody>
          </table>
        </div>

        <div class="mt-4">
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal">
            <i class="bi bi-check2-circle"></i> ยืนยันการสั่งซื้อ
          </button>
          <a href="orders_list.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> กลับหน้ารายงาน</a>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-question-circle"></i> ยืนยันการสั่งซื้อ?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                ตรวจสอบข้อมูลให้ครบถ้วนก่อนยืนยัน ระบบจะบันทึกลง tb_orders และ tb_orderdetails
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn btn-primary">ยืนยันการสั่งซื้อ</button>
              </div>
            </div>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>

  </body>
</html>
