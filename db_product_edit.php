<?php    
    include_once 'include/connDB.php';
    include_once 'include/funcMod.php';
    include_once 'include/elementMod.php'; 

    print_r($_POST);
    //
    $data = getEdit( $pdo,'tb_products','i_ProductID',$_POST['pid'] );
    print_r($data);

    // $stmt = $pdo->prepare("SELECT * FROM tb_products WHERE i_ProductID = :param_pid ;");
    // $stmt->execute(['param_pid' => $_POST['pid']]);
    // $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // $data = $rows[0];

    // print_r($rows);
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
    </head>

    <body>
        <div class="container-fluid">
            <div class="card">  
                <div class="card-header">Header</div>
                <div class="card-body"><form action="include/action_update.php" method = "POST">
                    <input type="hidden" name = "tb_name" value = "tb_product" >
                    <?= input_text("i_ProductID","รหัสสินค้า","number",$data["i_ProductID"],"กรุณากรอกรหัสสินค้า"); ?>
                    <?= input_text("c_ProductName","ชื่อสินค้า","text",$data["c_ProductName"],"กรุณากรอกชื่อสินค้า"); ?>
                    <?= input_text("c_Unit","หน่วยนับสินค้า","text",$data["c_Unit"],"กรุณากรอกหน่วยนับสินค้า"); ?>
                    <?= input_text("i_Price","ราคาสินค้า","text",$data["i_Price"],"กรุณากรอกราคาสินค้า"); ?>
                    <?= input_text("i_SupplierID","รหัสผู้ผลิตสินค้า","text",$data["i_SupplierID"],"กรุณากรอกผู้ผลิตสินค้า"); ?>
                    <?= input_dropdown($pdo,"i_CategoryID","หมวดหมู่สินค้า","tb_categories","i_CategoryID","c_CategoryName",$data["i_CategoryID"]) ?>
                    <?= input_dropdown($pdo,"i_SupplierID","หมวดหมู่ลูกค้า","tb_suppliers","i_SupplierID","c_SupplierName",$data["i_SupplierID"]) ?>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                </div>
                <!-- <div class="card-footer"><button type="submit" class="btn btn-primary">Submit</button></div> -->
            </div>
        </div>
                
    </body>
</html>

