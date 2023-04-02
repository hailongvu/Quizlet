<?php

namespace App\Exports;

use App\Models\LessonDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SetDetailsExport implements FromCollection, WithHeadings
{
    private $setDetails;

    public function __construct($setDetails)
    {
        $this->setDetails = $setDetails;
    }

    public function collection()
    {
        return $this->setDetails;
        //LessonDetail::all(['term', 'definition', 'image','audio','video','status',]);
    }

    public function headings(): array
    {
        return ['Term', 'Definition', 'Image','Audio','Video','Status'];
    }
}
