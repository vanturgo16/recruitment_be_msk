<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Employee::select(
            'emp_no',
            'email',
            'dept_name',
            'position_name',
            'offices.name as office_name',
            'employees.is_active',
            'join_date'
        )
        ->leftJoin('mst_positions', 'employees.id_position', 'mst_positions.id')
        ->leftJoin('mst_departments', 'mst_positions.id_dept', 'mst_departments.id')
        ->leftJoin('offices', 'employees.placement_id', 'offices.id')
        ->get()
        ->map(function($item) {
            return [
                $item->emp_no,
                $item->email,
                $item->dept_name,
                $item->position_name,
                $item->office_name,
                $item->is_active == 1 ? 'Active' : 'Inactive',
                $item->join_date,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Employee No',
            'Email',
            'Department',
            'Position',
            'Placement',
            'Status',
            'Join Date',
        ];
    }
}
