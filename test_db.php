<?php
require 'config/db.php';

$rows = garments_db_fetch_all('SELECT * FROM Employee');
?>

<table border="1">
    <tr>
        <th>Employee ID</th>
        <th>Password</th>
        <th>Position</th>
    </tr>

    <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= $row['EMPLOYEE_ID'] ?></td>
            <td><?= $row['PASSWORD'] ?></td>
            <td><?= $row['POSITION'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>