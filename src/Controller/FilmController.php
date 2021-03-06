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
            $checkKeyUrlImage=array_key_exists("url_image",$data);

            if($checkKeyName && $checkKeySynopsis && $checkKeyType && $checkKeyUrlImage){ // si les clés existe...

                $nom=htmlspecialchars(trim($data["nom"]));
                $synopsis=htmlspecialchars(trim($data["synopsis"]));
                $type=htmlspecialchars(trim($data["type"]));
                $url=htmlspecialchars(trim($data["url_image"]));

                if(!empty($nom) && !empty($synopsis) && !empty($type) && !empty($url)){//et qu'elles sont pas vides...

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
                    $film->setUrlImage($url);
                    $film->setCreatedAt($date);
                    $entityManager->persist($film);
                    $entityManager->flush(); //on insere les données
                    $code=201;

                    $message="Film ajouté !";
                    $http=Response::HTTP_CREATED;

                }else{
                    $code=400;
                    $message="Le nom, synopsis, url_image ou type est vide ! Veuillez rééssayer !";
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
     * @Route("/getall", name="get_all_film")
     */
    public function get_all_film(FilmRepository $filmRepository): JsonResponse
    {
        $allFilms=$filmRepository->findAll();

        $data=array();
        if(empty($allFilms)){ //On regarde si un film existe
            $code=400;
            $message="Aucun Film trouvé !";
            $http=Response::HTTP_BAD_REQUEST;
        }else{

            foreach ($allFilms as $films){ //si des Films existe alors on les push dans un array pour ensuite les afficher dans la réponse
                array_push($data,["id"=>$films->getId(),"Nom"=>$films->getNom(),"Synopsis"=>$films->getSynopsis(),"Type"=>$films->getType(),"url_image"=>$films->getUrlImage(),"created_at"=>$films->getCreatedAt()]);
            }
            $code=200;
            $message="Liste de tout les films disponibles dans la BDD !";
            $http=Response::HTTP_OK;
        }


        return new JsonResponse(["message"=>$message,"code"=>$code,"data"=>$data],$http);
    }


    /**
     * @Route("/get/{id_item}", name="film_by_id")
     */
    public function film_by_id(FilmRepository $filmRepository,$id_item=null): JsonResponse
    {
        $data = array();

        if($id_item > 0) {

            $film = $filmRepository->find(['id' => $id_item]);
            if (empty($film)) {
                $code = 400;
                $message = "Aucun Film trouvé pour cette id !";
                $http = Response::HTTP_BAD_REQUEST;
            } else {

                $data=["id" => $film->getId(), "Nom" => $film->getNom(), "Synopsis" => $film->getSynopsis(), "Type" => $film->getType(),"url_image"=>$film->getUrlImage(), "created_at" => $film->getCreatedAt()];
                $code = 200;
                $message = "Voici le film trouvé pour l'id ".$id_item;
                $http = Response::HTTP_OK;
            }
        }else{
            $code = 400;
            $message = "Vous devez saisir un id supérieur à 0 ! Aucun film trouver pour cette id";
            $http = Response::HTTP_BAD_REQUEST;
        }



        return new JsonResponse(["message"=>$message,"code"=>$code,"data"=>$data],$http);
    }
}
