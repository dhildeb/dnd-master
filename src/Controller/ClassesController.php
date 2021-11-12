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
 * @Route("/classes", name="classes.")

 */
class ClassesController extends AbstractController
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
            'https://www.dnd5eapi.co/api/classes'
        );
        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        try {
            $contentType = $response->getHeaders()['content-type'][0];
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
        }
        // $contentType = 'application/json'
        try {
            $content = $response->getContent();
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
        }
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        try {
            $content = $response->toArray();
        } catch (ClientExceptionInterface | DecodingExceptionInterface | TransportExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $e) {
        }
        dump($content);
        return $this->render("classes/index.html.twig", [
            "classes" => $content["results"]
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
//  get selected class
        $class = $request->query->get('class');
//  get class details from dnd api
        $response = $this->client->request(
            'GET',
            "https://www.dnd5eapi.co".$class["url"]
        );
        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        try {
            $contentType = $response->getHeaders()['content-type'][0];
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
        }
        // $contentType = 'application/json'
        try {
            $content = $response->getContent();
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
        }
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        try {
            $content = $response->toArray();
        } catch (ClientExceptionInterface | DecodingExceptionInterface | TransportExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $e) {
        }
//  get class levels from dnd api
        $response = $this->client->request(
            'GET',
            "https://www.dnd5eapi.co/api/classes/$class[index]/levels"
        );
        $statusCode = $response->getStatusCode();
        try {
            $contentType = $response->getHeaders()['content-type'][0];
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
        }
        try {
            $levels = $response->getContent();
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
        }
        try {
            $levels = $response->toArray();
        } catch (ClientExceptionInterface | DecodingExceptionInterface | TransportExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $e) {
        }

        dump($content);
        dump($levels);
        return $this->render("classes/details.html.twig", [
            "class" => $content,
            "levels" => $levels
        ]);
    }
}
