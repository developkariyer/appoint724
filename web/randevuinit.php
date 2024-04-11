<?php

// Change the path according to your actual path to Yii bootstrap file
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config));

// Path to your SQL file
$sqlFilePath = __DIR__ . '/randevusaas.sql';

try {
    if (!file_exists($sqlFilePath)) {
        throw new Exception("SQL file not found.");
    }

    $db = Yii::$app->db;
    $command = $db->createCommand(file_get_contents($sqlFilePath));
    
    $command->execute();

    echo "SQL file executed successfully.";
} catch (Exception $e) {
    echo "Error executing SQL file: " . $e->getMessage();
}
