<?php
session_start();
include_once __DIR__ . '/include/connDB.php';
include_once __DIR__ . '/include/funcMod.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: orders_create.php'); exit; }

$i_OrderID     = intval($_POST['i_OrderID'] ?? 0);
$c_OrderDate   = $_POST['c_OrderDate'] ?? '';
$i_EmployeeID  = intval($_POST['i_EmployeeID'] ?? 0);
$i_CustomerID  = intval($_POST['i_CustomerID'] ?? 0);
$i_ShipperID   = intval($_POST['i_ShipperID'] ?? 0);
$items         = $_POST['items'] ?? [];

$filtered = [];
foreach ($items as $it) {
  $pid = intval($it['i_ProductID'] ?? 0);
  $qty = intval($it['i_Quantity'] ?? 0);
  if ($pid > 0 && $qty > 0) $filtered[] = ['i_ProductID'=>$pid, 'i_Quantity'=>$qty];
}
if (count($filtered) == 0) {
  $_SESSION['flash'] = ['type'=>'danger','msg'=>'กรุณาเลือกรายการสินค้าอย่างน้อย 1 รายการ'];
  header('Location: orders_create.php'); exit;
}

try {
  $pdo->beginTransaction();
  $stmt = $pdo->prepare("INSERT INTO tb_orders (i_OrderID, i_CustomerID, i_EmployeeID, c_OrderDate, i_ShipperID) VALUES (:id,:cid,:eid,:odate,:sid)");
  $stmt->execute([':id'=>$i_OrderID, ':cid'=>$i_CustomerID, ':eid'=>$i_EmployeeID, ':odate'=>$c_OrderDate, ':sid'=>$i_ShipperID]);
  $maxDetail = $pdo->query("SELECT COALESCE(MAX(i_OrderDetailID),0) AS mx FROM tb_orderdetails")->fetch(PDO::FETCH_ASSOC)['mx'];
  $detailId = intval($maxDetail);
  $stmtD = $pdo->prepare("INSERT INTO tb_orderdetails (i_OrderDetailID, i_OrderID, i_ProductID, i_Quantity) VALUES (:did,:oid,:pid,:qty)");
  foreach ($filtered as $row) {
    $detailId += 1;
    $stmtD->execute([':did'=>$detailId, ':oid'=>$i_OrderID, ':pid'=>$row['i_ProductID'], ':qty'=>$row['i_Quantity']]);
  }
  $pdo->commit();
  $_SESSION['flash'] = ['type'=>'success','msg'=>'บันทึกคำสั่งขายเรียบร้อย'];
  header('Location: orders_list.php'); exit;
} catch (Throwable $ex) {
  $pdo->rollBack();
  $_SESSION['flash'] = ['type'=>'danger','msg'=>'บันทึกล้มเหลว: '.$ex->getMessage()];
  header('Location: orders_create.php'); exit;
}
