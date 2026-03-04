<?php
/**
 * Excel Helper - Wrapper para PhpSpreadsheet
 * Reemplaza PHPExcel obsoleto por PhpSpreadsheet moderno
 * 
 * Uso:
 *   require_once 'include/excel_helper.php';
 *   $spreadsheet = ExcelHelper::load($filePath);
 *   $spreadsheet = ExcelHelper::create();
 *   ExcelHelper::save($spreadsheet, $filename, 'xlsx');
 */

// Cargar autoload de Composer
require_once dirname(__DIR__) . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExcelHelper {
    
    /**
     * Crear un nuevo Spreadsheet vacío
     */
    public static function create(): Spreadsheet {
        return new Spreadsheet();
    }
    
    /**
     * Cargar un archivo Excel existente
     * Equivalente a: PHPExcel_IOFactory::load($filename)
     */
    public static function load(string $filename): Spreadsheet {
        return IOFactory::load($filename);
    }
    
    /**
     * Identificar el tipo de archivo Excel
     * Equivalente a: PHPExcel_IOFactory::identify($filename)
     */
    public static function identify(string $filename): string {
        return IOFactory::identify($filename);
    }
    
    /**
     * Crear un Reader para un tipo específico
     * Equivalente a: PHPExcel_IOFactory::createReader($type)
     */
    public static function createReader(string $type) {
        return IOFactory::createReader($type);
    }
    
    /**
     * Guardar Spreadsheet a archivo y descargar
     */
    public static function download(Spreadsheet $spreadsheet, string $filename, string $format = 'xlsx'): void {
        $format = strtolower($format);
        
        if ($format === 'xlsx') {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
        } else {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
            header('Cache-Control: max-age=0');
            $writer = new Xls($spreadsheet);
        }
        
        $writer->save('php://output');
    }
    
    /**
     * Guardar a archivo en disco
     */
    public static function save(Spreadsheet $spreadsheet, string $filepath, string $format = 'xlsx'): void {
        $format = strtolower($format);
        $writer = ($format === 'xlsx') ? new Xlsx($spreadsheet) : new Xls($spreadsheet);
        $writer->save($filepath);
    }
    
    /**
     * Convertir fecha Excel a PHP timestamp
     * Equivalente a: PHPExcel_Shared_Date::ExcelToPHP($value)
     */
    public static function excelToTimestamp($excelDate): int {
        return Date::excelToTimestamp($excelDate);
    }
    
    /**
     * Convertir fecha Excel a formato Y-m-d
     */
    public static function excelToDate($excelDate, string $format = 'Y-m-d'): string {
        $timestamp = Date::excelToTimestamp($excelDate);
        return date($format, $timestamp);
    }
    
    /**
     * Aplicar estilo de encabezado a una fila
     */
    public static function styleHeaderRow($sheet, string $range): void {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'CCCCCC']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);
    }
    
    /**
     * Auto-ajustar ancho de columnas
     */
    public static function autoSizeColumns($sheet, string $startCol, string $endCol): void {
        foreach (range($startCol, $endCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}

