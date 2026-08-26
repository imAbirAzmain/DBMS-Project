-------------------------------------------------
-- EMPLOYEE
-------------------------------------------------

INSERT INTO Employee VALUES (101,'pass101','Incharge',60000,'Active',TO_DATE('2026-08-01','YYYY-MM-DD'));
INSERT INTO Employee VALUES (102,'pass102','Incharge',62000,'Active',TO_DATE('2026-08-02','YYYY-MM-DD'));
INSERT INTO Employee VALUES (103,'pass103','Incharge',61000,'Active',TO_DATE('2026-08-03','YYYY-MM-DD'));

INSERT INTO Employee VALUES (201,'pass201','Worker',28000,'Active',TO_DATE('2026-08-01','YYYY-MM-DD'));
INSERT INTO Employee VALUES (202,'pass202','Worker',30000,'Active',TO_DATE('2026-08-02','YYYY-MM-DD'));
INSERT INTO Employee VALUES (203,'pass203','Worker',29000,'Inactive',TO_DATE('2026-08-01','YYYY-MM-DD'));

-------------------------------------------------
-- INCHARGE
-------------------------------------------------

INSERT INTO Incharge VALUES
(101,'Rahim Ahmed','Cutting','rahim@garments.com',
'Dhaka');

INSERT INTO Incharge VALUES
(102,'Karim Hasan','Sewing','karim@garments.com',
'Gazipur');

INSERT INTO Incharge VALUES
(103,'Sabbir Islam','Finishing','sabbir@garments.com',
'Narayanganj');

-------------------------------------------------
-- WORKER
-------------------------------------------------

INSERT INTO Worker VALUES
(201,'Jahid Hasan','Dhaka','A','jahid@gmail.com');

INSERT INTO Worker VALUES
(202,'Rifat Ali','Gazipur','B','rifat@gmail.com');

INSERT INTO Worker VALUES
(203,'Sakib Khan','Narsingdi','A','sakib@gmail.com');

-------------------------------------------------
-- INCHARGE CONTACT
-------------------------------------------------

INSERT INTO Incharge_Contact VALUES
(101,'01711111111');

INSERT INTO Incharge_Contact VALUES
(101,'01811111111');

INSERT INTO Incharge_Contact VALUES
(102,'01722222222');

INSERT INTO Incharge_Contact VALUES
(102,'01822222222');

INSERT INTO Incharge_Contact VALUES
(103,'01733333333');

INSERT INTO Incharge_Contact VALUES
(103,'01833333333');

-------------------------------------------------
-- WORKER CONTACT
-------------------------------------------------

INSERT INTO Worker_Contact VALUES
(201,'01911111111');

INSERT INTO Worker_Contact VALUES
(201,'01611111111');

INSERT INTO Worker_Contact VALUES
(202,'01922222222');

INSERT INTO Worker_Contact VALUES
(202,'01622222222');

INSERT INTO Worker_Contact VALUES
(203,'01933333333');

INSERT INTO Worker_Contact VALUES
(203,'01633333333');


-------------------------------------------------
-- PRODUCTION_STAGE
-------------------------------------------------

INSERT INTO Production_Stage VALUES
(1,'Cutting','Completed',15,TO_DATE('2026-08-01','YYYY-MM-DD'),TO_DATE('2026-08-02','YYYY-MM-DD'));

INSERT INTO Production_Stage VALUES
(2,'Sewing','In Progress',20,TO_DATE('2026-08-03','YYYY-MM-DD'),TO_DATE('2026-08-07','YYYY-MM-DD'));

INSERT INTO Production_Stage VALUES
(3,'Embroidery','Completed',10,TO_DATE('2026-08-02','YYYY-MM-DD'),TO_DATE('2026-08-04','YYYY-MM-DD'));

INSERT INTO Production_Stage VALUES
(4,'Printing','Pending',8,TO_DATE('2026-08-05','YYYY-MM-DD'),TO_DATE('2026-08-08','YYYY-MM-DD'));

INSERT INTO Production_Stage VALUES
(5,'Quality Check','Pending',6,TO_DATE('2026-08-08','YYYY-MM-DD'),TO_DATE('2026-08-09','YYYY-MM-DD'));

