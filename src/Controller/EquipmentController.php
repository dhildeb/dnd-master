<?php

namespace App\Controller;

use App\Entity\Spell;
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
 * @Route("/equipment", name="equipment.")

 */
class EquipmentController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/", name="")
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function index(): Response
    {
        $response = $this->client->request(
            'GET',
            'https://www.dnd5eapi.co/api/equipment'
        );
        try {
            $content = $response->getContent();
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
        }
        try {
            $content = $response->toArray();
        } catch (ClientExceptionInterface | DecodingExceptionInterface | TransportExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $e) {
        }
        return $this->render("equipment/index.html.twig", [
            "equipment" => $content["results"],
            "pathUrl" => "equipment"
        ]);
    }

    /**
     * @Route("/details", name="details")
     * @param Request $request
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function details(Request $request): Response
    {
        $equipment = $request->query->get('item');

        $response = $this->client->request(
            'GET',
            "https://www.dnd5eapi.co".$equipment["url"]
        );

        try {
            $content = $response->getContent();
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
        }
        try {
            $content = $response->toArray();
        } catch (ClientExceptionInterface | DecodingExceptionInterface | TransportExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $e) {
        }
        return $this->render("equipment/details.html.twig", [
            "equipment" => $content
        ]);
    }
}
