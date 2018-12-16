<?php

namespace Bbees\E3sBundle\Controller\requetes;

use Bbees\E3sBundle\Entity\Motu;
use Bbees\E3sBundle\Entity\Voc;
use Bbees\E3sBundle\Services\QueryBuilderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Controller pour les requêtes sur l'assignation des MOTUs et leur comptage
 *
 * @Route("/queries/assign-motu")
 * @Security("has_role('ROLE_INVITED')")
 */
class AssignationMotuController extends Controller {

  /**
   * @Route("/", name="assign-motu")
   *
   * Rendu du template de la page
   */
  public function index(QueryBuilderService $service) {
    // Get services
    $doctrine = $this->getDoctrine();

    # obtention de la liste des genres
    $genus_set = $service->getGenusSet();
    # obtention de la liste des critères d'identification
    $criteres = $doctrine->getRepository(Voc::class)->findByParent('critereIdentification');
    # liste des jeux de données
    $datasets = $doctrine->getRepository(Motu::class)->findAll();

    # Renvoyer le template et les paramètres
    return $this->render('requetes/assign-motu/index.html.twig', array(
      'genus_set' => $genus_set,
      'criteres'  => $criteres,
      'datasets'  => $datasets,
    ));
  }

  /**
   * @Route("/query", name="motu-query")
   *
   * Renvoie un objet JSON utilisé pour remplir la table de résultats (rows),
   * et des informations supplémentaires indiquant les paramètres de la
   * requêtes initiale (methodes, niveau, criteres)
   */
  public function searchQuery(Request $request, QueryBuilderService $service) {
    # Raccourci requete POST
    $data = $request->request;
    # Obtention des paramètres
    $res      = $service->getMotuCountList($data);
    # Renvoi de la réponse JSON
    return new JsonResponse(array(
      'rows'     => $res,
      'query'    => $data->all(),
    ));
  }

  /**
   * @Route("/detailsModal", name="motu-modal")
   *
   * Controle le rendu de la pop-in modale affichable depuis
   * la colonne Détails de la table de résultats
   */
  public function detailsModal(Request $request, QueryBuilderService $service) {
    # Raccourci requete POST
    $data = $request->request;
    # Obtention de la liste
    $res = $service->getMotuSeqList($data);
    # Processing template de la modal
    return new JsonResponse(array(
      'rows' => $res,
    ));
  }
}
