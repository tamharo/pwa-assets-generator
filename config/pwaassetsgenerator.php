<?php

return [

    "sizes" => [
        "icons" => [192, 512, 48, 72, 96, 144, 168, 180, 256],
        "splash" => [
            [640, 1136, "portrait"],
            [750, 1334, "portrait"],
            [1242, 2208, "portrait"],
            [1125, 2436, "portrait"],
            [1242, 2688, "portrait"], 
            [1536, 2048, "portrait"],
            [2048, 2732, "portrait"]
        ]
    ],

    "output" => [
        "icons" => storage_path("app/public/pwaassetsgenerator/appIcons"),
        "splash" => storage_path("app/public/pwaassetsgenerator/splashScreen"),
        "favicon" => storage_path("app/public/pwaassetsgenerator"),
        "manifest" => storage_path("app/public/pwaassetsgenerator"),
        "splashScreenLinkPath" => storage_path("app/public/pwaassetsgenerator/splash-screen-links.php")
    ],

    "police" => realpath(__DIR__.'/../src/fonts/Geoform-Bold.otf'),
    "background" => "#ffffff",
    "textColor" => "#658479",

    "automatiquePublishManifestToPublicFolder" => true,
    "automatiquePublishFaviconToPublicFolder" => true,

    "manifest" => [
        'name' => env("APP_NAME"),
        'shortName' => env("APP_NAME"),
        'startUrl' => "/",
        'lang' => "en",
        'description' => "Description off app",
        'display' => "standalone",
        'backgroundColor' => "#ffffff",
    ]

];