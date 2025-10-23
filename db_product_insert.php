<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include_once 'include/connDB.php';
    include_once 'include/funcMod.php';
    include_once 'include/elementMod.php'; 

    $NewID = getNewID($pdo,"tb_products","i_ProductID");
   echo $NewID ;
    
   
    // print_r($data);
    // print_r($data['c_ProductName']);
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>

        <!-- BS5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">  
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- GG Font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

        <style>
            body{
                background: #2A7B9B;
                background: linear-gradient(90deg, rgba(42, 123, 155, 1) 7%, rgb(3, 72, 193) 50%, rgb(2, 151, 192) 100%);
                font-family: 'Kanit', sans-serif;
            }
            h1{
                color: white  
            }
        </style>

     

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <script>
    // รอให้หน้าเว็บโหลดเสร็จก่อนเริ่มทำงาน
    document.addEventListener('DOMContentLoaded', function () {
        
        // เลือกฟอร์ม, modal, และปุ่มยืนยันใน modal
        const form = document.getElementById('myForm');
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');

        // เพิ่ม Event Listener เมื่อฟอร์มมีการกด submit
        form.addEventListener('submit', function (event) {
            
            // ป้องกันการส่งฟอร์มตามปกติไปก่อน
            event.preventDefault();
            event.stopPropagation();
            
            // เพิ่ม class 'was-validated' เพื่อแสดงผล validation
            form.classList.add('was-validated');

            // ตรวจสอบความถูกต้องของข้อมูลในฟอร์ม
            if (form.checkValidity()) {
                // ถ้าข้อมูลถูกต้องทั้งหมด ให้แสดง Modal
                confirmModal.show();
            }

        }, false);

        // เพิ่ม Event Listener ที่ปุ่ม "ตกลง" ใน Modal
        confirmSubmitBtn.addEventListener('click', function () {
            // เมื่อกดตกลง ให้ทำการ submit ฟอร์มจริงๆ
            // เราสามารถใช้ form.submit() ได้เลย เพราะมันจะข้าม event listener ด้านบน
            form.submit();
        });
    });
</script>
    </head>

    <body>
        <div class="container-fluid">
            <div class="card">  
                <div class="card-header">Header</div>
                <div class="card-body"><form action="include/action_update.php" method = "POST">
                    <input type="hidden" name = "tb_name" value = "tb_product" >
                    <?= input_text("i_ProductID","รหัสสินค้า","number",$NewID,"กรุณากรอกรหัสสินค้า",true); ?>
                    <?= input_text("c_ProductName","ชื่อสินค้า","text",null,"กรุณากรอกชื่อสินค้า"); ?>
                    <?= input_text("c_Unit","หน่วยนับสินค้า","text",null,"กรุณากรอกหน่วยนับสินค้า"); ?>
                    <?= input_text("i_Price","ราคาสินค้า","text",null,"กรุณากรอกราคาสินค้า"); ?>
                    <?= input_text("i_SupplierID","รหัสผู้ผลิตสินค้า","text",null,"กรุณากรอกผู้ผลิตสินค้า"); ?>
                    <?= input_dropdown($pdo,"i_CategoryID","หมวดหมู่สินค้า","tb_categories","i_CategoryID","c_CategoryName",null) ?>
                    <?= input_dropdown($pdo,"i_SupplierID","หมวดหมู่ลูกค้า","tb_suppliers","i_SupplierID","c_SupplierName",null) ?>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                </div>
                <!-- <div class="card-footer"><button type="submit" class="btn btn-primary">Submit</button></div> -->
            </div>
        </div>
                
    </body>
</html>