INSERT INTO Production_Stage VALUES
(6,'Finishing','Pending',12,TO_DATE('2026-08-09','YYYY-MM-DD'),TO_DATE('2026-08-10','YYYY-MM-DD'));

-------------------------------------------------
-- MACHINERY
-------------------------------------------------

INSERT INTO Machinery VALUES
(1,'Juki Sewing Machine','Sewing',150.00,25);

INSERT INTO Machinery VALUES
(2,'Gerber Cutter','Cutting',220.00,8);

INSERT INTO Machinery VALUES
(3,'Embroidery Machine','Embroidery',180.00,12);

INSERT INTO Machinery VALUES
(4,'Heat Press','Printing',120.00,10);

INSERT INTO Machinery VALUES
(5,'Steam Iron','Finishing',60.00,30);

INSERT INTO Machinery VALUES
(6,'Quality Scanner','Inspection',90.00,5);

-------------------------------------------------
-- COSTING
-------------------------------------------------

INSERT INTO Costing VALUES (1,500000.00);
INSERT INTO Costing VALUES (2,620000.00);
INSERT INTO Costing VALUES (3,475000.00);
INSERT INTO Costing VALUES (4,710000.00);
INSERT INTO Costing VALUES (5,390000.00);
INSERT INTO Costing VALUES (6,830000.00);

-------------------------------------------------
-- ORDERS
-------------------------------------------------

INSERT INTO Orders VALUES
(1,'1000 Polo Shirts',
TO_DATE('2026-08-01','YYYY-MM-DD'),
TO_DATE('2026-08-15','YYYY-MM-DD'));

INSERT INTO Orders VALUES
(2,'800 Hoodies',
TO_DATE('2026-08-02','YYYY-MM-DD'),
TO_DATE('2026-08-18','YYYY-MM-DD'));

INSERT INTO Orders VALUES
(3,'1500 T-Shirts',
TO_DATE('2026-08-03','YYYY-MM-DD'),
TO_DATE('2026-08-16','YYYY-MM-DD'));

INSERT INTO Orders VALUES
(4,'500 Jackets',
TO_DATE('2026-08-04','YYYY-MM-DD'),
TO_DATE('2026-08-20','YYYY-MM-DD'));

INSERT INTO Orders VALUES
(5,'1200 Sports Jerseys',
TO_DATE('2026-08-05','YYYY-MM-DD'),
TO_DATE('2026-08-19','YYYY-MM-DD'));

INSERT INTO Orders VALUES
(6,'700 Sweatshirts',
TO_DATE('2026-08-06','YYYY-MM-DD'),
TO_DATE('2026-08-22','YYYY-MM-DD'));

-------------------------------------------------
-- MATERIAL
-------------------------------------------------

INSERT INTO Material VALUES
(1,'Cotton Fabric','Fabric','Meter',220.00);

INSERT INTO Material VALUES
(2,'Polyester Fabric','Fabric','Meter',180.00);

INSERT INTO Material VALUES
(3,'Sewing Thread','Thread','Cone',95.00);

INSERT INTO Material VALUES
(4,'Buttons','Accessories','Piece',5.00);

INSERT INTO Material VALUES
(5,'Zipper','Accessories','Piece',18.00);

INSERT INTO Material VALUES
(6,'Neck Label','Label','Piece',3.50);


-------------------------------------------------
-- INSPECTION
-------------------------------------------------

INSERT INTO Inspection VALUES (1,980,20,'Minor stitching defects');
INSERT INTO Inspection VALUES (2,790,10,'Passed');
INSERT INTO Inspection VALUES (3,1485,15,'Color variation');
INSERT INTO Inspection VALUES (4,495,5,'Passed');
INSERT INTO Inspection VALUES (5,1188,12,'Loose threads');
INSERT INTO Inspection VALUES (6,695,5,'Passed');

-------------------------------------------------
-- FINAL_PRODUCT
-------------------------------------------------

INSERT INTO Final_Product VALUES
(1,'A','LOT1001',TO_DATE('2026-08-10','YYYY-MM-DD'));

INSERT INTO Final_Product VALUES
(2,'A','LOT1002',TO_DATE('2026-08-11','YYYY-MM-DD'));

INSERT INTO Final_Product VALUES
(3,'B','LOT1003',TO_DATE('2026-08-12','YYYY-MM-DD'));

