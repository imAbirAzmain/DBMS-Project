--------------------------------------------------------
-- EMPLOYEE
--------------------------------------------------------

CREATE TABLE Employee
(
    Employee_ID     NUMBER(5)      PRIMARY KEY,
    Password        VARCHAR2(100)  NOT NULL,
    Position        VARCHAR2(20)   NOT NULL,
    Salary          NUMBER(10,2)   NOT NULL,
    Status          VARCHAR2(20)   NOT NULL,
    Last_Login      DATE
);

--------------------------------------------------------
-- INCHARGE
--------------------------------------------------------

CREATE TABLE Incharge
(
    Employee_ID         NUMBER(5),
    Name                VARCHAR2(100) NOT NULL,
    Operating_Stage     VARCHAR2(50),
    Email               VARCHAR2(100) UNIQUE,
    Address             VARCHAR2(200),

    CONSTRAINT PK_Incharge
        PRIMARY KEY (Employee_ID),

    CONSTRAINT FK_Incharge_Employee
        FOREIGN KEY (Employee_ID)
        REFERENCES Employee(Employee_ID)
);

--------------------------------------------------------
-- INCHARGE CONTACT (Multivalued Attribute)
--------------------------------------------------------

CREATE TABLE Incharge_Contact
(
    Employee_ID         NUMBER(5),
    Contact_Number      VARCHAR2(20),

    CONSTRAINT PK_Incharge_Contact
        PRIMARY KEY(Employee_ID, Contact_Number),

    CONSTRAINT FK_InchargeContact_Incharge
        FOREIGN KEY(Employee_ID)
        REFERENCES Incharge(Employee_ID)
);

--------------------------------------------------------
-- WORKER
--------------------------------------------------------

CREATE TABLE Worker
(
    Employee_ID     NUMBER(5),
    Name            VARCHAR2(100) NOT NULL,
    Address         VARCHAR2(200),
    Grade           VARCHAR2(10),
    Email           VARCHAR2(100) UNIQUE,

    CONSTRAINT PK_Worker
        PRIMARY KEY(Employee_ID),

    CONSTRAINT FK_Worker_Employee
        FOREIGN KEY(Employee_ID)
        REFERENCES Employee(Employee_ID)
);

--------------------------------------------------------
-- WORKER CONTACT (Multivalued Attribute)
--------------------------------------------------------

CREATE TABLE Worker_Contact
(
    Employee_ID         NUMBER(5),
    Contact_Number      VARCHAR2(20),

    CONSTRAINT PK_Worker_Contact
        PRIMARY KEY(Employee_ID, Contact_Number),

    CONSTRAINT FK_WorkerContact_Worker
        FOREIGN KEY(Employee_ID)
        REFERENCES Worker(Employee_ID)
);

--------------------------------------------------------
-- PRODUCTION_STAGE
--------------------------------------------------------

CREATE TABLE Production_Stage
(
    Stage_ID            NUMBER(5),
    Stage_Name          VARCHAR2(50) NOT NULL,
    Stage_Progress      VARCHAR2(30),
    Assigned_Workers    NUMBER(5),
    Start_Date          DATE,
    End_Date            DATE,

    CONSTRAINT PK_Production_Stage
        PRIMARY KEY(Stage_ID),

    CONSTRAINT CHK_Stage_Dates
        CHECK (End_Date >= Start_Date)
);

--------------------------------------------------------
-- MACHINERY
--------------------------------------------------------

CREATE TABLE Machinery
(
    Machine_ID          NUMBER(5),
    Name                VARCHAR2(100) NOT NULL,
    Type                VARCHAR2(50),
    Cost_Per_Unit       NUMBER(10,2),
    Quantity            NUMBER(5),

    CONSTRAINT PK_Machinery
        PRIMARY KEY(Machine_ID),

    CONSTRAINT CHK_Machine_Cost
        CHECK (Cost_Per_Unit >= 0),

    CONSTRAINT CHK_Machine_Qty
        CHECK (Quantity >= 0)
);

--------------------------------------------------------
-- COSTING
--------------------------------------------------------

