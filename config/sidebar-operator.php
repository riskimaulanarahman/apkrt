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
            'icon' => 'fa fa-th-large',
            'title' => 'Dashboard',
            'url' => '/',
            'route-name' => 'admin.index'
        ],[
            'icon' => 'fa fa-hdd',
            'title' => 'Master Data',
            'url' => 'javascript:;',
            'caret' => true,
            'sub_menu' => [
                [
                    'url' => '/kegiatan-laporanrt',
                    'title' => 'Kinerja Ketua RT',
                    'route-name' => 'admin.kegiatan-laporanrt'
                ],
                // [
                //     'url' => 'javascript:;',
                //     'title' => 'Kematian Warga'
                // ],
                // [
                //     'url' => 'javascript:;',
                //     'title' => 'Demografi RT'
                // ],
            ]
        ],
        // [
        //     'icon' => 'fa fa-question-circle',
        //     'title' => 'Bantuan',
        //     'url' => '/bantuan',
        // ]
    ]
];
