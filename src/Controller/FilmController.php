<?php

namespace App\Controller;

use App\Entity\Film;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{
    /**
     * @Route("/create", name="film", methods={"POST"})
     */
    public function index(Request $request,EntityManagerInterface $entityManager,FilmRepository $filmRepository): JsonResponse
    {

        $data=$request->getContent(); //recupere la data

        if(!empty($data)){/* On regarde si on recupere une donnée*/

            $data=json_decode($data,true); //Converti le json en array

            /* On check si la clé exist dans le tableau */
            $checkKeyName=array_key_exists("nom",$data);
            $checkKeySynopsis=array_key_exists("synopsis",$data);
            $checkKeyType=array_key_exists("type",$data);

            if($checkKeyName && $checkKeySynopsis && $checkKeyType){ // si les clés existe...

                $nom=htmlspecialchars(trim($data["nom"]));
                $synopsis=htmlspecialchars(trim($data["synopsis"]));
                $type=htmlspecialchars(trim($data["type"]));

                if(!empty($nom) && !empty($synopsis) && !empty($type)){//et qu'elles sont pas vides...

                    $checkIfFilmExist=$filmRepository->findBy(['Nom'=>$nom]);

                    if(!empty($checkIfFilmExist)){ //sil le film existe one ne l'ajoute pas et on rrtourn un message
                        $code=400;
                        $message="Le film existe déjà !";
                        $http=Response::HTTP_BAD_REQUEST;
                        return new JsonResponse(["message"=>$message,"code"=>$code],$http);

                    }


                    $date=new \DateTimeImmutable();

                    $film=new Film();
                    $film->setNom($nom);
                    $film->setSynopsis($synopsis);
                    $film->setType($type);
                    $film->setCreatedAt($date);
                    $entityManager->persist($film);
                    $entityManager->flush(); //on insere les données
                    $code=201;

                    $message="Film ajouté !";
                    $http=Response::HTTP_CREATED;

                }else{
                    $code=400;
                    $message="Le nom, synopsis ou type est vide ! Veuillez rééssayer !";
                    $http=Response::HTTP_BAD_REQUEST;

                }


            }else{
                $code=400;
                $message="Les donneés envoyés ne correspondent pas aux champs attendu !";
                $http=Response::HTTP_BAD_REQUEST;
            }

        }else{
            $code=400;
            $message="Vous n'avez pas envoyé de donnée !";
            $http=Response::HTTP_BAD_REQUEST;
        }


        return new JsonResponse(["message"=>$message,"code"=>$code],$http);
    }

    /**
     * @Route("/getall", name="get_all_film", methods={"POST"})
     */
    public function get_all_film(FilmRepository $filmRepository): JsonResponse
    {
        $allFilms=$filmRepository->findAll();


        return new JsonResponse(["message"=>$message,"code"=>$code],$http);
    }
}
