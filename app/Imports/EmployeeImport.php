<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

// Models
use App\Models\Candidate;
use App\Models\Employee;
use App\Models\MstDivision;
use App\Models\MstDepartment;
use App\Models\MstPosition;
use App\Models\Office;

class EmployeeImport implements ToCollection, WithStartRow
{
    public $errors = [];       // collect row errors
    public $validRows = [];    // collect valid rows

    // track seen values inside Excel
    protected $seenEmpNo = [];
    protected $seenIdCardNo = [];
    protected $seenEmail = [];
    protected $seenEmailOffice = [];

    public function startRow(): int
    {
        return 2; // start reading from row 3
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $excelRow = $index + 2; // Excel actual row number

            $birthDate = is_numeric($row[6])
                ? Date::excelToDateTimeObject($row[6])->format('Y-m-d')
                : $row[6];

            $joinDate = is_numeric($row[13])
                ? Date::excelToDateTimeObject($row[13])->format('Y-m-d')
                : $row[13];

            // mapping columns A..W
            $data = [
                'emp_no'          => $row[0],
                'id_card_no'      => $row[1],
                'first_name'      => $row[2],
                'last_name'       => $row[3],
                'gender'          => $row[4],
                'birthplace'      => $row[5],
                'birthdate'       => $birthDate,
                'marriage_status' => $row[7],
                'email'           => $row[8],
                'email_office'    => $row[9],
                'phone'           => $row[10],
                'id_card_address' => $row[11],
                'domicile_address'=> $row[12],
                'join_date'       => $joinDate,
                'division'        => $row[14],
                'department'      => $row[15],
                'position'        => $row[16],
                'placement'       => $row[17],
                'reportline_1'    => $row[18],
                'reportline_2'    => $row[19],
                'reportline_3'    => $row[20],
                'reportline_4'    => $row[21],
                'reportline_5'    => $row[22],
            ];

            // 1. validate input fields
            $validator = Validator::make($data, [
                'emp_no'          => 'required',
                'id_card_no'      => 'required|integer|max:99999999999999999999',
                'first_name'      => 'required|string|max:50',
                'last_name'       => 'nullable|string|max:50',
                'gender'          => 'required|in:Male,Female',
                'birthplace'      => 'required|string|max:50',
                'birthdate'       => 'required|date_format:Y-m-d',
                'marriage_status' => 'required|in:Single,Married,Divorce',
                'email'           => 'required|email',
                'email_office'    => 'required|email',
                'phone'           => 'required',
                'id_card_address' => 'required|string|max:255',
                'domicile_address'=> 'required|string|max:255',
                'join_date'       => 'required|date_format:Y-m-d',
                'division'        => 'required|string|max:50',
                'department'      => 'required|string|max:50',
                'position'        => 'required|string|max:50',
                'placement'       => 'required|string|max:255',
                'reportline_1'    => 'required|email',
                'reportline_2'    => 'required|email',
                'reportline_3'    => 'required|email',
                'reportline_4'    => 'nullable|email',
                'reportline_5'    => 'nullable|email',
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    $this->errors[] = "row {$excelRow} = {$error}";
                }
                continue; // skip this row
            }

            // Check Duplicate Data
            if (in_array($data['emp_no'], $this->seenEmpNo)) {
                $this->errors[] = "row {$excelRow} = Duplicate Emp. No. inside Excel";
                continue;
            }
            if (in_array($data['id_card_no'], $this->seenIdCardNo)) {
                $this->errors[] = "row {$excelRow} = Duplicate NIK inside Excel";
                continue;
            }
            if (in_array($data['email'], $this->seenEmail)) {
                $this->errors[] = "row {$excelRow} = Duplicate Emp. Email inside Excel";
                continue;
            }
            if (in_array($data['email_office'], $this->seenEmailOffice)) {
                $this->errors[] = "row {$excelRow} = Duplicate Emp. Email Office inside Excel";
                continue;
            }
            $this->seenEmpNo[] = $data['emp_no'];
            $this->seenIdCardNo[] = $data['id_card_no'];
            $this->seenEmail[] = $data['email'];
            $this->seenEmailOffice[] = $data['email_office'];
            // Check duplicates in DB
            if (Employee::where('emp_no', $data['emp_no'])->exists()) {
                $this->errors[] = "row {$excelRow} = Emp. No., already exists in Database";
                continue;
            }
            if (Candidate::where('email', $data['email'])->exists()) {
                $this->errors[] = "row {$excelRow} = Emp. Email already exists in Database";
                continue;
            }
            if (Employee::where('email', $data['email_office'])->exists()) {
                $this->errors[] = "row {$excelRow} = Emp. Email Office already exists in Database";
                continue;
            }
            if (Candidate::where('id_card_no', $data['id_card_no'])->exists()) {
                $this->errors[] = "row {$excelRow} = NIK already exists in Database";
                continue;
            }

            // 2. check relational lookups
            try {
                $div = MstDivision::where('div_name', $data['division'])->first();
                if (!$div) {
                    $this->errors[] = "row {$excelRow} = Division Name Not Exist";
                    continue;
                }

                $dept = MstDepartment::where('id_div', $div->id)
                    ->where('dept_name', $data['department'])
                    ->first();
                if (!$dept) {
                    $this->errors[] = "row {$excelRow} = Department Name Not Exist";
                    continue;
                }

                $position = MstPosition::where('id_dept', $dept->id)
                    ->where('position_name', $data['position'])
                    ->first();
                if (!$position) {
                    $this->errors[] = "row {$excelRow} = Position Name Not Exist";
                    continue;
                }

                $office = Office::where('name', $data['placement'])->first();
                if (!$office) {
                    $this->errors[] = "row {$excelRow} = Placement Name Not Exist";
                    continue;
                }

                // enrich data
                $data['id_position'] = $position->id;
                $data['placement_id']= $office->id;

                // if all passed → push to validRows
                $this->validRows[] = $data;
                
            } catch (\Exception $e) {
                $this->errors[] = "row {$excelRow} = " . $e->getMessage();
            }
        }
    }
}
