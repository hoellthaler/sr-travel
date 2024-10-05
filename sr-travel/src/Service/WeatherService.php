<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class WeatherService
{
    private HttpClientInterface $client;
    private LoggerInterface $logger;

    public function __construct(HttpClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function getAverageSurfacePressure(array $stationIds): float
    {
        $url = 'https://dwd.api.proxy.bund.dev/v30/stationOverviewExtended?stationIds=' . implode(',', $stationIds);
        $response = $this->client->request('GET', $url);

        $data = $response->toArray(false);
        $logger = $this->logger;
        $logger->info('API Response: ', $data);

        $totalPressure = 0;
        $count = 0;

        foreach ($data as $station) {
            if (isset($station['forecast1']['surfacePressure']) && is_array($station['forecast1']['surfacePressure'])) {
                foreach ($station['forecast1']['surfacePressure'] as $pressure) {
                    if (is_numeric($pressure)) {
                        $totalPressure += (float)$pressure;
                    }
                }
                $count += count($station['forecast1']['surfacePressure']);
            } else {
                $logger->warning('Surface pressure not found or not an array for station.', [
                    'station' => $station,
                    'expectedKey' => 'forecast1.surfacePressure'
                ]);
            }
        }

        return $count > 0 ? $totalPressure / $count : 0;
    }
}
