CREATE OR REPLACE TYPE ADDRESS_OBJ AS OBJECT (
    Street VARCHAR2(100),
    City VARCHAR2(50),
    Country VARCHAR2(50)
);
/

CREATE TABLE Buyer_Address_Details (
    Buyer_ID NUMBER(5) PRIMARY KEY,
    Address_Obj ADDRESS_OBJ,
    CONSTRAINT FK_Buyer_Address_Details
        FOREIGN KEY (Buyer_ID) REFERENCES Buyer(Buyer_ID)
);
/

INSERT INTO Buyer_Address_Details (Buyer_ID, Address_Obj)
VALUES (1, ADDRESS_OBJ('12 Halting Road', 'Dhaka', 'Bangladesh'));

INSERT INTO Buyer_Address_Details (Buyer_ID, Address_Obj)
VALUES (2, ADDRESS_OBJ('45 Berlin Avenue', 'Berlin', 'Germany'));
/

CREATE OR REPLACE VIEW V_PRODUCTION_STATUS AS
SELECT
    o.Order_ID,
    o.Description,
    os.Style_Name,
    ps.Stage_Name,
    ps.Stage_Progress,
    i.Passed_Quantity,
    fp.Lot_Number,
    s.Tracking_Number
FROM Orders o
LEFT JOIN Rel_Order_OrderStyle roos ON roos.Order_ID = o.Order_ID
LEFT JOIN Order_Style os ON os.Order_ID = roos.Order_ID AND os.Style_ID = roos.Style_ID
LEFT JOIN Rel_ProductionStage_Inspection rpsi ON rpsi.Stage_ID = 1
LEFT JOIN Inspection i ON i.Inspection_ID = rpsi.Inspection_ID
LEFT JOIN Rel_Inspection_FinalProduct rifp ON rifp.Inspection_ID = i.Inspection_ID
LEFT JOIN Final_Product fp ON fp.Final_Product_ID = rifp.Final_Product_ID
LEFT JOIN Rel_FinalProduct_Packaging rfpp ON rfpp.Final_Product_ID = fp.Final_Product_ID
LEFT JOIN Packaging p ON p.Package_ID = rfpp.Package_ID
LEFT JOIN Rel_Packaging_Shipment rps ON rps.Package_ID = p.Package_ID
LEFT JOIN Shipment s ON s.Shipment_ID = rps.Shipment_ID
LEFT JOIN Rel_ProductionStage_Machinery rpstm ON rpstm.Stage_ID = 1
LEFT JOIN Production_Stage ps ON ps.Stage_ID = rpstm.Stage_ID
ORDER BY o.Order_ID;
/

CREATE OR REPLACE FUNCTION GET_ORDER_COST(p_order_id IN NUMBER) RETURN NUMBER IS
    v_total_cost NUMBER(12,2) := 0;
BEGIN
    SELECT NVL(SUM(c.Final_Bill), 0)
      INTO v_total_cost
      FROM Rel_Costing_Order rco
      JOIN Costing c ON c.Costing_ID = rco.Costing_ID
     WHERE rco.Order_ID = p_order_id;

    RETURN v_total_cost;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN 0;
END;
/

CREATE TABLE Production_Summary_Log (
    Stage_ID NUMBER(5),
    Stage_Name VARCHAR2(50),
    Stage_Progress VARCHAR2(30),
    Assigned_Workers NUMBER(5),
    Generated_At DATE DEFAULT SYSDATE
);
/

CREATE OR REPLACE PROCEDURE GENERATE_PRODUCTION_SUMMARY AS
    CURSOR stage_cursor IS
        SELECT Stage_ID, Stage_Name, Stage_Progress, Assigned_Workers
        FROM Production_Stage
        ORDER BY Stage_ID;
BEGIN
    DELETE FROM Production_Summary_Log;

    FOR rec IN stage_cursor LOOP
        INSERT INTO Production_Summary_Log (Stage_ID, Stage_Name, Stage_Progress, Assigned_Workers, Generated_At)
        VALUES (rec.Stage_ID, rec.Stage_Name, rec.Stage_Progress, rec.Assigned_Workers, SYSDATE);
    END LOOP;
END;
/

CREATE OR REPLACE PROCEDURE UPDATE_STAGE_PROGRESS(
    p_stage_id IN NUMBER,
    p_progress IN NUMBER
) AS
BEGIN
    IF p_stage_id IS NULL OR p_progress < 0 OR p_progress > 100 THEN
        RAISE_APPLICATION_ERROR(-20010, 'Stage progress must be between 0 and 100.');
    END IF;

    UPDATE Production_Stage
       SET Stage_Progress = CASE
                               WHEN p_progress = 0 THEN 'Pending'
                               WHEN p_progress < 100 THEN 'In Progress'
                               ELSE 'Completed'
                           END
     WHERE Stage_ID = p_stage_id;

    IF SQL%ROWCOUNT = 0 THEN
        RAISE_APPLICATION_ERROR(-20011, 'Stage not found.');
    END IF;
END;
/

CREATE OR REPLACE PROCEDURE ADD_PAYMENT(
    p_payment_id IN NUMBER,
    p_total_amount IN NUMBER,
    p_paid_amount IN NUMBER,
    p_payment_method IN VARCHAR2,
    p_payment_date IN DATE
) AS
BEGIN
    IF p_paid_amount > p_total_amount THEN
        RAISE_APPLICATION_ERROR(-20001, 'Paid amount cannot exceed total amount.');
    END IF;

    INSERT INTO Payment (
        Payment_ID,
        Total_Amount,
        Paid_Amount,
        Remaining_Amount,
        Payment_Method,
        Payment_Date
    ) VALUES (
        p_payment_id,
        p_total_amount,
        p_paid_amount,
        p_total_amount - p_paid_amount,
        p_payment_method,
        p_payment_date
    );
EXCEPTION
    WHEN OTHERS THEN
        RAISE;
END;
/

COMMIT;
