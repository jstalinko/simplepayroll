<?php


$setting = json_decode(file_get_contents(storage_path('app/private/settings.json')), true);
return [
    'secret' => $setting['whatsapp']['piwapi_secret'],
    'account' => $setting['whatsapp']['piwapi_account_id'],
];
