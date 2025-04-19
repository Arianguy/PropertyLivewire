<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

class Table extends Component
{
    use WithPagination;

    public $search = '';
    public $isExporting = false;
    public $statusFilter = 'YES';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function deleteContract(Contract $contract)
    {
        $contract->delete();
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Contract deleted successfully!'
        ]);
    }

    public function terminateContract(Contract $contract)
    {
        $contract->update(['validity' => 'NO']);

        // Update the property's status to 'VACANT'
        $contract->property->update(['status' => 'VACANT']);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Contract terminated successfully!'
        ]);
    }

    public function export($format)
    {
        $this->isExporting = true;

        $contracts = Contract::with(['tenant', 'property'])->get();

        if ($format === 'xlsx') {
            return $this->exportToExcel($contracts);
        } else {
            return $this->exportToPdf($contracts);
        }
    }

    protected function exportToExcel($contracts)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'Contract #');
        $sheet->setCellValue('B1', 'Tenant');
        $sheet->setCellValue('C1', 'Property');
        $sheet->setCellValue('D1', 'Start Date');
        $sheet->setCellValue('E1', 'End Date');
        $sheet->setCellValue('F1', 'Amount');
        $sheet->setCellValue('G1', 'Security Amount');
        $sheet->setCellValue('H1', 'Ejari');
        $sheet->setCellValue('I1', 'Status');
        $sheet->setCellValue('J1', 'Type');

        // Style headers
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);

        // Add data rows
        $row = 2;
        foreach ($contracts as $contract) {
            $sheet->setCellValue('A' . $row, $contract->name);
            $sheet->setCellValue('B' . $row, $contract->tenant ? $contract->tenant->name : 'N/A');
            $sheet->setCellValue('C' . $row, $contract->property ? $contract->property->name : 'N/A');
            $sheet->setCellValue('D' . $row, $contract->cstart ? Carbon::parse($contract->cstart)->format('Y-m-d') : 'N/A');
            $sheet->setCellValue('E' . $row, $contract->cend ? Carbon::parse($contract->cend)->format('Y-m-d') : 'N/A');
            $sheet->setCellValue('F' . $row, $contract->amount);
            $sheet->setCellValue('G' . $row, $contract->sec_amt);
            $sheet->setCellValue('H' . $row, $contract->ejari);
            $sheet->setCellValue('I' . $row, $contract->validity);
            $sheet->setCellValue('J' . $row, $contract->type ?? 'Original');
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Add filters
        $sheet->setAutoFilter('A1:J1');

        // Freeze the top row
        $sheet->freezePane('A2');

        // Save file
        $filename = 'contracts-' . date('Y-m-d-H-i-s') . '.xlsx';
        $path = storage_path('app/public/' . $filename);

        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        $this->isExporting = false;

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend();
    }

    protected function exportToPdf($contracts)
    {
        $pdf = PDF::loadView('exports.contracts-pdf', [
            'contracts' => $contracts,
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

        $filename = 'contracts-' . date('Y-m-d-H-i-s') . '.pdf';

        $this->isExporting = false;

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function render()
    {
        return view('livewire.contracts.table', [
            'contracts' => Contract::with(['tenant', 'property'])
                ->when(!$this->isExporting, function ($query) {
                    return $query->where(function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%')
                            ->orWhereHas('tenant', function ($query) {
                                $query->where('name', 'like', '%' . $this->search . '%');
                            })
                            ->orWhereHas('property', function ($query) {
                                $query->where('name', 'like', '%' . $this->search . '%');
                            });
                    });
                })
                ->when($this->statusFilter, function ($query) {
                    return $query->where('validity', $this->statusFilter);
                })
                ->latest()
                ->paginate(10),
        ]);
    }
}
