<?php


namespace App\Controller;

use App\Service\CbrRates;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function json_encode;

class CurrencyController
{

    /**
     * @Route("/currency", name="app_currency")
     * @param CbrRates $cbrRates
     * @return Response
     */
    public function get(CbrRates $cbrRates) : Response {
        $response = new Response(json_encode($cbrRates->getRates()));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
