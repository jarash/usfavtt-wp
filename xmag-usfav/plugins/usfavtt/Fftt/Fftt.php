<?php

use Alamirault\FFTTApi\Service\FFTTApi;

require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once '../../../../wp-load.php';

class FfttService
{
    public $ffttApi;
    public $usfavId;

    public function __construct(
        string $apiLogin,
        string $apiPassword,
        string $teamId,
    )
    {
        $this->ffttApi = new FFTTApi($apiLogin, $apiPassword);
        $this->usfavId = $teamId;
    }
}