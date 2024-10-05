<?php

namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    #[Route('/weather/average-pressure', name: 'weather_average_pressure')]
    public function index(WeatherService $weatherService): Response
    {
        $stationIds = ['10865', 'G005'];
        $averagePressure = $weatherService->getAverageSurfacePressure($stationIds);

        return new Response('Durchschnittlicher Luftdruck: ' . $averagePressure);
    }
}