INSERT INTO Final_Product VALUES
(4,'A','LOT1004',TO_DATE('2026-08-13','YYYY-MM-DD'));

INSERT INTO Final_Product VALUES
(5,'A','LOT1005',TO_DATE('2026-08-14','YYYY-MM-DD'));

INSERT INTO Final_Product VALUES
(6,'B','LOT1006',TO_DATE('2026-08-15','YYYY-MM-DD'));

-------------------------------------------------
-- PACKAGING
-------------------------------------------------

INSERT INTO Packaging VALUES
(1,TO_DATE('2026-08-11','YYYY-MM-DD'),15.50,50,20,'Carton');

INSERT INTO Packaging VALUES
(2,TO_DATE('2026-08-12','YYYY-MM-DD'),18.00,40,20,'Carton');

INSERT INTO Packaging VALUES
(3,TO_DATE('2026-08-13','YYYY-MM-DD'),14.25,60,25,'Poly Bag');

INSERT INTO Packaging VALUES
(4,TO_DATE('2026-08-14','YYYY-MM-DD'),20.10,35,15,'Box');

INSERT INTO Packaging VALUES
(5,TO_DATE('2026-08-15','YYYY-MM-DD'),16.80,45,18,'Carton');

INSERT INTO Packaging VALUES
(6,TO_DATE('2026-08-16','YYYY-MM-DD'),19.50,30,22,'Box');

-------------------------------------------------
-- SHIPMENT
-------------------------------------------------

INSERT INTO Shipment VALUES
(1,'TRK100001',
TO_DATE('2026-08-18','YYYY-MM-DD'),
'USA',
TO_DATE('2026-08-16','YYYY-MM-DD'));

INSERT INTO Shipment VALUES
(2,'TRK100002',
TO_DATE('2026-08-19','YYYY-MM-DD'),
'Germany',
TO_DATE('2026-08-17','YYYY-MM-DD'));

INSERT INTO Shipment VALUES
(3,'TRK100003',
TO_DATE('2026-08-20','YYYY-MM-DD'),
'Canada',
TO_DATE('2026-08-18','YYYY-MM-DD'));

INSERT INTO Shipment VALUES
(4,'TRK100004',
TO_DATE('2026-08-21','YYYY-MM-DD'),
'UK',
TO_DATE('2026-08-19','YYYY-MM-DD'));

INSERT INTO Shipment VALUES
(5,'TRK100005',
TO_DATE('2026-08-22','YYYY-MM-DD'),
'France',
TO_DATE('2026-08-20','YYYY-MM-DD'));

INSERT INTO Shipment VALUES
(6,'TRK100006',
TO_DATE('2026-08-23','YYYY-MM-DD'),
'Japan',
TO_DATE('2026-08-21','YYYY-MM-DD'));

-------------------------------------------------
-- BUYER
-------------------------------------------------

INSERT INTO Buyer VALUES
(1,'ABC Fashion','ABC Brand','New York, USA','ACC1001','abc@brand.com');

INSERT INTO Buyer VALUES
(2,'Global Wear','Global Wear','Berlin, Germany','ACC1002','global@wear.com');

INSERT INTO Buyer VALUES
(3,'Urban Style','Urban Style','Toronto, Canada','ACC1003','urban@style.com');

INSERT INTO Buyer VALUES
(4,'Classic Apparel','Classic Apparel','London, UK','ACC1004','classic@apparel.com');

INSERT INTO Buyer VALUES
(5,'Elite Clothing','Elite Clothing','Paris, France','ACC1005','elite@clothing.com');

INSERT INTO Buyer VALUES
(6,'Tokyo Fashion','Tokyo Fashion','Tokyo, Japan','ACC1006','tokyo@fashion.com');

-------------------------------------------------
-- BUYER_CONTACT
-------------------------------------------------

INSERT INTO Buyer_Contact VALUES (1,'+12025550101');
INSERT INTO Buyer_Contact VALUES (2,'+49301234567');
INSERT INTO Buyer_Contact VALUES (3,'+14165551234');
INSERT INTO Buyer_Contact VALUES (4,'+442071234567');
INSERT INTO Buyer_Contact VALUES (5,'+33140123456');
INSERT INTO Buyer_Contact VALUES (6,'+81312345678');