CREATE TABLE Costing
(
    Costing_ID          NUMBER(5),
    Final_Bill          NUMBER(12,2) NOT NULL,

    CONSTRAINT PK_Costing
        PRIMARY KEY(Costing_ID),

    CONSTRAINT CHK_Final_Bill
        CHECK (Final_Bill >= 0)
);

--------------------------------------------------------
-- ORDERS
--------------------------------------------------------

CREATE TABLE Orders
(
    Order_ID                NUMBER(5),
    Description             VARCHAR2(250),
    Order_Date              DATE NOT NULL,
    Estimate_Delivery       DATE,

    CONSTRAINT PK_Orders
        PRIMARY KEY(Order_ID),

    CONSTRAINT CHK_Order_Delivery
        CHECK (Estimate_Delivery >= Order_Date)
);

--------------------------------------------------------
-- MATERIAL
--------------------------------------------------------

CREATE TABLE Material
(
    Material_ID             NUMBER(5),
    Name                    VARCHAR2(100) NOT NULL,
    Type                    VARCHAR2(50),
    Unit_Of_Measure         VARCHAR2(30),
    Unit_Price              NUMBER(10,2),

    CONSTRAINT PK_Material
        PRIMARY KEY(Material_ID),

    CONSTRAINT CHK_Unit_Price
        CHECK (Unit_Price >= 0)
);

--------------------------------------------------------
-- INSPECTION
--------------------------------------------------------

CREATE TABLE Inspection
(
    Inspection_ID      NUMBER(5),
    Passed_Quantity    NUMBER(8),
    Failed_Quantity    NUMBER(8),
    Remarks            VARCHAR2(200),

    CONSTRAINT PK_Inspection
        PRIMARY KEY (Inspection_ID),

    CONSTRAINT CHK_Passed_Qty
        CHECK (Passed_Quantity >= 0),

    CONSTRAINT CHK_Failed_Qty
        CHECK (Failed_Quantity >= 0)
);

--------------------------------------------------------
-- FINAL_PRODUCT
--------------------------------------------------------

CREATE TABLE Final_Product
(
    Final_Product_ID       NUMBER(5),
    Grade                  VARCHAR2(10),
    Lot_Number             VARCHAR2(50),
    Date_Of_Completion     DATE,

    CONSTRAINT PK_Final_Product
        PRIMARY KEY (Final_Product_ID)
);

--------------------------------------------------------
-- PACKAGING
--------------------------------------------------------

CREATE TABLE Packaging
(
    Package_ID             NUMBER(5),
    Package_Date           DATE,
    Weight_Per_Pack        NUMBER(8,2),
    Quantity_Per_Pack      NUMBER(8),
    Total_Package          NUMBER(8),
    Type                   VARCHAR2(30),

    CONSTRAINT PK_Packaging
        PRIMARY KEY (Package_ID),

    CONSTRAINT CHK_Weight
        CHECK (Weight_Per_Pack >= 0),

    CONSTRAINT CHK_Qty_Per_Pack
        CHECK (Quantity_Per_Pack >= 0),

    CONSTRAINT CHK_Total_Package
        CHECK (Total_Package >= 0)
);

--------------------------------------------------------
-- SHIPMENT
--------------------------------------------------------

CREATE TABLE Shipment
(
    Shipment_ID            NUMBER(5),
    Tracking_Number        VARCHAR2(100) UNIQUE,
    Estimated_Delivery     DATE,
    Destination            VARCHAR2(100),
    Shipped_Date           DATE,

    CONSTRAINT PK_Shipment
        PRIMARY KEY (Shipment_ID),

    CONSTRAINT CHK_Shipment_Date
        CHECK (Estimated_Delivery >= Shipped_Date)
);

--------------------------------------------------------
-- BUYER
--------------------------------------------------------

CREATE TABLE Buyer
(
    Buyer_ID           NUMBER(5),
    Name               VARCHAR2(100) NOT NULL,
    Brand              VARCHAR2(100),
    Address            VARCHAR2(200),
    Account_No         VARCHAR2(50) UNIQUE,
    Email              VARCHAR2(100) UNIQUE,

    CONSTRAINT PK_Buyer
        PRIMARY KEY (Buyer_ID)
);

