<?php

return [
    'enable_remote' => true,
    'enable_html5_parser' => true,
    'enable_font_subsetting' => false,
    'font_dir' => storage_path('fonts'),
    'font_cache' => storage_path('fonts/cache'),
    'default_font' => 'DejaVu Sans',
    'pdf_backend' => 'CPDF',
    'default_media_type' => 'screen',
    'default_paper_size' => 'a4',
    'default_paper_orientation' => 'portrait',
    'isPhpEnabled' => true,
    'isRemoteEnabled' => true,
    'isHtml5ParserEnabled' => true,
    'isFontSubsettingEnabled' => false,
    'log_output_file' => storage_path('logs/dompdf.html'),
    'enable_font_subsetting' => false,
];