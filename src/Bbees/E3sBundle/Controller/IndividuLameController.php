<?php

namespace Bbees\E3sBundle\Controller;

use Bbees\E3sBundle\Entity\IndividuLame;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Bbees\E3sBundle\Services\GenericFunctionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Individulame controller.
 *
 * @Route("individulame")
 * @Security("has_role('ROLE_INVITED')")
 */
class IndividuLameController extends Controller
{
    /**
     * Lists all individuLame entities.
     *
     * @Route("/", name="individulame_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $individuLames = $em->getRepository('BbeesE3sBundle:IndividuLame')->findAll();

        return $this->render('individulame/index.html.twig', array(
            'individuLames' => $individuLames,
        ));
    }

        
    /**
     * Retourne au format json un ensemble de champs à afficher tab_collecte_toshow avec les critères suivant :  
     * a) 1 critère de recherche ($request->get('searchPhrase')) insensible à la casse appliqué à un champ (ex. codeCollecte)
     * b) le nombre de lignes à afficher ($request->get('rowCount'))
     * c) 1 critère de tri sur un collone  ($request->get('sort'))
     *
     * @Route("/indexjson", name="individulame_indexjson")
     * @Method("POST")
     */
    public function indexjsonAction(Request $request)
    {
        // recuperation des services
        $service = $this->get('bbees_e3s.generic_function_e3s');       
        $em = $this->getDoctrine()->getManager();
        //
        $rowCount = ($request->get('rowCount')  !== NULL) ? $request->get('rowCount') : 10;
        $orderBy = ($request->get('sort')  !== NULL) ? $request->get('sort') : array('individuLame.dateMaj' => 'desc', 'individuLame.id' => 'desc');  
        $minRecord = intval($request->get('current')-1)*$rowCount;
        $maxRecord = $rowCount; 
        // initialise la variable searchPhrase suivant les cas et définit la condition du where suivant les conditions sur le parametre d'url idFk
        $where = 'LOWER(individu.codeIndTriMorpho) LIKE :criteriaLower';
        $searchPhrase = $request->get('searchPhrase');
        if ( $request->get('searchPatern') !== null && $request->get('searchPatern') !== '' && $searchPhrase == '') {
            $searchPhrase = $request->get('searchPatern');
        }
        if ( $request->get('idFk') !== null && $request->get('idFk') !== '') {
            $where .= ' AND individuLame.individuFk = '.$request->get('idFk');
        }
        // Recherche de la liste des lots à montrer
        $tab_toshow =[];
        $toshow = $em->getRepository("BbeesE3sBundle:IndividuLame")->createQueryBuilder('individuLame')
            ->where($where)
            ->setParameter('criteriaLower', strtolower($searchPhrase).'%')
            ->leftJoin('BbeesE3sBundle:Individu', 'individu', 'WITH', 'individuLame.individuFk = individu.id')
            ->leftJoin('BbeesE3sBundle:Boite', 'boite', 'WITH', 'individuLame.boiteFk = boite.id')
            ->leftJoin('BbeesE3sBundle:LotMateriel', 'lotMateriel', 'WITH', 'individu.lotMaterielFk = lotMateriel.id')
            ->leftJoin('BbeesE3sBundle:Voc', 'vocTypeIndividu', 'WITH', 'individu.typeIndividuVocFk = vocTypeIndividu.id')
            ->addOrderBy(array_keys($orderBy)[0], array_values($orderBy)[0])
            ->getQuery()
            ->getResult();
        $nb = count($toshow);
        $toshow = array_slice($toshow, $minRecord, $rowCount);  
        $lastTaxname = '';
        foreach($toshow as $entity)
        {
            $id = $entity->getId();
            // initialisation des variables user : userCreId, userMajId , userCre, userMaj
            $userCreId = ($entity->getUserCre() !== null) ? $entity->getUserCre() : 0;
            $query = $em->createQuery('SELECT user.username FROM BbeesUserBundle:User user WHERE user.id = '.$userCreId.'')->getResult();
            $userCre = (count($query) > 0) ? $query[0]['username'] : 'NA';
            $userMajId = ($entity->getUserMaj() !== null) ? $entity->getUserMaj() : 0;
            $query = $em->createQuery('SELECT user.username FROM BbeesUserBundle:User user WHERE user.id = '.$userMajId.'')->getResult();
            $userMaj = (count($query) > 0) ? $query[0]['username'] : 'NA';
            //
            $idIndividu = $entity->getIndividuFk()->getId();
            $codeBoite = ($entity->getBoiteFk() !== null) ?  $entity->getBoiteFk()->getCodeBoite() : null;
            $DateLame = ($entity->getDateLame() !== null) ?  $entity->getDateLame()->format('Y-m-d') : null;
            $DateMaj = ($entity->getDateMaj() !== null) ?  $entity->getDateMaj()->format('Y-m-d H:i:s') : null;
            $DateCre = ($entity->getDateCre() !== null) ?  $entity->getDateCre()->format('Y-m-d H:i:s') : null;
             // récuparation du premier taxon identifié            
            $query = $em->createQuery('SELECT ei.id, ei.dateIdentification, rt.taxname as taxname, voc.code as codeIdentification FROM BbeesE3sBundle:EspeceIdentifiee ei JOIN ei.referentielTaxonFk rt JOIN ei.critereIdentificationVocFk voc WHERE ei.individuFk = '.$idIndividu.' ORDER BY ei.id DESC')->getResult(); 
            $lastTaxname = ($query[0]['taxname'] !== NULL) ? $query[0]['taxname'] : NULL;
            $lastdateIdentification = ($query[0]['dateIdentification']  !== NULL) ? $query[0]['dateIdentification']->format('Y-m-d') : NULL; 
            $codeIdentification = ($query[0]['codeIdentification'] !== NULL) ? $query[0]['codeIdentification'] : NULL;
            // 
            $tab_toshow[] = array("id" => $id, "individuLame.id" => $id, 
             "lotMateriel.codeLotMateriel" => $entity->getIndividuFk()->getLotMaterielFk()->getCodeLotMateriel(),
             "individu.codeIndTriMorpho" => $entity->getIndividuFk()->getCodeIndTriMorpho(),
             "individu.codeIndBiomol" => $entity->getIndividuFk()->getCodeIndBiomol(),
             "individuLame.codeLameColl" => $entity->getCodeLameColl(),
             "individuLame.dateLame" => $DateLame,
             "individu.codeTube" => $entity->getIndividuFk()->getCodeTube(),   
             "vocTypeIndividu.code" => $entity->getIndividuFk()->getTypeIndividuVocFk()->getCode(),  
             "lastTaxname" => $lastTaxname,
             "lastdateIdentification" => $lastdateIdentification,
             "codeIdentification" => $codeIdentification,
             "boite.codeBoite" => $codeBoite,
             "individuLame.nomDossierPhotos" => $entity->getNomDossierPhotos(),
             "individuLame.dateCre" => $DateCre, "individuLame.dateMaj" => $DateMaj,
             "userCreId" => $service->GetUserCreId($entity), "individuLame.userCre" => $service->GetUserCreUsername($entity) ,"individuLame.userMaj" => $service->GetUserMajUsername($entity)
             );
        }     
        // Reponse Ajax
        $response = new Response ();
        $response->setContent ( json_encode ( array (
            "current"    => intval( $request->get('current') ), 
            "rowCount"  => $rowCount,            
            "rows"     => $tab_toshow, 
            "searchPhrase" => $searchPhrase,
            "total"    => $nb // total data array				
            ) ) );
        // Si il s’agit d’un SUBMIT via une requete Ajax : renvoie le contenu au format json
        $response->headers->set('Content-Type', 'application/json');

        return $response;          
    }

    
    /**
     * Creates a new individuLame entity.
     *
     * @Route("/new", name="individulame_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_COLLABORATION')")
     */
    public function newAction(Request $request)
    {
        $individuLame = new Individulame();
        $form = $this->createForm('Bbees\E3sBundle\Form\IndividuLameType', $individuLame);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($individuLame);
            try {
                $em->flush();
            } 
            catch(\Doctrine\DBAL\DBALException $e) {
                $exception_message =  str_replace('"', '\"',str_replace("'", "\'", html_entity_decode(strval($e), ENT_QUOTES , 'UTF-8')));
                return $this->render('individulame/index.html.twig', array('exception_message' =>  explode("\n", $exception_message)[0]));
            } 
            return $this->redirectToRoute('individulame_edit', array('id' => $individuLame->getId(), 'valid' => 1));                       
        }