--------------------------------------------------------
-- BUYER_CONTACT (Multivalued Attribute)
--------------------------------------------------------

CREATE TABLE Buyer_Contact
(
    Buyer_ID           NUMBER(5),
    Contact_Number     VARCHAR2(20),

    CONSTRAINT PK_Buyer_Contact
        PRIMARY KEY (Buyer_ID, Contact_Number),

    CONSTRAINT FK_BuyerContact_Buyer
        FOREIGN KEY (Buyer_ID)
        REFERENCES Buyer(Buyer_ID)
);

--------------------------------------------------------
-- PAYMENT
--------------------------------------------------------

CREATE TABLE Payment
(
    Payment_ID          NUMBER(5),
    Total_Amount        NUMBER(12,2),
    Paid_Amount         NUMBER(12,2),
    Remaining_Amount    NUMBER(12,2),
    Payment_Method      VARCHAR2(30),
    Payment_Date        DATE,

    CONSTRAINT PK_Payment
        PRIMARY KEY (Payment_ID),

    CONSTRAINT CHK_Total_Amount
        CHECK (Total_Amount >= 0),

    CONSTRAINT CHK_Paid_Amount
        CHECK (Paid_Amount >= 0),

    CONSTRAINT CHK_Remaining_Amount
        CHECK (Remaining_Amount >= 0)
);

--------------------------------------------------------
-- ACCOUNTS
--------------------------------------------------------

CREATE TABLE Accounts
(
    Transaction_ID      NUMBER(5),
    Status              VARCHAR2(20),
    Amount              NUMBER(12,2),
    Transaction_Date    DATE,
    Associated_Bank     VARCHAR2(100),

    CONSTRAINT PK_Accounts
        PRIMARY KEY (Transaction_ID),

    CONSTRAINT CHK_Transaction_Status
        CHECK (Status IN ('Credited','Debited')),

    CONSTRAINT CHK_Transaction_Amount
        CHECK (Amount >= 0)
);

--------------------------------------------------------
-- BOM
--------------------------------------------------------

CREATE TABLE BOM
(
    BOM_ID                  NUMBER(5),
    Material_Description    VARCHAR2(250),
    Unit_Bill               NUMBER(12,2),
    Total_Bill              NUMBER(12,2),

    CONSTRAINT PK_BOM
        PRIMARY KEY (BOM_ID),

    CONSTRAINT CHK_Unit_Bill
        CHECK (Unit_Bill >= 0),

    CONSTRAINT CHK_Total_Bill
        CHECK (Total_Bill >= 0)
);

--------------------------------------------------------
-- SUPPLIER
--------------------------------------------------------

CREATE TABLE Supplier
(
    Supplier_ID         NUMBER(5),
    Name                VARCHAR2(100) NOT NULL,
    Address             VARCHAR2(200),
    Email               VARCHAR2(100) UNIQUE,

    CONSTRAINT PK_Supplier
        PRIMARY KEY (Supplier_ID)
);

--------------------------------------------------------
-- SUPPLIER_CONTACT (Multivalued Attribute)
--------------------------------------------------------

CREATE TABLE Supplier_Contact
(
    Supplier_ID         NUMBER(5),
    Contact_Number      VARCHAR2(20),

    CONSTRAINT PK_Supplier_Contact
        PRIMARY KEY (Supplier_ID, Contact_Number),

    CONSTRAINT FK_SupplierContact_Supplier
        FOREIGN KEY (Supplier_ID)
        REFERENCES Supplier(Supplier_ID)
);

--------------------------------------------------------
-- ORDER_STYLE (Weak Entity)
--------------------------------------------------------

CREATE TABLE Order_Style
(
    Order_ID            NUMBER(5),
    Style_ID            NUMBER(5),
    Style_Name          VARCHAR2(100),
    Color               VARCHAR2(30),
    Size                VARCHAR2(20),

    CONSTRAINT PK_Order_Style
        PRIMARY KEY (Order_ID, Style_ID),

    CONSTRAINT FK_OrderStyle_Order
        FOREIGN KEY (Order_ID)
        REFERENCES Orders(Order_ID)
);

