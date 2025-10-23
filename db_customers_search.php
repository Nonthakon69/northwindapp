<?php
        include_once 'include/connDB.php';
        include_once 'include/elementMod.php';
        //print_r($_POST);
            // 1. เตรียมคำสั่ง SQL 
        //SELECT tb_products.i_ProductID as pid , tb_products.c_ProductName as pname , tb_products.i_Price as pprice  FROM tb_products WHERE tb_products.i_CategoryID = :param_catid  AND tb_products.i_Price >= :param_price ;
        $stmt = $pdo->prepare("SELECT tb_customers.i_customerid as cid , tb_customers.c_customername as cname , tb_customers.c_contactname as ccontact  FROM tb_customers WHERE tb_customers.c_city = :param_ccity  AND tb_customers.c_country = :param_ccountry");

        // 2. สั่งให้ทำงานทำให้ได้ผลลัพธ์เป็นแบบ associative array
        $stmt->execute([
            ':param_ccity'=> isset($_POST['cond_ccity']) ? $_POST['cond_ccity'] : '',     
            ':param_ccountry'=> isset($_POST['cond_ccountry']) ? $_POST['cond_ccountry'] : ''
        ]);

        // 3. ดึงผลลัพธ์ทั้งหมดมาเก็บในรูปแบบ associative array
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);



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
                form.action = 'db_customers_edit.php';
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
                <h1>หน้าจอค้นลูกค้า</h1>
                    <div id="accordion">
                            <div class="card">
                                <div class="card-header">
                                <a class="btn" data-bs-toggle="collapse" href="#collapseOne">
                                    ตัวกรองข้อมูล
                                </a>
                                </div>
                                <div id="collapseOne" class="collapse show" data-bs-parent="#accordion">
                                <div class="card-body">
                                    <!-- FORM FILTER -->
                                     <form action = "db_customers_search.php" method = "POST">
                                        <div class="row">
                                            <div class="col-5">
                                            <!-- <input type="text" class="form-control" placeholder="กรุณาใส่หมวดหมู่สินค้าที่ต้องการกรอง" name="cond_catid"> -->
                                             <?php dropdown_db($pdo,"cond_ccountry","tb_customers","i_customerid","c_country"); ?>
                                            </div>
                                            <div class="col-5">
                                            <?php dropdown_db($pdo,"cond_ccity","tb_customers","i_customerid","c_city"); ?>
                                            </div>
                                            <div class="col-2 d-grid gap-2">
                                            <button type="submit" class="btn btn-primary mb-3"><i class="bi bi-search"></i>&nbsp;ค้นหาข้อมูล</button>
                                            </div>
                                        </div>
                                     </form>
                                    <!-- END FORM FILTER -->
                                </div>
                                </div>
                            </div>
                            </div>
                <p></p>    

                <div class="card">
                    <div class="card-header">หน้าจอแสดงข้อมูลลูกค้า</div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Customer Contac</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php  foreach ($customers as $crod) { ?>
                            <tr>
                                <td><?= $crod['cid'] ?></td>
                                <td><?= $crod['cname'] ?></td>
                                <td><?= $crod['ccontact'] ?></td>
                                <td><a href="db_customers_edit.php?cid=<?= $crod['cid'] ?>">Edit</a></td>
                                <td>
                                    <form action="db_customers_edit.php?cid=<?= $crod['cid'] ?>" method="post">
                                        <input type = "hidden" name = "cid" value = "<?= $crod['cid'] ?>">
                                        <button type="submit">Edit</button>
                                    </form>
                                </td>
                                <td>
                                    <button type = "button" class = "btn btn-danger" onclick = "EditData(<?= $crod['cid'] ?>)">Delete</button>

                                </td>
                                <td>
                                    <form action="db_customers_delete.php" method="post" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                        <input type="hidden" name="cid" value="<?= $crod['cid'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Delete</button>
                                    </form>
                                </td>
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