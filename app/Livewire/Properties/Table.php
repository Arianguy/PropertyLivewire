<?php

namespace App\Livewire\Properties;

use App\Models\Property;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Table extends Component
{
    use WithPagination;

    public $search = '';
    public $isExporting = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteProperty(Property $property)
    {
        $property->delete();
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Property deleted successfully!'
        ]);
    }

    public function export($format)
    {
        $this->isExporting = true;

        $properties = Property::with('owner')->get();

        if ($format === 'xlsx') {
            return $this->exportToExcel($properties);
        } else {
            return $this->exportToPdf($properties);
        }
    }

    protected function exportToExcel($properties)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'Type');
        $sheet->setCellValue('C1', 'Class');
        $sheet->setCellValue('D1', 'Purchase Date');
        $sheet->setCellValue('E1', 'Title Deed No');
        $sheet->setCellValue('F1', 'Mortgage Status');
        $sheet->setCellValue('G1', 'Community');
        $sheet->setCellValue('H1', 'Plot No');
        $sheet->setCellValue('I1', 'Building No');
        $sheet->setCellValue('J1', 'Building Name');
        $sheet->setCellValue('K1', 'Property No');
        $sheet->setCellValue('L1', 'Floor Detail');
        $sheet->setCellValue('M1', 'Suite Area');
        $sheet->setCellValue('N1', 'Balcony Area');
        $sheet->setCellValue('O1', 'Area (sq.m)');
        $sheet->setCellValue('P1', 'Common Area');
        $sheet->setCellValue('Q1', 'Area (sq.ft)');
        $sheet->setCellValue('R1', 'Owner');
        $sheet->setCellValue('S1', 'Purchase Value');
        $sheet->setCellValue('T1', 'Status');
        $sheet->setCellValue('U1', 'DEWA Premise No');
        $sheet->setCellValue('V1', 'DEWA Account No');

        // Style headers
        $sheet->getStyle('A1:V1')->getFont()->setBold(true);

        // Add data rows
        $row = 2;
        foreach ($properties as $property) {
            $sheet->setCellValue('A' . $row, $property->name);
            $sheet->setCellValue('B' . $row, $property->type);
            $sheet->setCellValue('C' . $row, $property->class);
            $sheet->setCellValue('D' . $row, $property->purchase_date ? $property->purchase_date->format('Y-m-d') : 'N/A');
            $sheet->setCellValue('E' . $row, $property->title_deed_no);
            $sheet->setCellValue('F' . $row, $property->mortgage_status);
            $sheet->setCellValue('G' . $row, $property->community);
            $sheet->setCellValue('H' . $row, $property->plot_no);
            $sheet->setCellValue('I' . $row, $property->bldg_no);
            $sheet->setCellValue('J' . $row, $property->bldg_name);
            $sheet->setCellValue('K' . $row, $property->property_no);
            $sheet->setCellValue('L' . $row, $property->floor_detail);
            $sheet->setCellValue('M' . $row, $property->suite_area);
            $sheet->setCellValue('N' . $row, $property->balcony_area);
            $sheet->setCellValue('O' . $row, $property->area_sq_mter);
            $sheet->setCellValue('P' . $row, $property->common_area);
            $sheet->setCellValue('Q' . $row, $property->area_sq_feet);
            $sheet->setCellValue('R' . $row, $property->owner ? $property->owner->name : 'N/A');
            $sheet->setCellValue('S' . $row, $property->purchase_value);
            $sheet->setCellValue('T' . $row, $property->status);
            $sheet->setCellValue('U' . $row, $property->dewa_premise_no);
            $sheet->setCellValue('V' . $row, $property->dewa_account_no);
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'V') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Add filters
        $sheet->setAutoFilter('A1:V1');

        // Freeze the top row
        $sheet->freezePane('A2');

        // Save file
        $filename = 'properties-' . date('Y-m-d-H-i-s') . '.xlsx';
        $path = storage_path('app/public/' . $filename);

        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        $this->isExporting = false;

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend();
    }

    protected function exportToPdf($properties)
    {
        $pdf = PDF::loadView('exports.properties-pdf', [
            'properties' => $properties,
            'date' => date('Y-m-d'),
        ]);

        // Set PDF options
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions([
            'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);

        $filename = 'properties-' . date('Y-m-d-H-i-s') . '.pdf';

        $this->isExporting = false;

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function render()
    {
        return view('livewire.properties.table', [
            'properties' => Property::with('owner')
                ->when(!$this->isExporting, function ($query) {
                    return $query->where(function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('property_no', 'like', '%' . $this->search . '%')
                            ->orWhere('title_deed_no', 'like', '%' . $this->search . '%')
                            ->orWhere('community', 'like', '%' . $this->search . '%')
                            ->orWhere('bldg_name', 'like', '%' . $this->search . '%')
                            ->orWhereHas('owner', function ($query) {
                                $query->where('name', 'like', '%' . $this->search . '%');
                            });
                    });
                })
                ->paginate(10),
        ]);
    }
}
