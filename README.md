# Northwind App (PHP + Bootstrap 5)

วิธีติดตั้งอย่างย่อ:
1) ติดตั้ง Apache+PHP+MySQL (เช่น XAMPP/MAMP/WAMP)
2) Import ฐานข้อมูลจากไฟล์ `dbNorthwind (1).sql` เพื่อสร้าง `db_northwind`
3) แก้ไขการเชื่อมต่อใน `include/connDB.php` ให้ตรงกับเครื่อง
4) คัดลอกโฟลเดอร์ `northwind_app` ไปไว้ในโฟลเดอร์เว็บ เช่น `htdocs/northwind_app`
5) เปิด `http://localhost/northwind_app/`

หน้าจอตามโจทย์:
- orders_create.php: สร้างคำสั่งขาย (ID และวันที่อัตโนมัติ, เลือกพนักงาน ลูกค้า บริษัทขนส่ง, เลือกสินค้าได้ถึง 10 รายการ + modal ยืนยัน)
- orders_list.php: ตรวจสอบยอดขาย (สรุปจำนวนและราคารวม, ปุ่มดูรายละเอียด)
- order_detail.php: แสดงรายละเอียดการขาย (ชื่อสินค้า ราคา จำนวน และราคารวม + รวมด้านล่าง)
