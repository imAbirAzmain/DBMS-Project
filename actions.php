<?php
require_once __DIR__ . '/config/auth.php';

header('Content-Type: application/json; charset=utf-8');
garments_require_incharge('login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !garments_verify_csrf($_POST['csrf_token'] ?? null)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Invalid request token. Refresh the page and try again.']);
    exit;
}

function action_value(string $key, bool $required = true): ?string
{
    $value = trim((string) ($_POST[$key] ?? ''));
    if ($required && $value === '') {
        throw new InvalidArgumentException(ucwords(str_replace('_', ' ', $key)) . ' is required.');
    }
    return $value === '' ? null : $value;
}

function action_id(string $key): int
{
    $value = action_value($key);
    if (!preg_match('/(\d+)$/', $value, $matches)) {
        throw new InvalidArgumentException(ucwords(str_replace('_', ' ', $key)) . ' must be a number.');
    }
    return (int) $matches[1];
}

function action_date(string $key, bool $required = true): ?string
{
    $value = action_value($key, $required);
    if ($value !== null && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
        throw new InvalidArgumentException('Invalid date.');
    }
    return $value;
}

function action_run(string $sql, array $params): void
{
    $result = garments_db_execute($sql, $params);
    if (!$result['ok']) {
        throw new RuntimeException($result['error']);
    }
}