-------------------------------------------------
-- PAYMENT
-------------------------------------------------

INSERT INTO Payment VALUES
(1,500000.00,300000.00,200000.00,'Bank Transfer',
TO_DATE('2026-08-12','YYYY-MM-DD'));

INSERT INTO Payment VALUES
(2,620000.00,620000.00,0.00,'LC',
TO_DATE('2026-08-13','YYYY-MM-DD'));

INSERT INTO Payment VALUES
(3,475000.00,250000.00,225000.00,'Bank Transfer',
TO_DATE('2026-08-14','YYYY-MM-DD'));

INSERT INTO Payment VALUES
(4,710000.00,500000.00,210000.00,'Swift',
TO_DATE('2026-08-15','YYYY-MM-DD'));

INSERT INTO Payment VALUES
(5,390000.00,390000.00,0.00,'Cash',
TO_DATE('2026-08-16','YYYY-MM-DD'));

INSERT INTO Payment VALUES
(6,830000.00,600000.00,230000.00,'LC',
TO_DATE('2026-08-17','YYYY-MM-DD'));

-------------------------------------------------
-- ACCOUNTS
-------------------------------------------------

INSERT INTO Accounts VALUES
(1,'Credited',300000.00,
TO_DATE('2026-08-12','YYYY-MM-DD'),
'Dutch Bangla Bank');

INSERT INTO Accounts VALUES
(2,'Credited',620000.00,
TO_DATE('2026-08-13','YYYY-MM-DD'),
'BRAC Bank');

INSERT INTO Accounts VALUES
(3,'Credited',250000.00,
TO_DATE('2026-08-14','YYYY-MM-DD'),
'City Bank');

INSERT INTO Accounts VALUES
(4,'Credited',500000.00,
TO_DATE('2026-08-15','YYYY-MM-DD'),
'Eastern Bank');

INSERT INTO Accounts VALUES
(5,'Credited',390000.00,
TO_DATE('2026-08-16','YYYY-MM-DD'),
'Islami Bank');

INSERT INTO Accounts VALUES
(6,'Credited',600000.00,
TO_DATE('2026-08-17','YYYY-MM-DD'),
'HSBC Bangladesh');

-------------------------------------------------
-- BOM
-------------------------------------------------

INSERT INTO BOM VALUES
(1,'Cotton Polo Shirt',210000.00,500000.00);

INSERT INTO BOM VALUES
(2,'Winter Hoodie',260000.00,620000.00);

INSERT INTO BOM VALUES
(3,'Basic T-Shirt',190000.00,475000.00);

INSERT INTO BOM VALUES
(4,'Denim Jacket',310000.00,710000.00);

INSERT INTO BOM VALUES
(5,'Sports Jersey',165000.00,390000.00);

INSERT INTO BOM VALUES
(6,'Premium Sweatshirt',340000.00,830000.00);

-------------------------------------------------
-- SUPPLIER
-------------------------------------------------

INSERT INTO Supplier VALUES
(1,'Square Textiles',
'Gazipur',
'square@supplier.com');

INSERT INTO Supplier VALUES
(2,'DBL Fabrics',
'Narayanganj',
'dbl@supplier.com');

INSERT INTO Supplier VALUES
(3,'ABC Accessories',
'Dhaka',
'abc@supplier.com');

INSERT INTO Supplier VALUES
(4,'Cotton World',
'Chattogram',
'cotton@supplier.com');

INSERT INTO Supplier VALUES
(5,'Fashion Source',
'Cumilla',
'fashion@supplier.com');

INSERT INTO Supplier VALUES
(6,'Global Textile Ltd.',
'Savar',
'global@supplier.com');

-------------------------------------------------
-- SUPPLIER_CONTACT
-------------------------------------------------

INSERT INTO Supplier_Contact VALUES (1,'01710000001');
INSERT INTO Supplier_Contact VALUES (2,'01710000002');
INSERT INTO Supplier_Contact VALUES (3,'01710000003');
INSERT INTO Supplier_Contact VALUES (4,'01710000004');
INSERT INTO Supplier_Contact VALUES (5,'01710000005');
INSERT INTO Supplier_Contact VALUES (6,'01710000006');

-------------------------------------------------
-- ORDER_STYLE (Weak Entity)
-------------------------------------------------

