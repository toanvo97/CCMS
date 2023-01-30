<?php

namespace App\Exports;

use App\Helpers\Helper;
use App\Models\CheckoutAcceptance;

class UnacceptedReportExport extends ExportDatatable
{
    public function titleData(): string
    {
        return 'Báo cáo chi tiết danh sách tài sản chưa được chấp nhận sau khi cấp phát ';
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
        $endCollumns = [8];
        return $endCollumns;
    }

    public function getAll(){
        $deleted = request()->deleted;
        if($deleted == 'deleted') {
            $acceptances = CheckoutAcceptance::pending()->withTrashed()->with(['assignedTo' , 'checkoutable.assignedTo', 'checkoutable.model'])->get();
        } else if($deleted == 'not-deleted'){
            $acceptances = CheckoutAcceptance::pending()->with(['assignedTo' => function ($query) {
                $query->withTrashed();
            }, 'checkoutable.assignedTo', 'checkoutable.model'])->get();
        }

        $assetsForReport = $acceptances
            ->filter(function ($acceptance) {
                return $acceptance->checkoutable_type == 'App\Models\Asset';
            })
            ->map(function($acceptance) {
                return ['assetItem' => $acceptance->checkoutable, 'acceptance' => $acceptance];
            });
        return $assetsForReport;
    }

    public function mappingData($list)
    {
        return $list->map(function ($item,$key) {
            if($item['assetItem']){
                return [
                    $key+1,
                    Helper::getFormattedDateObject($item['acceptance']->created_at, 'date')['formatted'],
                    ($item['assetItem']->company) ? $item['assetItem']->company->name : '',
                    strip_tags($item['assetItem']->model->category->present()->nameUrl()),
                    strip_tags($item['assetItem']->present()->modelUrl()),
                    strip_tags($item['assetItem']->present()->nameUrl()),
                    $item['assetItem']->asset_tag,
                    ($item['acceptance']->assignedTo) ? $item['acceptance']->assignedTo->getFullNameAttribute() : trans('admin/reports/general.deleted_user'),
                ];
            }else{
                return [];
            }
        })->toArray();
    }
}
