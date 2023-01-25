<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */
  'menu' => [
        [
            'icon' => 'fa fa-home',
            'title' => 'Home',
            'url' => '/',
            'route-name' => 'admin.index'
        ],[
                'icon' => 'fa fa-folder',
                'title' => 'Master Data',
                'url' => 'javascript:;',
                'caret' => true,
                'sub_menu' => [
                    [
                        'url' => '/kegiatan-laporanrt',
                        'title' => 'Kinerja Ketua RT',
                        'route-name' => 'admin.kegiatan-laporanrt'
                    ],
//                     [
//                         'url' => 'javascript:;',
//                         'title' => 'Kematian Warga'
//                     ],
//                     [
//                         'url' => 'javascript:;',
//                         'title' => 'Demografi RT'
//                     ],
                ]
        ],[
            'icon' => 'fa fa-folder',
            'title' => 'Table Refrensi',
            'url' => 'javascript:;',
            'caret' => true,
            'sub_menu' => [
                [
                    'url' => '/kegiatan-rt',
                    'title' => 'Daftar Kegiatan RT',
                    'route-name' => 'admin.kegiatan-rt'
                ],
            ]
        ],[
            'icon' => 'fa fa-users',
            'title' => 'Kelola User',
            'url' => '/master-user',
            'route-name' => 'admin.masteruser'
        ],
//         [
//             'icon' => 'fa fa-hdd',
//             'title' => 'Kelola Sistem',
//             'url' => 'javascript:;',
//             'caret' => true,
//             'sub_menu' => [
//                 [
//                     'url' => 'javascript:;',
//                     'title' => 'Inisialisasi Kelurahan'
//                 ],[
//                     'url' => 'javascript:;',
//                     'title' => 'Pemeliharaan Data'
//                 ]]
//         ],
//         [
//             'icon' => 'fa fa-question-circle',
//             'title' => 'Bantuan',
//             'url' => '/bantuan',
//         ]
    ]
];
