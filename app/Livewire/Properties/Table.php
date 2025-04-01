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
        $sheet->setCellValue('D1', 'Community');
        $sheet->setCellValue('E1', 'Building Name');
        $sheet->setCellValue('F1', 'Property Number');
        $sheet->setCellValue('G1', 'Title Deed Number');
        $sheet->setCellValue('H1', 'Owner');
        $sheet->setCellValue('I1', 'Purchase Date');
        $sheet->setCellValue('J1', 'Purchase Value');
        $sheet->setCellValue('K1', 'Status');

        // Style headers
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);

        // Add data rows
        $row = 2;
        foreach ($properties as $property) {
            $sheet->setCellValue('A' . $row, $property->name);
            $sheet->setCellValue('B' . $row, $property->type);
            $sheet->setCellValue('C' . $row, $property->class);
            $sheet->setCellValue('D' . $row, $property->community);
            $sheet->setCellValue('E' . $row, $property->bldg_name);
            $sheet->setCellValue('F' . $row, $property->property_no);
            $sheet->setCellValue('G' . $row, $property->title_deed_no);
            $sheet->setCellValue('H' . $row, $property->owner ? $property->owner->name : 'N/A');
            $sheet->setCellValue('I' . $row, $property->purchase_date ? $property->purchase_date->format('Y-m-d') : 'N/A');
            $sheet->setCellValue('J' . $row, $property->purchase_value);
            $sheet->setCellValue('K' . $row, $property->status);
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

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