--------------------------------------------------------
-- 1. REL_WORKER_PRODUCTIONSTAGE
--------------------------------------------------------

CREATE TABLE Rel_Worker_ProductionStage
(
    Employee_ID     NUMBER(5),
    Stage_ID        NUMBER(5),

    CONSTRAINT PK_Rel_Worker_ProductionStage
        PRIMARY KEY(Employee_ID, Stage_ID),

    CONSTRAINT FK_RWPS_Worker
        FOREIGN KEY(Employee_ID)
        REFERENCES Worker(Employee_ID),

    CONSTRAINT FK_RWPS_Stage
        FOREIGN KEY(Stage_ID)
        REFERENCES Production_Stage(Stage_ID)
);

--------------------------------------------------------
-- 2. Rel_Inch_Worker_Stage
-- (Aggregation Relationship)
--------------------------------------------------------

CREATE TABLE Rel_Inch_Worker_Stage
(
    Incharge_ID         NUMBER(5),
    Worker_ID           NUMBER(5),
    Stage_ID            NUMBER(5),
    Operation_Progress  VARCHAR2(50),

    CONSTRAINT PK_Rel_IWPS
        PRIMARY KEY(Incharge_ID, Worker_ID, Stage_ID),

    CONSTRAINT FK_IWPS_Incharge
        FOREIGN KEY(Incharge_ID)
        REFERENCES Incharge(Employee_ID),

    CONSTRAINT FK_IWPS_Worker
        FOREIGN KEY(Worker_ID)
        REFERENCES Worker(Employee_ID),

    CONSTRAINT FK_IWPS_Stage
        FOREIGN KEY(Stage_ID)
        REFERENCES Production_Stage(Stage_ID)
);

--------------------------------------------------------
-- 3. REL_PRODUCTIONSTAGE_MACHINERY
--------------------------------------------------------

CREATE TABLE Rel_ProductionStage_Machinery
(
    Stage_ID        NUMBER(5),
    Machine_ID      NUMBER(5),
    Used_Duration   NUMBER(8,2),
    Used_Cost       NUMBER(12,2),

    CONSTRAINT PK_Rel_Stage_Machinery
        PRIMARY KEY(Stage_ID, Machine_ID),

    CONSTRAINT FK_RSM_Stage
        FOREIGN KEY(Stage_ID)
        REFERENCES Production_Stage(Stage_ID),

    CONSTRAINT FK_RSM_Machinery
        FOREIGN KEY(Machine_ID)
        REFERENCES Machinery(Machine_ID),

    CONSTRAINT CHK_Used_Duration
        CHECK(Used_Duration >= 0),

    CONSTRAINT CHK_Used_Cost
        CHECK(Used_Cost >= 0)
);

--------------------------------------------------------
-- 4. REL_MACHINERY_COSTING
--------------------------------------------------------

CREATE TABLE Rel_Machinery_Costing
(
    Machine_ID              NUMBER(5),
    Costing_ID              NUMBER(5),
    Machinery_Total_Cost    NUMBER(12,2),

    CONSTRAINT PK_Rel_Machinery_Costing
        PRIMARY KEY(Machine_ID, Costing_ID),

    CONSTRAINT FK_RMC_Machinery
        FOREIGN KEY(Machine_ID)
        REFERENCES Machinery(Machine_ID),

    CONSTRAINT FK_RMC_Costing
        FOREIGN KEY(Costing_ID)
        REFERENCES Costing(Costing_ID),

    CONSTRAINT CHK_Machinery_Total_Cost
        CHECK(Machinery_Total_Cost >= 0)
);

--------------------------------------------------------
-- 5. REL_COSTING_ORDER
--------------------------------------------------------

CREATE TABLE Rel_Costing_Order
(
    Costing_ID      NUMBER(5),
    Order_ID        NUMBER(5),

    CONSTRAINT PK_Rel_Costing_Order
        PRIMARY KEY(Costing_ID, Order_ID),

    CONSTRAINT FK_RCO_Costing
        FOREIGN KEY(Costing_ID)
        REFERENCES Costing(Costing_ID),

    CONSTRAINT FK_RCO_Order
        FOREIGN KEY(Order_ID)
        REFERENCES Orders(Order_ID)
);

