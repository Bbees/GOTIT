<?php

/*
 * This file is part of the E3sBundle.
 *
 * Copyright (c) 2018 Philippe Grison <philippe.grison@mnhn.fr>
 *
 * E3sBundle is free software : you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * 
 * E3sBundle is distributed in the hope that it will be useful,but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with E3sBundle.  If not, see <https://www.gnu.org/licenses/>
 * 
 */

namespace Bbees\E3sBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


/**
* ImportIndividu controller.
*
* @Route("importfilespcrchromato")
*/
class ImportFilesPcrChromatoController extends Controller
{
     /**
     * @var string
     */
    private $type_csv;
     
    /**
     * @Route("/", name="importfilespcrchromato_index")
     *    
     */
     public function indexAction(Request $request)
    {    
        $message = ""; 
        // load the ImportFileE3s service
        $importFileE3sService = $this->get('bbees_e3s.import_file_e3s');
        //creation du formulaire
        $form = $this->createFormBuilder()
                ->setMethod('POST')
                ->add('type_csv', ChoiceType::class, array(
                    'choice_translation_domain' => false,
                    'choices'  => array(
                         ' ' => array('Pcr & Chromato' => 'pcr_chromato'),
                         '  ' => array('Etablissement' => 'etablissement',),
                         '   ' => array('Vocabulaire' => 'vocabulaire','Personne' => 'personne',),)
                    ))
                ->add('fichier', FileType::class)
                ->add('envoyer', SubmitType::class, array('label' => 'Envoyer'))
                ->getForm();
        $form->handleRequest($request);
        
        if ($form->isSubmitted()){ //processing form request 
            $fichier = $form->get('fichier')->getData()->getRealPath(); // path to the tmp file created
            $this->type_csv = $form->get('type_csv')->getData();   
            $nom_fichier_download = $form->get('fichier')->getData()->getClientOriginalName();
            $message = "Import : ".$nom_fichier_download."<br />";
            switch ($this->type_csv) {
                case 'pcr_chromato':
                    $message .= $importFileE3sService->importCSVDataPcrChromato($fichier);
                    break;
                case 'pcr':
                    $message .= $importFileE3sService->importCSVDataPcr($fichier);
                    break;
                case 'chromato':
                    $message .= $importFileE3sService->importCSVDataChromato($fichier);
                    break;
                case 'vocabulaire':
                    $message .= $importFileE3sService->importCSVDataVoc($fichier);
                    break;
                case 'etablissement' :
                    $message .= $importFileE3sService->importCSVDataEtablissement($fichier);
                    break;
                case 'personne' :
                    $message .= $importFileE3sService->importCSVDataPersonne($fichier);
                    break;
                default:
                   $message .= "Le choix de la liste de fichier à importer ne correspond a aucun cas ?";
            }
            return $this->render('importfilecsv/importfiles.html.twig', array("message" => $message, 'form' => $form->createView())); 
        }
        return $this->render('importfilecsv/importfiles.html.twig', array("message" => $message,'form' => $form->createView()));
     }
    
}
