

//require 'autoload.php'; // manual loader
//as i downloaded it from github: https://github.com/PHPOffice/PhpSpreadsheet

// upper thing did not work so now on terminal: composer require phpoffice/phpspreadsheet

<?php
ob_clean();
ini_set('display_errors', 0);
error_reporting(0);

require 'auth.php';
require 'db.php';
require 'vendor/autoload.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Title');
$sheet->setCellValue('C1', 'Description');
$sheet->setCellValue('D1', 'Task Date');
$sheet->setCellValue('E1', 'Status');

$row = 2;
foreach ($tasks as $task) {
    $sheet->setCellValue("A{$row}", $task['id']);
    $sheet->setCellValue("B{$row}", $task['title']);
    $sheet->setCellValue("C{$row}", $task['description']);
    $sheet->setCellValue("D{$row}", $task['task_date']);
    $sheet->setCellValue("E{$row}", ucfirst($task['status']));
    $row++;
}

$filename = "my_tasks_" . date('Ymd_His') . ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
