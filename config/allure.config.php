<?php

// return [
//     'outputDirectory' => 'allure-results',
// ];

return [
    // Use the per-run directory from your runner. Fallback to build/allure-results.
    'outputDirectory' => getenv('ALLURE_RESULTS_DIRECTORY')
        ?: getenv('ALLURE_OUTPUT_DIR')
        ?: 'build/allure-results',
];