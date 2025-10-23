    <?php
        include_once 'include/connDB.php';
        include_once 'include/elementMod.php';
        //print_r($_POST);
            // 1. เตรียมคำสั่ง SQL 
        //SELECT tb_products.i_ProductID as pid , tb_products.c_ProductName as pname , tb_products.i_Price as pprice  FROM tb_products WHERE tb_products.i_CategoryID = :param_catid  AND tb_products.i_Price >= :param_price ;
        $stmt = $pdo->prepare("SELECT tb_products.i_ProductID as pid , tb_products.c_ProductName as pname , tb_products.i_Price as pprice  FROM tb_products WHERE tb_products.i_CategoryID = :param_catid  AND tb_products.i_Price >= :param_price");

        // 2. สั่งให้ทำงานทำให้ได้ผลลัพธ์เป็นแบบ associative array
        $stmt->execute([
            ':param_catid'=> $_POST['cond_catid'] ,     
            ':param_price'=> $_POST['cond_price']
        ]);

        // 3. ดึงผลลัพธ์ทั้งหมดมาเก็บในรูปแบบ associative array
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);



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

        <script>
            function EditData(id){
                //alert("Edit Data : " + id);
                console.log("Edit Data : " + id);
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'db_product_edit.php';
                const input1 = document.createElement('input');
                input1.type = 'hidden';
                input1.name = 'pid';
                input1.value = id ;
                form.appendChild(input1);
                document.body.appendChild(form);
                form.submit();
            }

        </script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    </head>

    <body>
        <?php require_once 'include/navbar.php';  // Requi?>
        <div class="container">
                <h1>หน้าจอค้นหาสินค้า</h1>
                    <div id="accordion">
                            <div class="card">
                                <div class="card-header">
                                <a class="btn" data-bs-toggle="collapse" href="#collapseOne">
                                    ตัวกรองข้อมูล
                                </a>
                                </div>
                                <div id="collapseOne" class="collapse show" data-bs-parent="#accordion">
                                <div class="card-body">
                                    <!-- FROM FILTER -->
                                     <form action = "db_product_search.php" method = "POST">
                                        <div class="row">
                                            <div class="col-5">
                                            <!-- <input type="text" class="form-control" placeholder="กรุณาใส่หมวดหมู่สินค้าที่ต้องการกรอง" name="cond_catid"> -->
                                             <?php dropdown_db($pdo,"cond_catid","tb_categories","i_CategoryID","c_CategoryName"); ?>
                                            </div>
                                            <div class="col-5">
                                            <input type="number" class="form-control" placeholder="กรุณาใส่ราคาสินค้าที่ต้องการกรอง" name="cond_price">
                                            </div>
                                            <div class="col-2 d-grid gap-2">
                                            <button type="submit" class="btn btn-primary mb-3"><i class="bi bi-search"></i>&nbsp;ค้นหาข้อมูล</button>
                                            </div>
                                        </div>
                                     </form>
                                    <!-- END FROM FILTER -->
                                </div>
                                </div>
                            </div>
                            </div>
                <p></p>    

                <div class="card">
                    <div class="card-header">หน้าจอแสดงรายการสินค้า</div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Product Price</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php  foreach ($products as $prod) { ?>
                            <tr>
                                <td><?= $prod['pid'] ?></td>
                                <td><?= $prod['pname'] ?></td>
                                <td><?= $prod['pprice'] ?></td>
                                <td><a href = "db_product_edit.php?pid = <?= $prod['pid'] ?>">Edit</a></td>
                                <td>
                                    <from action = "db_product_edit.php?pid" method = "post">
                                        <input type = "hidden" name = "pid" value = "<?= $prod['pid'] ?>" hidden>
                                        <button type = "submit">Edit</button>
                                    </form>
                                </td>
                                <td>
                                    <button type = "button" class = "btn btn-danger" onclick = "EditData(<?= $prod['pid'] ?>)">Edit</button>

                                </td>
                                <td>Delete</td>
                            </tr>
                            <?php }?>

                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <ul class="pagination justify-content-end">
                            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </div>
                </div>
                <p></p>
        </div>
    </body>
    </html>

