<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
/**
 * @Route("/spells", name="spells.")

 */
class SpellsController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


    /**
     * @Route("/details", name="details")
     * @param Request $request
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function details(Request $request): Response
    {
        $spell = $request->query->get('item');
        $response = $this->client->request(
            'GET',
            "https://www.dnd5eapi.co".$spell["url"]
        );
        try {
            $content = $response->getContent();
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
        }
        try {
            $content = $response->toArray();
        } catch (ClientExceptionInterface | DecodingExceptionInterface | TransportExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $e) {
        }
        return $this->render("spells/details.html.twig", [
            "spell" => $content
        ]);
    }

    /**
     * @Route("/{search}", name="", defaults={"search"=null})
     * @param $search
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function index($search): Response
    {
        $url = 'https://www.dnd5eapi.co/api/spells';
        if($search){
            $url = "https://www.dnd5eapi.co/api/classes/$search/spells";
        }
        $response = $this->client->request(
            'GET',
            $url
        );
        try {
            $content = $response->getContent();
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
        }
        try {
            $content = $response->toArray();
        } catch (ClientExceptionInterface | DecodingExceptionInterface | TransportExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $e) {
        }
        if($content['count'] < 1){
            return $this->redirectToRoute("spells.", ["search" => null]);
        }
        return $this->render("spells/index.html.twig", [
            "spells" => $content["results"],
            "pathUrl" => "spells"
        ]);
    }
}
