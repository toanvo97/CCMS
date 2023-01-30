<?php

namespace App\Exports;

use App\Helpers\Helper;
use App\Models\License;
use Illuminate\Support\Facades\Gate;

class LicenseReportExport extends ExportDatatable
{
    public function titleData(): string
    {
        return 'Báo cáo chi tiết quản lý bản quyền ';
    }

    public function headingData(): array
    {
        return array_map('mb_strtoupper', [
            'STT',
            'Cơ sở',
            'Tên bản quyền',
            'Key',
            'Số lượng',
            'Số lượng hiện tại',
            'Ngày hết hạn',
            'Ngày thanh toán',
            'Số tiền',
            'Khấu hao',
            'Tổng',
            'Khấu trừ',
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
        return License::with('depreciation')->orderBy('created_at', 'ASC')
                        ->with('company')->get(); 
    }

    public function mappingData($list)
    {
        return $list->map(function ($license, $key) {
            return [
                $key+1,
                $license->company ? $license->company->name : '',
                $license->name,
                Gate::allows('viewKeys', License::class) ? $license->serial : '------------',
                $license->seats,
                $license->remaincount(),
                !empty($license->expiration_date) ? Helper::getFormattedDateObject($license->expiration_date, 'date')['formatted'] : '',
                !empty($license->purchase_date) ? Helper::getFormattedDateObject($license->purchase_date, 'date')['formatted'] : '',
                Helper::formatCurrencyOutput($license->purchase_cost),
                $license->depreciation ? $license->depreciation->name.' ('.$license->depreciation->months.' '.trans('general.months').')': '',
                Helper::formatCurrencyOutput($license->getDepreciatedValue()),
                '-'.Helper::formatCurrencyOutput(($license->purchase_cost - $license->getDepreciatedValue()))
            ];
        })->toArray();
    }
}
