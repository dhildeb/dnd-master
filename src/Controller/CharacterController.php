<?php

namespace App\Controller;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
/**
 * @Route("/character", name="character.")

 */
class CharacterController extends AbstractController
{
    private $client;
    private $charRepo;

    public function __construct(HttpClientInterface $client, CharacterRepository $characterRepository)
    {
        $this->client = $client;
        $this->charRepo = $characterRepository;
    }

    /**
     * @Route("/", name="")
     * @return Response
     */
    public function index(): Response
    {
        $characters = $this->charRepo->getAll();

        dump($characters);
        return $this->render("character/index.html.twig", [
            'characters' => $characters
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

        if(isset($_POST['create_character'])){
            $char = new Character();
            $char->setType($class['class_name']);
            //                                         v should be variable, TODO make roll stat function
            $char->setHp(intval($class['hit_die'])+3);
            $char->setHitDie($class['hit_die']);
            $char->setSavingThrows($class['saving_throws']);
            $charName = htmlspecialchars($_POST['character_name']);
            $charEquipment = [...$class['starting_equipment']['primary'], ...$_POST['equipment']];
            $charProficiencies = [...$class['proficiencies']['primary'], ...$_POST['proficiencies']];
            $char->setName($charName);
            $char->setEquipment($charEquipment);
            $char->setProficiencies($charProficiencies);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($char);
            $entityManager->flush();
            $this->addFlash('success', 'Character Created!');
            return $this->redirect($this->generateUrl('character.'));
        }
        return $this->render("character/create.html.twig", [
            "class" => $class
        ]);
    }

/**
 * @Route("/edit/{id}", name="edit")
 * @param Request $request
 * @return Response
 */
public function editCharacter(Request $request): Response
{

}

/**
 * @Route("/delete/{id}", name="delete")
 * @param Request $request
 * @return Response
 */
public function deleteCharacter(Request $request): Response
{

}


}

