<?php

namespace App\Exports;

use App\Helpers\Helper;
use App\Models\Actionlog;

class ActivityReportExport extends ExportDatatable
{
    public function titleData(): string
    {
        return 'Báo cáo hoạt động';
    }

    public function headingData(): array
    {
        return array_map('mb_strtoupper', [
            'STT',
            'THỜI GIAN',
            'Admin',
            'hoạt động',
            'loại',
            'Tài sản',
            'Đến',
            'Ghi chú',
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
        $beginCollumns = [3,6];
        return $beginCollumns;
    }

    public function getEndCollumns()
    {
        $endCollumns = [5,8];
        return $endCollumns;
    }

    public function getAll(){
        return Actionlog::with('item', 'user', 'target', 'location')->get(); 
    }

    public function mappingData($list)
    {
        return $list->map(function ($actionlog, $key) {
            if(isset($actionlog)){
                return [
                    $key+1,
                    $actionlog->action_date ? Helper::getFormattedDateObject($actionlog->action_date, 'datetime')['formatted']: Helper::getFormattedDateObject($actionlog->created_at, 'datetime')['formatted'],
                    $actionlog->user ? [
                        'name' => e($actionlog->user->getFullNameAttribute()),
                    ]['name'] : null,
                    empty($actionlog->action_type) ? null : $actionlog->present()->actionType(),
                    $actionlog->item ? [
                        'type' => e($actionlog->itemType()),
                    ]['type'] : null,
                    $actionlog->item ? [
                        'name' => ($actionlog->itemType()=='user') ? $actionlog->filename : e($actionlog->item->getDisplayNameAttribute()),
                    ]['name'] : null,
                    $actionlog->target ? [
                        'name' => ($actionlog->targetType()=='user') ? e($actionlog->target->getFullNameAttribute()) : e($actionlog->target->getDisplayNameAttribute()),
                    ]['name'] : null,
                    $actionlog->note ? e($actionlog->note): null,
                ];
            }
            else{
                return [];
            }
        })->toArray();
    }
}
