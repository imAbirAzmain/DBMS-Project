<?php require 'config/db.php'; print_r(garments_db_fetch_all('SELECT Employee_ID, Password FROM Employee WHERE Employee_ID=101')); ?>