--------------------------------------------------------
-- 6. REL_ORDER_MATERIAL
--------------------------------------------------------

CREATE TABLE Rel_Order_Material
(
    Order_ID        NUMBER(5),
    Material_ID     NUMBER(5),

    CONSTRAINT PK_Rel_Order_Material
        PRIMARY KEY(Order_ID, Material_ID),

    CONSTRAINT FK_ROM_Order
        FOREIGN KEY(Order_ID)
        REFERENCES Orders(Order_ID),

    CONSTRAINT FK_ROM_Material
        FOREIGN KEY(Material_ID)
        REFERENCES Material(Material_ID)
);

--------------------------------------------------------
-- 7. REL_PRODUCTIONSTAGE_INSPECTION
--------------------------------------------------------

CREATE TABLE Rel_ProductionStage_Inspection
(
    Stage_ID            NUMBER(5),
    Inspection_ID       NUMBER(5),

    CONSTRAINT PK_Rel_Stage_Inspection
        PRIMARY KEY(Stage_ID, Inspection_ID),

    CONSTRAINT FK_RPSI_Stage
        FOREIGN KEY(Stage_ID)
        REFERENCES Production_Stage(Stage_ID),

    CONSTRAINT FK_RPSI_Inspection
        FOREIGN KEY(Inspection_ID)
        REFERENCES Inspection(Inspection_ID)
);

--------------------------------------------------------
-- 8. REL_INSPECTION_FINALPRODUCT
--------------------------------------------------------

CREATE TABLE Rel_Inspection_FinalProduct
(
    Inspection_ID          NUMBER(5),
    Final_Product_ID       NUMBER(5),

    CONSTRAINT PK_Rel_Inspection_Product
        PRIMARY KEY(Inspection_ID, Final_Product_ID),

    CONSTRAINT FK_RIFP_Inspection
        FOREIGN KEY(Inspection_ID)
        REFERENCES Inspection(Inspection_ID),

    CONSTRAINT FK_RIFP_Product
        FOREIGN KEY(Final_Product_ID)
        REFERENCES Final_Product(Final_Product_ID)
);

--------------------------------------------------------
-- 9. REL_FINALPRODUCT_PACKAGING
--------------------------------------------------------

CREATE TABLE Rel_FinalProduct_Packaging
(
    Final_Product_ID      NUMBER(5),
    Package_ID            NUMBER(5),

    CONSTRAINT PK_Rel_Product_Package
        PRIMARY KEY(Final_Product_ID, Package_ID),

    CONSTRAINT FK_RFPP_Product
        FOREIGN KEY(Final_Product_ID)
        REFERENCES Final_Product(Final_Product_ID),

    CONSTRAINT FK_RFPP_Package
        FOREIGN KEY(Package_ID)
        REFERENCES Packaging(Package_ID)
);

--------------------------------------------------------
-- 10. REL_PACKAGING_SHIPMENT
--------------------------------------------------------

CREATE TABLE Rel_Packaging_Shipment
(
    Package_ID         NUMBER(5),
    Shipment_ID        NUMBER(5),

    CONSTRAINT PK_Rel_Package_Shipment
        PRIMARY KEY(Package_ID, Shipment_ID),

    CONSTRAINT FK_RPS_Package
        FOREIGN KEY(Package_ID)
        REFERENCES Packaging(Package_ID),

    CONSTRAINT FK_RPS_Shipment
        FOREIGN KEY(Shipment_ID)
        REFERENCES Shipment(Shipment_ID)
);

--------------------------------------------------------
-- 11. REL_SHIPMENT_BUYER
--------------------------------------------------------

CREATE TABLE Rel_Shipment_Buyer
(
    Shipment_ID        NUMBER(5),
    Buyer_ID           NUMBER(5),

    CONSTRAINT PK_Rel_Shipment_Buyer
        PRIMARY KEY(Shipment_ID, Buyer_ID),

    CONSTRAINT FK_RSB_Shipment
        FOREIGN KEY(Shipment_ID)
        REFERENCES Shipment(Shipment_ID),

    CONSTRAINT FK_RSB_Buyer
        FOREIGN KEY(Buyer_ID)
        REFERENCES Buyer(Buyer_ID)
);

