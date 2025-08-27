<?php

namespace App\Imports;

use App\Karyawan;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class KaryawanImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Karyawan([
            //
        ]);
    }

    public function collection(Collection $rows)
    {
        $isFirstHeader = true;

        $filteredRows = $rows->filter(function ($row, $key) use (&$isFirstHeader) {

            if ($key === 0 || (!$isFirstHeader && $row[0] == 'username(nik)')) {
                $isFirstHeader = false;
                return false;
            }


            return !is_null($row[0]) && !is_null($row[1]) && !is_null($row[2]);
        });
        $mappedData = $filteredRows->map(function ($row) {
            return [
                'username_nik' => $row[0],
                'email'        => $row[1],
                'password'     => $row[2],
                'complete_name' => $row[3],
                'zone_code'    => $row[4] ?? null,
                'role_name'    => $row[5],
            ];
        });

        return $mappedData;
    }
}
