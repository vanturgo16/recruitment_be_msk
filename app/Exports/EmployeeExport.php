<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;

class EmployeeExport implements FromCollection, WithHeadings
{
    protected $departmentIds;
    protected $status;

    public function __construct($departmentIds = [], $status = 'all')
    {
        $this->departmentIds = $departmentIds;
        $this->status = $status;
    }

    public function collection()
    {
        $query = Employee::select(
            'emp_no',
            'email',
            'mst_departments.dept_name',
            'mst_positions.position_name',
            'offices.name as office_name',
            'employees.is_active',
            'join_date'
        )
        ->leftJoin('mst_positions', 'employees.id_position', 'mst_positions.id')
        ->leftJoin('mst_departments', 'mst_positions.id_dept', 'mst_departments.id')
        ->leftJoin('offices', 'employees.placement_id', 'offices.id');

        if (!empty($this->departmentIds)) {
            $query->whereIn('mst_departments.id', $this->departmentIds);
        }
        if ($this->status === 'active') {
            $query->where('employees.is_active', 1);
        } elseif ($this->status === 'inactive') {
            $query->where('employees.is_active', 0);
        }

        return $query->get()->map(function($item) {
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
            'NIP',
            'Email',
            'Department',
            'Position',
            'Placement',
            'Status',
            'Join Date',
        ];
    }
}
