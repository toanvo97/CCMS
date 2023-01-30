<?php

namespace App\Exports;

use App\Models\Accessory;

class AccessoryReportExport extends ExportDatatable
{
    public function titleData(): string
    {
        return 'Báo cáo chi tiết quản lý phụ kiện';
    }

    public function headingData(): array
    {
        return array_map('mb_strtoupper', [
            'STT',
            'Cơ sở',
            'Tên phụ kiện',
            'Loại tài sản',
            'Tổng số lượng',
            'Số lượng khả dụng',
        ]);
    }

    public function columnWidthsData()
    {
        return [
            'B' => 22,
        ];
    }

    public function getBeginColumns()
    {
        $beginCollumns = [3];
        return $beginCollumns;
    }

    public function getEndCollumns()
    {
        $endCollumns = [7];
        return $endCollumns;
    }

    public function getAll(){
        return Accessory::orderBy('created_at', 'ASC')->with('company')->get();
    }

    public function mappingData($list)
    {
        return $list->map(function ($accessory, $key) {
            
            return [
                $key+1,
                $accessory->company ? $accessory->company->name : '',
                $accessory->name,
                $accessory->model_number ? $accessory->model_number : '',
                $accessory->qty,
                $accessory->numRemaining()
            ];
        })->toArray();
    }
}
