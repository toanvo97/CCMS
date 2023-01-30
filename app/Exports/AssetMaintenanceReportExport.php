<?php

namespace App\Exports;

use App\Helpers\Helper;
use App\Models\AssetMaintenance;

class AssetMaintenanceReportExport extends ExportDatatable
{
    public function titleData(): string
    {
        return 'Báo cáo chi tiết quản lý bảo trì thiết bị';
    }

    public function headingData(): array
    {
        return array_map('mb_strtoupper', [
            'STT',
            'Cơ sở',
            'Mã thiết bị',
            'Tên thiết bị',
            'Nhà cung cấp',
            'Loại bảo trì',
            'Tiêu đề',
            'Ngày bắt đầu',
            'Ngày hoàn thành',
            'Thời gian bảo trì trong ngày',
            'Phí bảo trì',
            'Người quản lý',
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
        return AssetMaintenance::with('asset', 'supplier', 'asset.company')
        ->orderBy('created_at', 'ASC')
        ->get();
    }

    public function mappingData($list)
    {
        return $list->map(function ($assetMaintenance, $key) {
       
            return [
                $key+1,
                isset($assetMaintenance->asset) && isset($assetMaintenance->asset->company) ? $assetMaintenance->asset->company->name : '',
                isset($assetMaintenance->asset) ? e($assetMaintenance->asset->asset_tag) : '',
                isset($assetMaintenance->asset) ? e($assetMaintenance->asset->name) : '',
                isset($assetMaintenance->supplier) ? $assetMaintenance->supplier->name : '',
                $assetMaintenance->asset_maintenance_type,
                isset($assetMaintenance->title) ? $assetMaintenance->title : '',
                isset($assetMaintenance->start_date) ? Helper::getFormattedDateObject($assetMaintenance->start_date, 'date')['formatted'] : '',
                isset($assetMaintenance->completion_date) ? Helper::getFormattedDateObject($assetMaintenance->completion_date, 'date')['formatted'] : '',
                $assetMaintenance->asset_maintenance_time,
                Helper::formatCurrencyOutput($assetMaintenance->cost),
                isset($assetMaintenance->admin) ? e($assetMaintenance->admin->getFullNameAttribute()) : null,
            ];
        })->toArray();
    }
}
