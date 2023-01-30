<?php

namespace App\Exports;

class DepreciationReportExport extends ExportDatatable
{
    public function titleData(): string
    {
        return 'Báo cáo chi tiết khấu hao tài sản';
    }

    public function headingData(): array
    {
        return array_map('mb_strtoupper', [
            'STT',
            'Ngày gửi',
            'Cơ sở',
            'Thể loại',
            'Loại tài sản',
            'Tên tài sản',
            'Mã tài sản',
            'Cấp phát đến',
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
        $endCollumns = [12];
        return $endCollumns;
    }

    public function getAll(){
        
    }

    public function mappingData($list)
    {
        return $list->map(function ($item,$key) {
            return [];
        })->toArray();
    }
}
