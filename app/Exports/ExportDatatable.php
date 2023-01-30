<?php

namespace App\Exports;

use App\Helpers\SystemDefine;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

abstract class ExportDatatable implements FromArray, WithTitle, WithHeadings, WithEvents, WithColumnWidths, WithPreCalculateFormulas
{
    use Exportable, RegistersEventListeners;

    public string $type;
    protected string $begin;
    protected string $end;

    // public function __construct(string $type)
    // {
    //     $this->type  = $type;
    // }

    public function getDrawing(){
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('img/logo_royal.png'));
        $drawing->setHeight(75);
        $drawing->setCoordinates('A2');
        return $drawing;
    }

    public function title(): string
    {
        return $this->titleData();
    }

    public function headings(): array
    {
        return $this->headingData();
    }

    public function columnWidths(): array
    {
        return $this->columnWidthsData();
    }

    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            AfterSheet::class => function(AfterSheet $event) {
                $workSheet = $event->sheet->getDelegate();
               
                // last column as letter value (e.g., D)
                // if(!empty($this->array())){
                //     $last_column = Coordinate::stringFromColumnIndex(count($this->array()[0]));
                // }else{
                    $last_column = Coordinate::stringFromColumnIndex(count($this->headings()));
                // }
               
                // calculate last row + 1 (total results + header rows + column headings row + new row)
                $last_row = $event->getDelegate()->getHighestRow() + 7;

                // set up a style array for cell formatting
                $style_text_center = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ]
                ];
                $style_sum_total_center = [
                    'alignment' => [
                        'vertical' => Alignment::HORIZONTAL_CENTER
                    ]
                ];

                $backgroundColor = [
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'ffffff'],
                    ],
                ];

                $font = [
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 15,
                        'bold' => true
                    ],
                ];

                $headerFont = [
                    'font' => [
                        'name' => 'Times New Roman',
                        'bold' => true
                    ],
                ];
                // at row 1, insert 2 rows
                $event->sheet->insertNewRowBefore(1, 7);

                // merge cells for full-width
                $event->sheet->mergeCells(sprintf('C2:%s2',$last_column));
                $event->sheet->mergeCells(sprintf('C4:%s4',$last_column));
                $event->sheet->mergeCells(sprintf('A7:%s7',$last_column));
                // Handel merge row for sum total when use bom export
                $dataArr            = $this->getMergeArray();
                $mergeRows          = $dataArr['mergeRows'];
                $dataToSum          = $dataArr['dataToSum'];
                $orderNumberColumn  = !empty($dataToSum['order_number_column_id']) ? Coordinate::stringFromColumnIndex($dataToSum['order_number_column_id']) : '';
                $totalCostCell      = $dataToSum['total_cost'];
                $totalOrderNumberCell = $dataToSum['total_order_number'];
                $colDefault         = 8;
                $totalCell          = 0;
                $totalSumCostColumn = '';
                $totalCostColumn    = '';
                if(!empty($mergeRows)){
                    array_map(function($arr) use(&$event, $colDefault, $style_sum_total_center, &$totalCell,
                    &$totalSumCostColumn, &$totalCostColumn){
                        array_map(function($location) use(&$event, $colDefault, $style_sum_total_center, &$totalCell,
                        &$totalSumCostColumn, &$totalCostColumn){
                            array_map(function($item) use(&$event, $colDefault, $style_sum_total_center, &$totalCell,
                            &$totalSumCostColumn, &$totalCostColumn) {
                                if(count($item) > 1) {
                                    $totalCostColumn        = !empty($item['total_cost_column_id']) ? Coordinate::stringFromColumnIndex($item['total_cost_column_id']) : '';
                                    $totalSumCostColumn     = !empty($item['total_cost_column_id']) ? Coordinate::stringFromColumnIndex($item['total_cost_column_id']+1) : '';
                                    if((request()->use_bom || request()->use_bom_model || request()->use_bom_location) && !empty($totalSumCostColumn)){
                                        $firstId    = $item['array_ids'][0] + $colDefault;
                                        $lastId     = last($item['array_ids']) + $colDefault;
                                        $totalCell += $item['totals'];
                                        $event->sheet->mergeCells($totalSumCostColumn.$firstId.':'.$totalSumCostColumn.$lastId);
                                        $event->sheet->setCellValue($totalSumCostColumn.$firstId, $item['totals']);
                                        $event->sheet->getStyle(sprintf($totalSumCostColumn.$firstId.':'.$totalSumCostColumn.$lastId))->applyFromArray($style_sum_total_center);
                                    }
                                }
                            }, $location);
                        }, $arr);
                    }, $mergeRows);
                    //Set value after sum 
                    $lastRowToSum               = $last_row+1;
                    $event->sheet->setCellvalue($orderNumberColumn.$lastRowToSum, $totalOrderNumberCell);
                    $event->sheet->setCellvalue($totalCostColumn.$lastRowToSum, $totalCostCell);
                    if(request()->use_bom || request()->use_bom_model || request()->use_bom_location){
                        $event->sheet->setCellvalue($totalSumCostColumn.$lastRowToSum, $totalCell);
                        $event->sheet->getStyle(sprintf($orderNumberColumn.$lastRowToSum.':%s'.$lastRowToSum, $totalSumCostColumn))->getNumberFormat()->applyFromArray(
                            [
                                'formatCode' => SystemDefine::FORMAT_NUMBER_COMMA_SEPARATED
                            ]
                        );
                    }else{
                        $event->sheet->getStyle(sprintf($orderNumberColumn.$lastRowToSum.':%s'.$lastRowToSum, $totalCostColumn))->getNumberFormat()->applyFromArray(
                            [
                                'formatCode' => SystemDefine::FORMAT_NUMBER_COMMA_SEPARATED
                            ]
                        );
                    }
                }
                // Header cell value
                $event->sheet->setCellValue('C2', 'RoyalSchool CMMS');
                $event->sheet->setCellValue('C4', '08 Đặng Đại Độ, Tân Phong, Quận 7, Thành phố Hồ Chí Minh 700000');
                $event->sheet->setCellValue('A7', $this->titleData());

                //Logo
                $this->getDrawing()->setWorksheet($workSheet);

                //Footer cell value
                // $event->sheet->setCellValue(sprintf('A%d',$last_row),'SECURITY CLASSIFICATION - UNCLASSIFIED [Generated: ...]');
               
                // assign cell styles
                $event->sheet->getStyle('A7')->applyFromArray($style_text_center);
                $event->sheet->getStyle(sprintf('A8:%s8',$last_column))->applyFromArray($style_text_center);
                $event->sheet->getStyle('C2')->applyFromArray($font);
                $event->sheet->getStyle(sprintf('A8:%s8',$last_column))->applyFromArray($headerFont);
                $event->sheet->getStyle('A7')->applyFromArray($font)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('D9D9D9');
                $event->sheet->getStyle(sprintf('A%d',$last_row))->applyFromArray($style_text_center);
                for($i=1;$i<=6;$i++){
                    $event->sheet->getStyle(sprintf('A'.$i.':%s'.$i,$last_column))->applyFromArray($backgroundColor);  
                }
                $event->sheet->getStyle(sprintf('A8:%s'. $last_row,$last_column))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ])->getAlignment()->setWrapText(true);
                //Column width
                $beginColumns = $this->getBeginColumns();
                $endColumns   = $this->getEndCollumns();  
                for($i = 0; $i < max(count($beginColumns),count($endColumns)); $i++){
                    if(!empty($beginColumns[$i]) && !empty($endColumns[$i])){
                        $this->setColumnAutoWidth($beginColumns[$i],$endColumns[$i],$event);
                    }else if(empty($beginColumns[$i])){
                        $this->setColumnAutoWidth($endColumns[$i],$endColumns[$i],$event);
                    }else if(empty($endColumns[$i])){
                        $this->setColumnAutoWidth($beginColumns[$i],$beginColumns[$i],$event);
                    }
                }
            },
        ];
    }

    public function setColumnAutoWidth($begin, $end, $event){
        if(!empty($this->headingData())){
            foreach(range($begin, $end) as $columnID){
                $event->sheet->getColumnDimension(Coordinate::stringFromColumnIndex($columnID))->setAutoSize(true);
            }
        }
    }

    public function getMergeArray(): array
    {
        return !empty($this->headingData()) ? $this->mappingData($this->getAll()) : [];
    }

    public function array(): array
    {
        if(request()->export_type == 'all'){
                return !empty($this->headingData()) ? $this->mappingData($this->getAll()['result']) : [];
        }
        else if(request()->export_type == 'single'){
            //dd(213);
        }
    }

    abstract public function headingData();
    abstract public function mappingData($list);
    abstract public function titleData();
    abstract public function getAll();
    abstract public function columnWidthsData();
    abstract public function getBeginColumns();
    abstract public function getEndCollumns();
}