        return $this->render('individulame/edit.html.twig', array(
            'individuLame' => $individuLame,
            'edit_form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a individuLame entity.
     *
     * @Route("/{id}", name="individulame_show")
     * @Method("GET")
     */
    public function showAction(IndividuLame $individuLame)
    {
        $deleteForm = $this->createDeleteForm($individuLame);
        $editForm = $this->createForm('Bbees\E3sBundle\Form\IndividuLameType', $individuLame);

        return $this->render('show.html.twig', array(
            'individuLame' => $individuLame,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
        
    }

    /**
     * Displays a form to edit an existing individuLame entity.
     *
     * @Route("/{id}/edit", name="individulame_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_COLLABORATION')")
     */
    public function editAction(Request $request, IndividuLame $individuLame)
    {
        // control d'acces sur les  user de type ROLE_COLLABORATION
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        if ($user->getRole() ==  'ROLE_COLLABORATION' && $individuLame->getUserCre() != $user->getId() ) {
                $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'ACCESS DENIED');
        }
        
        // recuperation du service generic_function_e3s
        $service = $this->get('bbees_e3s.generic_function_e3s');       
        // memorisation des ArrayCollection        
        $individuLameEstRealisePars = $service->setArrayCollection('IndividuLameEstRealisePars',$individuLame);
        //
        $deleteForm = $this->createDeleteForm($individuLame);
        $editForm = $this->createForm('Bbees\E3sBundle\Form\IndividuLameType', $individuLame);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // suppression des ArrayCollection 
            $service->DelArrayCollection('IndividuLameEstRealisePars',$individuLame, $individuLameEstRealisePars);
            // flush
            $this->getDoctrine()->getManager()->persist($individuLame);                       
            try {
                $this->getDoctrine()->getManager()->flush();
            } 
            catch(\Doctrine\DBAL\DBALException $e) {
                $exception_message =  str_replace('"', '\"',str_replace("'", "\'", html_entity_decode(strval($e), ENT_QUOTES , 'UTF-8')));
                return $this->render('individulame/index.html.twig', array('exception_message' =>  explode("\n", $exception_message)[0]));
            } 
            return $this->render('individulame/edit.html.twig', array(
                'individuLame' => $individuLame,
                'edit_form' => $editForm->createView(),
                'valid' => 1));
        }       

        return $this->render('individulame/edit.html.twig', array(
            'individuLame' => $individuLame,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a individuLame entity.
     *
     * @Route("/{id}", name="individulame_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_COLLABORATION')")
     */
    public function deleteAction(Request $request, IndividuLame $individuLame)
    {
        $form = $this->createDeleteForm($individuLame);
        $form->handleRequest($request);

        $submittedToken = $request->request->get('token');
        if (($form->isSubmitted() && $form->isValid()) || $this->isCsrfTokenValid('delete-item', $submittedToken) ) {
            $em = $this->getDoctrine()->getManager();
            try {
                $em->remove($individuLame);
                $em->flush();
            } 
            catch(\Doctrine\DBAL\DBALException $e) {
                $exception_message =  str_replace('"', '\"',str_replace("'", "\'", html_entity_decode(strval($e), ENT_QUOTES , 'UTF-8')));
                return $this->render('individulame/index.html.twig', array('exception_message' =>  explode("\n", $exception_message)[0]));
            }   
        }
        return $this->redirectToRoute('individulame_index');
    }

    /**
     * Creates a form to delete a individuLame entity.
     *
     * @param IndividuLame $individuLame The individuLame entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(IndividuLame $individuLame)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('individulame_delete', array('id' => $individuLame->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
