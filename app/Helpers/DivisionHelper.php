<?php

namespace App\Helpers;

class DivisionHelper
{
    /**
     * Get all available divisions
     *
     * @return array
     */
    public static function getAllDivisions()
    {
        return [
            'Satuan Pengawasan Intern',
            'Sekretariat Perusahaan',
            'Tranformation Management Office',
            'PPSDM',
            'Layanan SDM dan HSSE',
            'Hukum',
            'Anggaran, Akuntansi, dan Pelaporan',
            'Keuangan dan Perpajakan',
            'Manajemen Risiko',
            'Perencanaan Strategis',
            'Kerjasama Usaha dan Pembinaan Anak Perusahaan',
            'Komersial dan Hubungan Pelanggan',
            'Pengelolaan Operasi',
            'Perencanaan dan Pengembangan Operasi',
            'Sistem Manajemen',
            'Peralatan Pelabuhan',
            'Fasilitas Pelabuhan'
        ];
    }

    /**
     * Get divisions for select dropdown
     * 
     * @param string|null $selected
     * @return string
     */
    public static function getDivisionsForSelect($selected = null)
    {
        $html = '<option value="">-- Pilih Divisi --</option>';
        
        foreach (self::getAllDivisions() as $division) {
            $isSelected = ($selected === $division) ? 'selected' : '';
            $html .= sprintf(
                '<option value="%s" %s>%s</option>',
                e($division),
                $isSelected,
                e($division)
            );
        }
        
        return $html;
    }
}
