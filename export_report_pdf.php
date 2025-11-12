<?php
require('fpdf186/fpdf.php');
include 'db.php'; // Database connection

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Hotel Reservation Report', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function TableHeader() {
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(15, 10, 'ID', 1, 0, 'C', true);
        $this->Cell(35, 10, 'Customer', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Room', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Check-in', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Check-out', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Status', 1, 1, 'C', true);
    }

    function TableData($conn) {
        $this->SetFont('Arial', '', 10);
        $query = "SELECT id, guest_name, room_type, check_in, check_out, status FROM reservations";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            $this->Cell(15, 8, $row['id'], 1);
            $this->Cell(35, 8, $row['guest_name'], 1);
            $this->Cell(30, 8, $row['room_type'], 1);
            $this->Cell(30, 8, $row['check_in'], 1);
            $this->Cell(30, 8, $row['check_out'], 1);
            $this->Cell(30, 8, $row['status'], 1);
            $this->Ln();
        }
    }
}

// Create PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->TableHeader();
$pdf->TableData($conn);
$pdf->Output('D', 'reservation_report.pdf'); // D = force download
exit;
