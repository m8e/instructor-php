<?php

use Cognesy\Instructor\Utils\Env;

return [
    'defaultScraper' => 'none', // 'none' uses file_get_contents($url)

    'scrapers' => [
        'jinareader' => [
            'baseUri' => 'https://r.jina.ai/',
            'apiKey' => Env::get('JINAREADER_API_KEY', ''),
        ],
        'scrapfly' => [
            'baseUri' => 'https://api.scrapfly.io/scrape',
            'apiKey' => Env::get('SCRAPFLY_API_KEY', ''),
        ],
        'scrapingbee' => [
            'baseUri' => 'https://app.scrapingbee.com/api/v1/',
            'apiKey' => Env::get('SCRAPINGBEE_API_KEY', ''),
        ],
    ]
];