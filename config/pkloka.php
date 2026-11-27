<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pengaturan Institusi Sekolah PKLoka
    |--------------------------------------------------------------------------
    |
    | Satu sumber konfigurasi nama sekolah dan wilayah agar konsisten
    | di seluruh halaman, form, ekspor laporan, dan data demo.
    |
    */
    'school_name' => env('SCHOOL_NAME', 'SMKN 4 Bandar Lampung'),
    'school_short_name' => env('SCHOOL_SHORT_NAME', 'SMKN 4 Bandar Lampung'),
    'school_city' => env('SCHOOL_CITY', 'Bandar Lampung'),
    'school_address' => env('SCHOOL_ADDRESS', 'Jl. Hos Cokroaminoto No. 102, Enggal, Kota Bandar Lampung'),
    'school_email' => env('SCHOOL_EMAIL', 'pkl@smkn4bandarlampung.sch.id'),
    'default_student_password' => env('DEFAULT_STUDENT_PASSWORD', 'password'),
];