INSERT INTO Order_Style VALUES (1,1,'Polo Shirt','Blue','M');
INSERT INTO Order_Style VALUES (2,2,'Hoodie','Black','L');
INSERT INTO Order_Style VALUES (3,3,'T-Shirt','White','XL');
INSERT INTO Order_Style VALUES (4,4,'Jacket','Navy','L');
INSERT INTO Order_Style VALUES (5,5,'Sports Jersey','Red','M');
INSERT INTO Order_Style VALUES (6,6,'Sweatshirt','Grey','XL');

-------------------------------------------------
-- REL_WORKER_PRODUCTIONSTAGE
-------------------------------------------------

INSERT INTO Rel_Worker_ProductionStage VALUES (201,1);
INSERT INTO Rel_Worker_ProductionStage VALUES (202,2);
INSERT INTO Rel_Worker_ProductionStage VALUES (203,3);
INSERT INTO Rel_Worker_ProductionStage VALUES (201,4);
INSERT INTO Rel_Worker_ProductionStage VALUES (202,5);
INSERT INTO Rel_Worker_ProductionStage VALUES (203,6);

-------------------------------------------------
-- REL_INCHARGE_WORKER_PRODUCTIONSTAGE
-------------------------------------------------

INSERT INTO Rel_Incharge_Worker_ProductionStage
VALUES (101,201,1,'Completed');

INSERT INTO Rel_Incharge_Worker_ProductionStage
VALUES (102,202,2,'70% Complete');

INSERT INTO Rel_Incharge_Worker_ProductionStage
VALUES (103,203,3,'Completed');

INSERT INTO Rel_Incharge_Worker_ProductionStage
VALUES (101,201,4,'Pending');

INSERT INTO Rel_Incharge_Worker_ProductionStage
VALUES (102,202,5,'Pending');

INSERT INTO Rel_Incharge_Worker_ProductionStage
VALUES (103,203,6,'Pending');

-------------------------------------------------
-- REL_PRODUCTIONSTAGE_MACHINERY
-------------------------------------------------

INSERT INTO Rel_ProductionStage_Machinery
VALUES (1,2,10,2200);

INSERT INTO Rel_ProductionStage_Machinery
VALUES (2,1,18,2700);

INSERT INTO Rel_ProductionStage_Machinery
VALUES (3,3,12,2160);

INSERT INTO Rel_ProductionStage_Machinery
VALUES (4,4,8,960);

INSERT INTO Rel_ProductionStage_Machinery
VALUES (5,6,6,540);

INSERT INTO Rel_ProductionStage_Machinery
VALUES (6,5,7,420);

-------------------------------------------------
-- REL_MACHINERY_COSTING
-------------------------------------------------

INSERT INTO Rel_Machinery_Costing VALUES (1,1,2700);
INSERT INTO Rel_Machinery_Costing VALUES (2,2,2200);
INSERT INTO Rel_Machinery_Costing VALUES (3,3,2160);
INSERT INTO Rel_Machinery_Costing VALUES (4,4,960);
INSERT INTO Rel_Machinery_Costing VALUES (5,5,420);
INSERT INTO Rel_Machinery_Costing VALUES (6,6,540);

-------------------------------------------------
-- REL_COSTING_ORDER
-------------------------------------------------

INSERT INTO Rel_Costing_Order VALUES (1,1);
INSERT INTO Rel_Costing_Order VALUES (2,2);
INSERT INTO Rel_Costing_Order VALUES (3,3);
INSERT INTO Rel_Costing_Order VALUES (4,4);
INSERT INTO Rel_Costing_Order VALUES (5,5);
INSERT INTO Rel_Costing_Order VALUES (6,6);

-------------------------------------------------
-- REL_ORDER_MATERIAL
-------------------------------------------------

INSERT INTO Rel_Order_Material VALUES (1,1);
INSERT INTO Rel_Order_Material VALUES (2,2);
INSERT INTO Rel_Order_Material VALUES (3,3);
INSERT INTO Rel_Order_Material VALUES (4,4);
INSERT INTO Rel_Order_Material VALUES (5,5);
INSERT INTO Rel_Order_Material VALUES (6,6);

-------------------------------------------------
-- REL_PRODUCTIONSTAGE_INSPECTION
-------------------------------------------------