try {
    $resource = action_value('resource');
    switch ($resource) {
        case 'accounts':
            action_run('INSERT INTO Accounts (Transaction_ID, Status, Amount, Transaction_Date, Associated_Bank) VALUES (:id, :status, :amount, TO_DATE(:date_value, \'YYYY-MM-DD\'), :bank)', [
                'id' => action_id('transaction_id'), 'status' => action_value('status'), 'amount' => action_value('amount'), 'date_value' => action_date('date'), 'bank' => action_value('associated_bank')]);
            break;
        case 'bom':
            action_run('INSERT INTO BOM (BOM_ID, Material_Description, Unit_Bill, Total_Bill) VALUES (:id, :description, :unit_bill, :total_bill)', [
                'id' => action_id('bom_id'), 'description' => action_value('material_description'), 'unit_bill' => action_value('unit_bill'), 'total_bill' => action_value('total_bill')]);
            break;
        case 'buyer':
            $id = action_id('buyer_id');
            action_run('INSERT INTO Buyer (Buyer_ID, Name, Brand, Address, Account_No, Email) VALUES (:id, :name, :brand, :address, :account_no, :email)', ['id' => $id, 'name' => action_value('name'), 'brand' => action_value('brand'), 'address' => action_value('address'), 'account_no' => action_value('account_no'), 'email' => action_value('email')]);
            foreach (['contact_number_primary', 'contact_number_additional'] as $field) { if ($contact = action_value($field, false)) action_run('INSERT INTO Buyer_Contact (Buyer_ID, Contact_Number) VALUES (:id, :contact)', ['id' => $id, 'contact' => $contact]); }
            break;
        case 'final_product':
            $id = garments_db_next_id('Final_Product', 'Final_Product_ID');
            action_run('INSERT INTO Final_Product (Final_Product_ID, Grade, Lot_Number, Date_Of_Completion) VALUES (:id, :grade, :lot, TO_DATE(:date_value, \'YYYY-MM-DD\'))', ['id' => $id, 'grade' => action_value('grade'), 'lot' => action_value('lot_number'), 'date_value' => action_date('date_of_completion')]);
            action_run('INSERT INTO Rel_Inspection_FinalProduct (Inspection_ID, Final_Product_ID) VALUES (:inspection_id, :product_id)', ['inspection_id' => action_id('inspection_id'), 'product_id' => $id]);
            break;
        case 'inspection':
            $id = garments_db_next_id('Inspection', 'Inspection_ID');
            action_run('INSERT INTO Inspection (Inspection_ID, Passed_Quantity, Failed_Quantity, Remarks) VALUES (:id, :passed, :failed, :remarks)', ['id' => $id, 'passed' => action_value('passed_quantity'), 'failed' => action_value('failed_quantity'), 'remarks' => action_value('remarks', false)]);
            action_run('INSERT INTO Rel_ProductionStage_Inspection (Stage_ID, Inspection_ID) VALUES (:stage_id, :inspection_id)', ['stage_id' => action_id('stage_id'), 'inspection_id' => $id]);
            break;
        case 'machinery':
            action_run('INSERT INTO Machinery (Machine_ID, Name, Type, Quantity, Cost_Per_Unit) VALUES (:id, :name, :type, :quantity, :cost)', ['id' => action_id('machine_id'), 'name' => action_value('name'), 'type' => action_value('type'), 'quantity' => action_value('quantity'), 'cost' => action_value('cost_per_unit')]);
            break;
        case 'material':
            action_run('INSERT INTO Material (Material_ID, Name, Type, Unit_Of_Measure, Unit_Price) VALUES (:id, :name, :type, :unit, :price)', ['id' => action_id('material_id'), 'name' => action_value('name'), 'type' => action_value('type'), 'unit' => action_value('unit_of_measure'), 'price' => action_value('unit_price')]);
            break;
        case 'order_style':
            $orderId = action_id('order_id'); $styleId = action_id('style_id');
            action_run('INSERT INTO Order_Style (Order_ID, Style_ID, Style_Name, Color, Size_Value) VALUES (:order_id, :style_id, :name, :color, :size)', ['order_id' => $orderId, 'style_id' => $styleId, 'name' => action_value('style_name'), 'color' => action_value('color'), 'size' => action_value('size')]);
            action_run('INSERT INTO Rel_Order_OrderStyle (Order_ID, Style_ID, Quantity) VALUES (:order_id, :style_id, :quantity)', ['order_id' => $orderId, 'style_id' => $styleId, 'quantity' => action_value('quantity')]);
            break;
        case 'order':
            $id = action_id('order_id'); $buyerId = action_id('buyer_id');
            action_run('INSERT INTO Orders (Order_ID, Description, Order_Date, Estimate_Delivery) VALUES (:id, :description, TO_DATE(:order_date, \'YYYY-MM-DD\'), TO_DATE(:delivery_date, \'YYYY-MM-DD\'))', ['id' => $id, 'description' => action_value('description'), 'order_date' => action_date('order_date'), 'delivery_date' => action_date('estimate_delivery')]);
            action_run('INSERT INTO Rel_Buyer_Order (Order_ID, Buyer_ID) VALUES (:order_id, :buyer_id)', ['order_id' => $id, 'buyer_id' => $buyerId]);
            if (action_value('style_id', false)) { $_POST['order_id'] = (string) $id; $resource = 'order_style'; $orderId = $id; $styleId = action_id('style_id'); action_run('INSERT INTO Order_Style (Order_ID, Style_ID, Style_Name, Color, Size_Value) VALUES (:order_id, :style_id, :name, :color, :size)', ['order_id' => $orderId, 'style_id' => $styleId, 'name' => action_value('style_name'), 'color' => action_value('color'), 'size' => action_value('size')]); action_run('INSERT INTO Rel_Order_OrderStyle (Order_ID, Style_ID, Quantity) VALUES (:order_id, :style_id, :quantity)', ['order_id' => $orderId, 'style_id' => $styleId, 'quantity' => action_value('quantity')]); }
            break;
        case 'advanced_stage_progress':
            action_run('BEGIN UPDATE_STAGE_PROGRESS(:stage_id, :progress); END;', [
                'stage_id' => action_id('stage_id'),
                'progress' => action_value('progress'),
            ]);
            break;
        case 'advanced_generate_summary':
            action_run('BEGIN GENERATE_PRODUCTION_SUMMARY; END;', []);
            break;
        case 'advanced_payment_exception':
            $total = (float) action_value('total_amount');
            $paid = (float) action_value('paid_amount');
            if ($paid <= $total) {
                throw new InvalidArgumentException('For this exception demo, the paid amount must be greater than the total amount.');
            }
            action_run('BEGIN ADD_PAYMENT(:payment_id, :total_amount, :paid_amount, :payment_method, TO_DATE(:payment_date, \'YYYY-MM-DD\')); END;', [
                'payment_id' => action_id('payment_id'),
                'total_amount' => $total,
                'paid_amount' => $paid,
                'payment_method' => action_value('payment_method'),
                'payment_date' => action_date('payment_date'),
            ]);
            break;
        case 'packaging':
            $id = garments_db_next_id('Packaging', 'Package_ID');
            action_run('INSERT INTO Packaging (Package_ID, Package_Date, Type, Weight_Per_Pack, Quantity_Per_Pack, Total_Package) VALUES (:id, TO_DATE(:date_value, \'YYYY-MM-DD\'), :type, :weight, :quantity, :total)', ['id' => $id, 'date_value' => action_date('package_date'), 'type' => action_value('type'), 'weight' => action_value('weight_per_pack'), 'quantity' => action_value('quantity_per_pack'), 'total' => action_value('total_package')]);
            $productValue = action_value('final_product_id');
            $productId = preg_match('/(\d+)$/', $productValue, $matches)
                ? (int) $matches[1]
                : (int) ((garments_db_fetch_one('SELECT Final_Product_ID FROM Final_Product WHERE Lot_Number = :lot_number', ['lot_number' => $productValue])['FINAL_PRODUCT_ID'] ?? 0));
            if ($productId < 1) throw new InvalidArgumentException('Final product was not found.');
            action_run('INSERT INTO Rel_FinalProduct_Packaging (Final_Product_ID, Package_ID) VALUES (:product_id, :package_id)', ['product_id' => $productId, 'package_id' => $id]);
            break;
        case 'payment':
            $id = garments_db_next_id('Payment', 'Payment_ID');
            $total = (float) action_value('total_amount'); $paid = (float) action_value('paid_amount');
            if ($paid > $total) throw new InvalidArgumentException('Paid amount cannot exceed the total amount.');
            action_run('INSERT INTO Payment (Payment_ID, Total_Amount, Paid_Amount, Remaining_Amount, Payment_Method, Payment_Date) VALUES (:id, :total, :paid, :remaining, :method, TO_DATE(:date_value, \'YYYY-MM-DD\'))', ['id' => $id, 'total' => $total, 'paid' => $paid, 'remaining' => $total - $paid, 'method' => action_value('payment_method'), 'date_value' => action_date('payment_date')]);
            $buyer = garments_db_fetch_one('SELECT Buyer_ID FROM Rel_Buyer_Order WHERE Order_ID = :order_id FETCH FIRST 1 ROWS ONLY', ['order_id' => action_id('order_id')]);
            if (!$buyer) throw new InvalidArgumentException('The selected order is not linked to a buyer.');
            action_run('INSERT INTO Rel_Buyer_Payment (Buyer_ID, Payment_ID) VALUES (:buyer_id, :payment_id)', ['buyer_id' => $buyer['BUYER_ID'], 'payment_id' => $id]);
            break;
        case 'production':
            action_run('INSERT INTO Production_Stage (Stage_ID, Stage_Name, Stage_Progress, Assigned_Workers, Start_Date, End_Date) VALUES (:id, :name, :progress, :workers, TO_DATE(:start_date, \'YYYY-MM-DD\'), TO_DATE(:end_date, \'YYYY-MM-DD\'))', ['id' => action_id('stage_id'), 'name' => action_value('stage_name'), 'progress' => action_value('status'), 'workers' => action_value('assigned_workers'), 'start_date' => action_date('start_date'), 'end_date' => action_date('end_date', false)]);
            break;
        case 'shipment':
            $id = garments_db_next_id('Shipment', 'Shipment_ID');
            action_run('INSERT INTO Shipment (Shipment_ID, Tracking_Number, Destination, Shipped_Date, Estimated_Delivery) VALUES (:id, :tracking, :destination, TO_DATE(:shipped, \'YYYY-MM-DD\'), TO_DATE(:delivery, \'YYYY-MM-DD\'))', ['id' => $id, 'tracking' => action_value('tracking_number'), 'destination' => action_value('destination'), 'shipped' => action_date('shipped_date'), 'delivery' => action_date('estimated_delivery', false)]);
            action_run('INSERT INTO Rel_Packaging_Shipment (Package_ID, Shipment_ID) VALUES (:package_id, :shipment_id)', ['package_id' => action_id('package_id'), 'shipment_id' => $id]);
            action_run('INSERT INTO Rel_Shipment_Buyer (Shipment_ID, Buyer_ID) VALUES (:shipment_id, :buyer_id)', ['shipment_id' => $id, 'buyer_id' => action_id('buyer_id')]);
            break;
        case 'supplier':
            $id = action_id('supplier_id');
            action_run('INSERT INTO Supplier (Supplier_ID, Name, Address, Email) VALUES (:id, :name, :address, :email)', ['id' => $id, 'name' => action_value('name'), 'address' => action_value('address'), 'email' => action_value('email')]);
            foreach (['contact_number_primary', 'contact_number_additional'] as $field) { if ($contact = action_value($field, false)) action_run('INSERT INTO Supplier_Contact (Supplier_ID, Contact_Number) VALUES (:id, :contact)', ['id' => $id, 'contact' => $contact]); }
            break;
        case 'worker':
        case 'incharge':
            $id = action_id('employee_id'); $role = $resource === 'worker' ? 'Worker' : 'Incharge';
            action_run('INSERT INTO Employee (Employee_ID, Password, Position, Salary, Status) VALUES (:id, :password, :position, :salary, :status)', ['id' => $id, 'password' => password_hash(action_value('password'), PASSWORD_DEFAULT), 'position' => $role, 'salary' => action_value('salary'), 'status' => $resource === 'worker' ? action_value('status') : 'Active']);
            if ($resource === 'worker') { action_run('INSERT INTO Worker (Employee_ID, Name, Address, Grade, Email) VALUES (:id, :name, :address, :grade, :email)', ['id' => $id, 'name' => action_value('name'), 'address' => action_value('address'), 'grade' => action_value('grade'), 'email' => action_value('email')]); action_run('INSERT INTO Worker_Contact (Employee_ID, Contact_Number) VALUES (:id, :contact)', ['id' => $id, 'contact' => action_value('contact_number')]); }
            else { action_run('INSERT INTO Incharge (Employee_ID, Name, Operating_Stage, Email, Address) VALUES (:id, :name, :stage, :email, :address)', ['id' => $id, 'name' => action_value('name'), 'stage' => action_value('operating_stage'), 'email' => action_value('email'), 'address' => action_value('address')]); action_run('INSERT INTO Incharge_Contact (Employee_ID, Contact_Number) VALUES (:id, :contact)', ['id' => $id, 'contact' => action_value('contact_number')]); }
            break;
        default:
            throw new InvalidArgumentException('Unsupported form.');
    }
    echo json_encode(['ok' => true, 'message' => 'Record saved successfully.']);
} catch (Throwable $error) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => $error->getMessage()]);
}
