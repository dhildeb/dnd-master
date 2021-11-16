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
 * @Route("/api/class", name="class.")

 */
class ClassController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/{class}", name="", defaults={"class":"barbarian"})
     * @param string $class
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function index(string $class): Response
    {
        $response = $this->client->request(
            'GET',
            'https://www.dnd5eapi.co/api/classes/'.$class
        );
        try {
            $content = $response->getContent();
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
        }
        try {
            $content = $response->toArray();
        } catch (ClientExceptionInterface | DecodingExceptionInterface | TransportExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $e) {
        }

        dump($content);
        $dndClass = [
            "class_name" => $content['name'],
            "hit_die" => $content['hit_die'],
            "proficiencies" => ["choose" => [], "options" => [], "primary" => []],
            "saving_throws" => [],
            "starting_equipment" => ["choose" => [], "options" => [], "primary" => []],
            

    ];
        // proficiencies
        foreach ($content['proficiency_choices'] as $proficiencyChoices){
            $optionsArr = [];
            array_push($dndClass['proficiencies']['choose'], $proficiencyChoices['choose']);
            foreach ($proficiencyChoices['from'] as $option){
                array_push($optionsArr, $option['name']);
            }
            array_push($dndClass['proficiencies']['options'], $optionsArr);
        }
        foreach ($content['proficiencies'] as $proficiency){
            array_push($dndClass['proficiencies']['primary'], $proficiency['name']);
        }
        // saving_throws
        foreach ($content['saving_throws'] as $savingThrow){
            array_push($dndClass['saving_throws'], $savingThrow['name']);
        }
        // starting equipment
        foreach ($content['starting_equipment'] as $equipment) {
            // add qty if greater than one
            $equipmentWithQty = $equipment['quantity'] > 1 ? $equipment['equipment']['name'].' x'.strval($equipment['quantity']) : $equipment['equipment']['name'];
            array_push($dndClass['starting_equipment']['primary'], $equipmentWithQty);
        }
        // dont blame me, blame dndapi
        foreach ($content['starting_equipment_options'] as $equipmentOption){
            $optionsArr = [];
            array_push($dndClass['starting_equipment']['choose'], $equipmentOption['choose']);
            foreach ($equipmentOption['from'] as $option){
                if(isset($option['quantity'])) {
                    $equipmentWithQty = $option['quantity'] > 1 ? $option['equipment']['name'] . ' x' . strval($option['quantity']) : $option['equipment']['name'];
                    array_push($optionsArr, $equipmentWithQty);
                }elseif(is_array($option)){
                    foreach ($option as $op) {
                        if(isset($op['choose'])){
                            $url = 'https://www.dnd5eapi.co'.$op['from']['equipment_category']['url'];
                            $res = $this->client->request(
                                'GET',
                                $url
                            );
                            try {
                                $urlRes = $res->getContent();
                            } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
                            }
                            try {
                                $urlRes = $res->toArray();
                            } catch (ClientExceptionInterface | DecodingExceptionInterface | TransportExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $e) {
                            }
                            $arr = [];
                            foreach ($urlRes['equipment'] as $ur) {
                                array_push($arr, $ur['name']);
                            }
                            array_push($optionsArr, $arr);
                            continue;
                        }
                        if (isset($op['quantity'])) {
                            $equipmentWithQty = $op['quantity'] > 1 ? $op['equipment']['name'] . ' x' . strval($op['quantity']) : $op['equipment']['name'];
                            array_push($optionsArr, $equipmentWithQty);
                        } else {
                            if(isset($op['equipment_option'])){
                            $newUrl2 = 'https://www.dnd5eapi.co'.$op['equipment_option']['from']['equipment_category']['url'];
                            }else{
                                dump($op);
                            $newUrl2 = 'https://www.dnd5eapi.co'.$op['url'];
                            }
                            $res = $this->client->request(
                                'GET',
                                $newUrl2
                            );
                            try {
                                $urlRes = $res->getContent();
                            } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
                            }
                            try {
                                $urlRes = $res->toArray();
                            } catch (ClientExceptionInterface | DecodingExceptionInterface | TransportExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $e) {
                            }
                            dump($urlRes['equipment']);
                            foreach ($urlRes['equipment'] as $ur) {
                                array_push($optionsArr, $ur['name']);
                            }
                        }
                    }
                }else{
                    $newUrl = 'https://www.dnd5eapi.co'.$option['equipment_option']['from']['equipment_category']['url'];
                    $res = $this->client->request(
                        'GET',
                        $newUrl
                    );
                    try {
                        $urlRes = $res->getContent();
                    } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
                    }
                    try {
                        $urlRes = $res->toArray();
                    } catch (ClientExceptionInterface | DecodingExceptionInterface | TransportExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $e) {
                    }
                    dump($urlRes['equipment']);
                    foreach ($urlRes['equipment'] as $ur) {
                        array_push($optionsArr, $ur['name']);
                    }
                }
            }
            array_push($dndClass['starting_equipment']['options'], $optionsArr);
        }
        dump($dndClass);
        $response = new Response();

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        $response->setContent(json_encode($content));

//        return $response;
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
        return $this->render("class/details.html.twig", [
            "class" => $content,
            "levels" => $levels
        ]);
    }
}
