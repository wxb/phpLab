<?php

require 'vendor/autoload.php';

use Carbon\Carbon;
use Carbon\Translator;

$boringLanguage = 'en_Boring';
$translator = Translator::get($boringLanguage);
$translator->setTranslations([
    'day' => ':count boring day|:count boring days',
]);

$date1 = Carbon::create(2018, 1, 1, 0, 0, 0);
$date2 = Carbon::create(2018, 1, 4, 4, 0, 0);

echo $date1->diffForHumans($date2);
echo $date1->locale($boringLanguage)->diffForHumans($date2);

$translator->setTranslations([
    'before' => function ($time) {
        return '[' . strtoupper($time) . "]";
    },
]);
echo $date2->diffForHumans($date1);
echo $date1->locale($boringLanguage)->diffForHumans($date2);