<?php
return [
    'adminEmail' => 'admin@example.com',
    'maskMoneyOptions' => [
        'prefix' => 'Rp. ',
        'suffix' => '',
        'affixesStay' => true,
        'thousands' => '.',
        'decimal' => ',',
        'precision' => 2, 
        'allowZero' => false,
        'allowNegative' => false,
    ],
    'datepickerOptions' => [
        'format' => 'yyyy-mm-dd',
        'autoclose' => true,
        'todayHighlight' => true,
    ],
    'errMysql' => [
        '1451' => '<br>Data ini terkait dengan data yang terdapat pada modul yang lain.',
    ],
];
