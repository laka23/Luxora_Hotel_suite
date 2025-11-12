<?php
require 'vendor/autoload.php';
require 'db.php';



use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Fetch reservation data
$query = "SELECT id, guest_name, room_type, check_in, check_out, status FROM reservations";
$result = mysqli_query($conn, $query);
if (!$result) {
    die('Database query failed: ' . mysqli_error($conn));
}

// Create new spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set headers
$headers = ['ID', 'Customer Name', 'Room', 'Check-in Date', 'Check-out Date', 'Status'];
$sheet->fromArray($headers, null, 'A1');

// Fill data
$rowNum = 2;
while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue("A$rowNum", $row['id']);
    $sheet->setCellValue("B$rowNum", $row['guest_name']);
    $sheet->setCellValue("C$rowNum", $row['room_type']);
    $sheet->setCellValue("D$rowNum", $row['check_in']);
    $sheet->setCellValue("E$rowNum", $row['check_out']);
    $sheet->setCellValue("F$rowNum", $row['status']);
    $rowNum++;
}

// Auto filter and column width
$sheet->setAutoFilter("A1:F1");
foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Output to browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reservations.xlsx"');
header('Cache-Control: max-age=0');

if (ob_get_length()) {
    ob_end_clean();
}

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
