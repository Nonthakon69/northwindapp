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
  <body class="bg-light">

<?php include_once __DIR__ . "/include/connDB.php"; ?>
<?php include_once __DIR__ . "/include/navbar.php"; ?>
<div class="container py-4">
  <div class="p-5 mb-4 bg-white rounded-3 shadow-sm">
    <div class="container-fluid py-5">
      <h1 class="display-6 fw-bold">โครงงาน Web Application – Northwind</h1>
      <p class="col-md-10 fs-5">ตามโจทย์: CRUD ข้อมูลพื้นฐาน + หน้าขายสินค้า + ตรวจสอบยอดขาย + รายละเอียดการขาย ด้วย PHP (PDO) และ Bootstrap 5</p>
      <a href="orders_create.php" class="btn btn-primary btn-lg"><i class="bi bi-bag-plus"></i> สร้างคำสั่งขายสินค้า</a>
      <a href="orders_list.php" class="btn btn-outline-secondary btn-lg"><i class="bi bi-list-ul"></i> ตรวจสอบยอดขาย</a>
    </div>
  </div>
</div>

  </body>
</html>
