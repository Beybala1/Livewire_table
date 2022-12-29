<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    private $row = 0;

    public function collection()
    {
        return User::latest()->get();
    }

    public function map($user): array
    {
        return [
           'row_number' => ++$this->row,
            $user->name,
            $user->email,
            $user->created_at,
        ];
    }
    
    public function headings(): array
    {
        return [
            '#',
            'İstifadəçi adı',
            'Email',
            'Tarix',
        ];
    }
}