INSERT INTO Rel_ProductionStage_Inspection VALUES (1,1);
INSERT INTO Rel_ProductionStage_Inspection VALUES (2,2);
INSERT INTO Rel_ProductionStage_Inspection VALUES (3,3);
INSERT INTO Rel_ProductionStage_Inspection VALUES (4,4);
INSERT INTO Rel_ProductionStage_Inspection VALUES (5,5);
INSERT INTO Rel_ProductionStage_Inspection VALUES (6,6);

-------------------------------------------------
-- REL_INSPECTION_FINALPRODUCT
-------------------------------------------------

INSERT INTO Rel_Inspection_FinalProduct VALUES (1,1);
INSERT INTO Rel_Inspection_FinalProduct VALUES (2,2);
INSERT INTO Rel_Inspection_FinalProduct VALUES (3,3);
INSERT INTO Rel_Inspection_FinalProduct VALUES (4,4);
INSERT INTO Rel_Inspection_FinalProduct VALUES (5,5);
INSERT INTO Rel_Inspection_FinalProduct VALUES (6,6);

-------------------------------------------------
-- REL_FINALPRODUCT_PACKAGING
-------------------------------------------------

INSERT INTO Rel_FinalProduct_Packaging VALUES (1,1);
INSERT INTO Rel_FinalProduct_Packaging VALUES (2,2);
INSERT INTO Rel_FinalProduct_Packaging VALUES (3,3);
INSERT INTO Rel_FinalProduct_Packaging VALUES (4,4);
INSERT INTO Rel_FinalProduct_Packaging VALUES (5,5);
INSERT INTO Rel_FinalProduct_Packaging VALUES (6,6);

-------------------------------------------------
-- REL_PACKAGING_SHIPMENT
-------------------------------------------------

INSERT INTO Rel_Packaging_Shipment VALUES (1,1);
INSERT INTO Rel_Packaging_Shipment VALUES (2,2);
INSERT INTO Rel_Packaging_Shipment VALUES (3,3);
INSERT INTO Rel_Packaging_Shipment VALUES (4,4);
INSERT INTO Rel_Packaging_ShipMENT VALUES (5,5);
INSERT INTO Rel_Packaging_Shipment VALUES (6,6);

-------------------------------------------------
-- REL_SHIPMENT_BUYER
-------------------------------------------------

INSERT INTO Rel_Shipment_Buyer VALUES (1,1);
INSERT INTO Rel_Shipment_Buyer VALUES (2,2);
INSERT INTO Rel_Shipment_Buyer VALUES (3,3);
INSERT INTO Rel_Shipment_Buyer VALUES (4,4);
INSERT INTO Rel_Shipment_Buyer VALUES (5,5);
INSERT INTO Rel_Shipment_Buyer VALUES (6,6);

-------------------------------------------------
-- REL_BUYER_PAYMENT
-------------------------------------------------

INSERT INTO Rel_Buyer_Payment VALUES (1,1);
INSERT INTO Rel_Buyer_Payment VALUES (2,2);
INSERT INTO Rel_Buyer_Payment VALUES (3,3);
INSERT INTO Rel_Buyer_Payment VALUES (4,4);
INSERT INTO Rel_Buyer_Payment VALUES (5,5);
INSERT INTO Rel_Buyer_Payment VALUES (6,6);

-------------------------------------------------
-- REL_BUYER_ORDER
-------------------------------------------------

INSERT INTO Rel_Buyer_Order VALUES (1,1);
INSERT INTO Rel_Buyer_Order VALUES (2,2);
INSERT INTO Rel_Buyer_Order VALUES (3,3);
INSERT INTO Rel_Buyer_Order VALUES (4,4);
INSERT INTO Rel_Buyer_Order VALUES (5,5);
INSERT INTO Rel_Buyer_Order VALUES (6,6);

-------------------------------------------------
-- REL_COSTING_PAYMENT
-------------------------------------------------

INSERT INTO Rel_Costing_Payment VALUES
(1,1,85000);

INSERT INTO Rel_Costing_Payment VALUES
(2,2,120000);

INSERT INTO Rel_Costing_Payment VALUES
(3,3,76000);

INSERT INTO Rel_Costing_Payment VALUES
(4,4,145000);

INSERT INTO Rel_Costing_Payment VALUES
(5,5,68000);

