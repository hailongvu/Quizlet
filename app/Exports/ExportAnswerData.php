<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ExportAnswerData implements FromCollection, WithHeadings
{
    private $data;
    private $sheetName;

    public function __construct(Collection $data, string $sheetName)
    {
        $this->data = $data;
        $this->sheetName = $sheetName;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Quiz ID',
            'Quiz Name',
            'User Name',
            'Question',
            'Answer',
            'Is Correct',
        ];
    }

    public function title(): string
    {
        return $this->sheetName;
    }
}