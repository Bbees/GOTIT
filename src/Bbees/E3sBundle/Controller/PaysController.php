<?php

namespace Bbees\E3sBundle\Controller;

use Bbees\E3sBundle\Entity\Pays;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pay controller.
 *
 * @Route("pays")
 */
class PaysController extends Controller
{
    /**
     * Lists all pay entities.
     *
     * @Route("/", name="pays_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $pays = $em->getRepository('BbeesE3sBundle:Pays')->findAll();

        return $this->render('pays/index.html.twig', array(
            'pays' => $pays,
        ));
    }

    /**
     * Retourne au format json un ensemble de champs à afficher tab_collecte_toshow avec les critères suivant :  
     * a) 1 critère de recherche ($request->get('searchPhrase')) insensible à la casse appliqué à un champ (ex. codeCollecte)
     * b) le nombre de lignes à afficher ($request->get('rowCount'))
     * c) 1 critère de tri sur un collone  ($request->get('sort'))
     *
     * @Route("/indexjson", name="pays_indexjson")
     * @Method("POST")
     */
    public function indexjsonAction(Request $request)
    {
       
        $em = $this->getDoctrine()->getManager();
        
        $rowCount = ($request->get('rowCount')  !== NULL) ? $request->get('rowCount') : 10;
        $orderBy = ($request->get('sort')  !== NULL) ? $request->get('sort') : array('pays.id' => 'desc');  
        $minRecord = intval($request->get('current')-1)*$rowCount;
        $maxRecord = $rowCount; 
        // initialise la variable searchPhrase suivant les cas et définit la condition du where suivant les conditions sur le parametre d'url idFk
        $where = 'LOWER(pays.codePays) LIKE :criteriaLower';
        $searchPhrase = $request->get('searchPhrase');
        if ( $request->get('searchPatern') !== null && $request->get('searchPatern') !== '' && $searchPhrase == '') {
            $searchPhrase = $request->get('searchPatern');
        }
        // Recherche de la liste des lots à montrer
        $tab_toshow =[];
        $toshow = $em->getRepository("BbeesE3sBundle:Pays")->createQueryBuilder('pays')
            ->where($where)
            ->setParameter('criteriaLower', strtolower($searchPhrase).'%')
            ->addOrderBy(array_keys($orderBy)[0], array_values($orderBy)[0])
            ->getQuery()
            ->getResult();
        $nb = count($toshow);
        $toshow = array_slice($toshow, $minRecord, $rowCount);  
        foreach($toshow as $entity)
        {
            $id = $entity->getId();
            $DateMaj = ($entity->getDateMaj() !== null) ?  $entity->getDateMaj()->format('Y-m-d H:i:s') : null;
            $DateCre = ($entity->getDateCre() !== null) ?  $entity->getDateCre()->format('Y-m-d H:i:s') : null;
            //
            $tab_toshow[] = array("id" => $id, "pays.id" => $id, 
             "pays.codePays" => $entity->getCodePays(),
             "pays.nomPays" => $entity->getNomPays(),
             "pays.dateCre" => $DateCre, "pays.dateMaj" => $DateMaj,);
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
     * Creates a new pay entity.
     *
     * @Route("/new", name="pays_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $pays = new Pays();
        $form = $this->createForm('Bbees\E3sBundle\Form\PaysType', $pays);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pays);
            try {
                $em->flush();
            } 
            catch(\Doctrine\DBAL\DBALException $e) {
                $exception_message =  str_replace('"', '\"',str_replace("'", "\'", html_entity_decode(strval($e), ENT_QUOTES , 'UTF-8')));
                return $this->render('pays/index.html.twig', array('exception_message' =>  explode("\n", $exception_message)[0]));
            } 
            return $this->redirectToRoute('pays_edit', array('id' => $pays->getId(), 'valid' => 1));                       
        }

        return $this->render('pays/edit.html.twig', array(
            'pays' => $pays,
            'edit_form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a pay entity.
     *
     * @Route("/{id}", name="pays_show")
     * @Method("GET")
     */
    public function showAction(Pays $pays)
    {
        $deleteForm = $this->createDeleteForm($pays);
        $editForm = $this->createForm('Bbees\E3sBundle\Form\PaysType', $pays);

        return $this->render('show.html.twig', array(
            'pays' => $pays,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing pay entity.
     *
     * @Route("/{id}/edit", name="pays_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Pays $pays)
    {
        $deleteForm = $this->createDeleteForm($pays);
        $editForm = $this->createForm('Bbees\E3sBundle\Form\PaysType', $pays);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            try {
                $this->getDoctrine()->getManager()->flush();
            } 
            catch(\Doctrine\DBAL\DBALException $e) {
                $exception_message =  str_replace('"', '\"',str_replace("'", "\'", html_entity_decode(strval($e), ENT_QUOTES , 'UTF-8')));
                return $this->render('pays/index.html.twig', array('exception_message' =>  explode("\n", $exception_message)[0]));
            } 
            return $this->render('pays/edit.html.twig', array(
                'pays' => $pays,
                'edit_form' => $editForm->createView(),
                'valid' => 1));
        }

        return $this->render('pays/edit.html.twig', array(
            'pays' => $pays,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a pay entity.
     *
     * @Route("/{id}", name="pays_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Pays $pays)
    {
        $form = $this->createDeleteForm($pays);
        $form->handleRequest($request);

        $submittedToken = $request->request->get('token');
        if (($form->isSubmitted() && $form->isValid()) || $this->isCsrfTokenValid('delete-item', $submittedToken) ) {
            $em = $this->getDoctrine()->getManager();
            try {
                $em->remove($pays);
                $em->flush();
            } 
            catch(\Doctrine\DBAL\DBALException $e) {
                $exception_message =  str_replace('"', '\"',str_replace("'", "\'", html_entity_decode(strval($e), ENT_QUOTES , 'UTF-8')));
                return $this->render('pays/index.html.twig', array('exception_message' =>  explode("\n", $exception_message)[0]));
            }   
        }
        
        return $this->redirectToRoute('pays_index');
    }

    /**
     * Creates a form to delete a pay entity.
     *
     * @param Pays $pay The pay entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Pays $pay)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pays_delete', array('id' => $pay->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}