INSERT INTO Rel_Costing_Payment VALUES
(6,6,170000);

-------------------------------------------------
-- REL_PAYMENT_ACCOUNTS
-------------------------------------------------

INSERT INTO Rel_Payment_Accounts VALUES (1,1);
INSERT INTO Rel_Payment_Accounts VALUES (2,2);
INSERT INTO Rel_Payment_Accounts VALUES (3,3);
INSERT INTO Rel_Payment_Accounts VALUES (4,4);
INSERT INTO Rel_Payment_Accounts VALUES (5,5);
INSERT INTO Rel_Payment_Accounts VALUES (6,6);

-------------------------------------------------
-- REL_ACCOUNTS_EMPLOYEE
-------------------------------------------------

INSERT INTO Rel_Accounts_Employee
VALUES (1,101,'Incharge',65000,50000);

INSERT INTO Rel_Accounts_Employee
VALUES (2,102,'Incharge',65000,50000);

INSERT INTO Rel_Accounts_Employee
VALUES (3,103,'Incharge',65000,50000);

INSERT INTO Rel_Accounts_Employee
VALUES (4,201,'Worker',35000,25000);

INSERT INTO Rel_Accounts_Employee
VALUES (5,202,'Worker',35000,25000);

INSERT INTO Rel_Accounts_Employee
VALUES (6,203,'Worker',35000,25000);

-------------------------------------------------
-- REL_COSTING_BOM
-------------------------------------------------

INSERT INTO Rel_Costing_BOM VALUES (1,1);
INSERT INTO Rel_Costing_BOM VALUES (2,2);
INSERT INTO Rel_Costing_BOM VALUES (3,3);
INSERT INTO Rel_Costing_BOM VALUES (4,4);
INSERT INTO Rel_Costing_BOM VALUES (5,5);
INSERT INTO Rel_Costing_BOM VALUES (6,6);

-------------------------------------------------
-- REL_ACCOUNTS_BOM
-------------------------------------------------

INSERT INTO Rel_Accounts_BOM VALUES (1,1);
INSERT INTO Rel_Accounts_BOM VALUES (2,2);
INSERT INTO Rel_Accounts_BOM VALUES (3,3);
INSERT INTO Rel_Accounts_BOM VALUES (4,4);
INSERT INTO Rel_Accounts_BOM VALUES (5,5);
INSERT INTO Rel_Accounts_BOM VALUES (6,6);


-------------------------------------------------
-- REL_ACCOUNTS_SUPPLIER
-------------------------------------------------

INSERT INTO Rel_Accounts_Supplier VALUES (1,1);
INSERT INTO Rel_Accounts_Supplier VALUES (2,2);
INSERT INTO Rel_Accounts_Supplier VALUES (3,3);
INSERT INTO Rel_Accounts_Supplier VALUES (4,4);
INSERT INTO Rel_Accounts_Supplier VALUES (5,5);
INSERT INTO Rel_Accounts_Supplier VALUES (6,6);

-------------------------------------------------
-- REL_SUPPLIER_BOM_MATERIAL
-------------------------------------------------

INSERT INTO Rel_Supplier_BOM_Material
VALUES (1,1,1,7,1000);

INSERT INTO Rel_Supplier_BOM_Material
VALUES (2,2,2,10,800);

INSERT INTO Rel_Supplier_BOM_Material
VALUES (3,3,3,5,150);

INSERT INTO Rel_Supplier_BOM_Material
VALUES (4,4,4,3,500);

INSERT INTO Rel_Supplier_BOM_Material
VALUES (5,5,5,4,1200);

INSERT INTO Rel_Supplier_BOM_Material
VALUES (6,6,6,6,700);

-------------------------------------------------
-- REL_ORDER_ORDERSTYLE
-------------------------------------------------

INSERT INTO Rel_Order_OrderStyle VALUES (1,1,1000);
INSERT INTO Rel_Order_OrderStyle VALUES (2,2,800);
INSERT INTO Rel_Order_OrderStyle VALUES (3,3,1500);
INSERT INTO Rel_Order_OrderStyle VALUES (4,4,500);
INSERT INTO Rel_Order_OrderStyle VALUES (5,5,1200);
INSERT INTO Rel_Order_OrderStyle VALUES (6,6,700);
