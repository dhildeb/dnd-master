<?php

namespace App\Controller;

use App\Entity\Character;
use App\Form\CharacterType;
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
 * @Route("/character", name="character.")

 */
class CharacterController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/", name="")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $class = $request->query->get('class');
        $char = $request->request->all();
        dump($request);
        dump($char);
        return $this->render("character/index.html.twig", [
            'char' => $char
        ]);
    }

    /**
     * @Route("/details", name="details")
     * @param Request $request
     * @return Response
     */
    public function details(Request $request): Response
    {

        return $this->render("character/details.html.twig");
    }
    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $class = $request->query->get('class');

        // form parameters
        $proficiencies = [];
        foreach($class['proficiency_choices'][0]['from'] as $c){
            $proficiencies[$c['name']] = $c['name'];
        }

        $equipment = [];
        foreach($class['starting_equipment_options'] as $seo){
            foreach ($seo['from'] as $f){
                if($f['equipment'] ?? null){
                    $equipment[$f['equipment']['name']] = $f['equipment']['name'];
                } else{
                    $items = $this->getList($f['equipment_option']['from']['equipment_category']['url']);
                    $set = [];
                    dump($items);
                    foreach ($items['equipment'] as $i){
                        $set[$i['name']] = $i['name'];
                    }
                    $equipment[$items['name']] = $set;
                }
            }
        }
        $char = new Character();
        $char->setType($class['name']);
        $char->setHp(intval($class['hit_die'])+3);
        $char->setHitDie($class['hit_die']);
        $saves = [];
        foreach ($class['saving_throws'] as $s){
            array_push($saves, $s['name']);
        }
        $char->setSavingThrows($saves);
        $form = $this->createForm(CharacterType::class, $char, [
            'parameters' =>  [
                'proficiencies' => $proficiencies,
                'equipment' => $equipment,
                'class' => $class['name']
                ]
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $formData = $form->getData();

            dump($formData);
            // create character then redirect
        }
    dump($class);
        return $this->render("character/create.html.twig", [
            "class" => $class,
            "character_form" => $form->createView()
        ]);
    }
    private function getList($url){
        $response = $this->client->request(
            'GET',
            "https://www.dnd5eapi.co$url"
        );
        try {
            $data = $response->getContent();
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
        }
        try {
            $data = $response->toArray();
        } catch (ClientExceptionInterface | DecodingExceptionInterface | TransportExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $e) {
        }
        return $data;
    }
}