--------------------------------------------------------
-- 12. REL_BUYER_PAYMENT
--------------------------------------------------------

CREATE TABLE Rel_Buyer_Payment
(
    Buyer_ID           NUMBER(5),
    Payment_ID         NUMBER(5),

    CONSTRAINT PK_Rel_Buyer_Payment
        PRIMARY KEY(Buyer_ID, Payment_ID),

    CONSTRAINT FK_RBP_Buyer
        FOREIGN KEY(Buyer_ID)
        REFERENCES Buyer(Buyer_ID),

    CONSTRAINT FK_RBP_Payment
        FOREIGN KEY(Payment_ID)
        REFERENCES Payment(Payment_ID)
);

--------------------------------------------------------
-- 13. REL_BUYER_ORDER
--------------------------------------------------------

CREATE TABLE Rel_Buyer_Order
(
    Order_ID        NUMBER(5),
    Buyer_ID        NUMBER(5),

    CONSTRAINT PK_Rel_Buyer_Order
        PRIMARY KEY (Order_ID, Buyer_ID),

    CONSTRAINT FK_RBO_Order
        FOREIGN KEY (Order_ID)
        REFERENCES Orders(Order_ID),

    CONSTRAINT FK_RBO_Buyer
        FOREIGN KEY (Buyer_ID)
        REFERENCES Buyer(Buyer_ID)
);

--------------------------------------------------------
-- 14. REL_COSTING_PAYMENT
--------------------------------------------------------

CREATE TABLE Rel_Costing_Payment
(
    Costing_ID          NUMBER(5),
    Payment_ID          NUMBER(5),
    Profit_Margin       NUMBER(12,2),

    CONSTRAINT PK_Rel_Costing_Payment
        PRIMARY KEY (Costing_ID, Payment_ID),

    CONSTRAINT FK_RCP_Costing
        FOREIGN KEY (Costing_ID)
        REFERENCES Costing(Costing_ID),

    CONSTRAINT FK_RCP_Payment
        FOREIGN KEY (Payment_ID)
        REFERENCES Payment(Payment_ID),

    CONSTRAINT CHK_Profit_Margin
        CHECK (Profit_Margin >= 0)
);

--------------------------------------------------------
-- 15. REL_PAYMENT_ACCOUNTS
--------------------------------------------------------

CREATE TABLE Rel_Payment_Accounts
(
    Payment_ID          NUMBER(5),
    Transaction_ID      NUMBER(5),

    CONSTRAINT PK_Rel_Payment_Accounts
        PRIMARY KEY (Payment_ID, Transaction_ID),

    CONSTRAINT FK_RPA_Payment
        FOREIGN KEY (Payment_ID)
        REFERENCES Payment(Payment_ID),

    CONSTRAINT FK_RPA_Accounts
        FOREIGN KEY (Transaction_ID)
        REFERENCES Accounts(Transaction_ID)
);

--------------------------------------------------------
-- 16. REL_ACCOUNTS_EMPLOYEE
--------------------------------------------------------

CREATE TABLE Rel_Accounts_Employee
(
    Transaction_ID      NUMBER(5),
    Employee_ID         NUMBER(5),
    Position            VARCHAR2(30),
    Max_Salary          NUMBER(10,2),
    Min_Salary          NUMBER(10,2),

    CONSTRAINT PK_Rel_Accounts_Employee
        PRIMARY KEY (Transaction_ID, Employee_ID),

    CONSTRAINT FK_RAE_Accounts
        FOREIGN KEY (Transaction_ID)
        REFERENCES Accounts(Transaction_ID),

    CONSTRAINT FK_RAE_Employee
        FOREIGN KEY (Employee_ID)
        REFERENCES Employee(Employee_ID),

    CONSTRAINT CHK_Max_Salary
        CHECK (Max_Salary >= 0),

    CONSTRAINT CHK_Min_Salary
        CHECK (Min_Salary >= 0),

    CONSTRAINT CHK_Salary_Range
        CHECK (Max_Salary >= Min_Salary)
);

