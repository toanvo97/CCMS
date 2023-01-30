<?php

namespace App\Exports;

use App\Helpers\SystemDefine;
use App\Http\Controllers\ReportsController;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class CustomReportExport extends ExportDatatable implements WithColumnFormatting
{
    public function titleData(): string
    {
        if(isset(request()->use_bom)){
            return 'Báo cáo thống kê tài sản ';
        }else{
            return 'Báo cáo chi tiết thống kê tài sản ';
        }
    }

    public function columnFormats(): array
    {
        $header            = $this->headingData();
        $purchaseCostTitle = 'ĐƠN GIÁ';
        $totalCostTitle    = 'THÀNH TIỀN';
        if((request()->use_bom || request()->use_bom_model || request()->use_bom_location) && request()->total_cost){
            $sumTotalTitle = 'TỔNG TIỀN';
        }
        $total             = [];
        $purchase          = [];
        $columnToFormat    = [];
        foreach($header as $key => $value){ 
            if($purchaseCostTitle == $value){
                $purchase = [Coordinate::stringFromColumnIndex($key+1) => SystemDefine::FORMAT_NUMBER_COMMA_SEPARATED];
            }
            if($totalCostTitle == $value){
                $total    = [Coordinate::stringFromColumnIndex($key+1) => SystemDefine::FORMAT_NUMBER_COMMA_SEPARATED];
            }
            if(isset($sumTotalTitle) && $value == $sumTotalTitle){
                $sum = [Coordinate::stringFromColumnIndex($key+1) => SystemDefine::FORMAT_NUMBER_COMMA_SEPARATED];
            }
        }

        $columnToFormat   = !isset($sum) ? array_merge($purchase,$total) : array_merge($purchase,$total,$sum);
        return $columnToFormat;
    }

    public function headingData(): array
    {   
        $header = (count((new ReportsController)->getHeader(request())) >=2) ? array_map('mb_strtoupper',array_merge(['STT'],(new ReportsController)->getHeader(request()))) : [];
        return $header; 
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
        $data = $this->getAll();
        $endCollumns = (count($data['result']) > 0) ? ((count($data['result'][0]) >= 3) ? [count($data['result'][0])] : [3]) : [3];
        return $endCollumns;
    }

    public function getAll(){
        $data = (new ReportsController)->getAssetCustom(request());
        return $data;
    }

    public function mappingData($list)
    {
        return $list;
    }
}