--------------------------------------------------------
-- 17. REL_COSTING_BOM
--------------------------------------------------------

CREATE TABLE Rel_Costing_BOM
(
    Costing_ID      NUMBER(5),
    BOM_ID          NUMBER(5),

    CONSTRAINT PK_Rel_Costing_BOM
        PRIMARY KEY (Costing_ID, BOM_ID),

    CONSTRAINT FK_RCB_Costing
        FOREIGN KEY (Costing_ID)
        REFERENCES Costing(Costing_ID),

    CONSTRAINT FK_RCB_BOM
        FOREIGN KEY (BOM_ID)
        REFERENCES BOM(BOM_ID)
);

--------------------------------------------------------
-- 18. REL_ACCOUNTS_BOM
--------------------------------------------------------

CREATE TABLE Rel_Accounts_BOM
(
    Transaction_ID      NUMBER(5),
    BOM_ID              NUMBER(5),

    CONSTRAINT PK_Rel_Accounts_BOM
        PRIMARY KEY (Transaction_ID, BOM_ID),

    CONSTRAINT FK_RAB_Accounts
        FOREIGN KEY (Transaction_ID)
        REFERENCES Accounts(Transaction_ID),

    CONSTRAINT FK_RAB_BOM
        FOREIGN KEY (BOM_ID)
        REFERENCES BOM(BOM_ID)
);

--------------------------------------------------------
-- 19. REL_ACCOUNTS_SUPPLIER
--------------------------------------------------------

CREATE TABLE Rel_Accounts_Supplier
(
    Transaction_ID      NUMBER(5),
    Supplier_ID         NUMBER(5),

    CONSTRAINT PK_Rel_Accounts_Supplier
        PRIMARY KEY (Transaction_ID, Supplier_ID),

    CONSTRAINT FK_RAS_Accounts
        FOREIGN KEY (Transaction_ID)
        REFERENCES Accounts(Transaction_ID),

    CONSTRAINT FK_RAS_Supplier
        FOREIGN KEY (Supplier_ID)
        REFERENCES Supplier(Supplier_ID)
);

--------------------------------------------------------
-- 20. REL_SUPPLIER_BOM_MATERIAL
-- (TERNARY RELATIONSHIP)
--------------------------------------------------------

CREATE TABLE Rel_Supplier_BOM_Material
(
    Supplier_ID         NUMBER(5),
    BOM_ID              NUMBER(5),
    Material_ID         NUMBER(5),
    Time_Required       NUMBER(5),
    Quantity            NUMBER(10),

    CONSTRAINT PK_Rel_Supplier_BOM_Material
        PRIMARY KEY
        (
            Supplier_ID,
            BOM_ID,
            Material_ID
        ),

    CONSTRAINT FK_RSBM_Supplier
        FOREIGN KEY (Supplier_ID)
        REFERENCES Supplier(Supplier_ID),

    CONSTRAINT FK_RSBM_BOM
        FOREIGN KEY (BOM_ID)
        REFERENCES BOM(BOM_ID),

    CONSTRAINT FK_RSBM_Material
        FOREIGN KEY (Material_ID)
        REFERENCES Material(Material_ID),

    CONSTRAINT CHK_Time_Required
        CHECK (Time_Required >= 0),

    CONSTRAINT CHK_Supplier_Quantity
        CHECK (Quantity >= 0)
);

--------------------------------------------------------
-- 21. REL_ORDER_ORDERSTYLE
-- (Identifying Relationship for Weak Entity)
--------------------------------------------------------

CREATE TABLE Rel_Order_OrderStyle
(
    Order_ID        NUMBER(5),
    Style_ID        NUMBER(5),
    Quantity        NUMBER(8),

    CONSTRAINT PK_Rel_Order_OrderStyle
        PRIMARY KEY (Order_ID, Style_ID),

    CONSTRAINT FK_ROOS_OrderStyle
        FOREIGN KEY (Order_ID, Style_ID)
        REFERENCES Order_Style(Order_ID, Style_ID),

    CONSTRAINT CHK_OrderStyle_Quantity
        CHECK (Quantity > 0)
);

