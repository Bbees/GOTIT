<?php

/*
 * This file is part of the E3sBundle.
 *
 * Authors : see information concerning authors of GOTIT project in file AUTHORS.md
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

namespace App\Form;

use App\Form\ActionFormType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonneType extends ActionFormType {
  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('nomPersonne')
      ->add('nomComplet', null, [
        'required' => false,
      ])
      ->add('nomPersonneRef', null, [
        'required' => false,
      ])
      ->add('etablissementFk', EntityType::class, [
        'class' => 'App:Etablissement',
        'placeholder' => 'Choose a Etablissement',
        'choice_label' => 'nom_etablissement',
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('institution')
            ->orderBy('institution.nomEtablissement');
        },
        'multiple' => false,
        'expanded' => false,
        'required' => false,
      ])
      ->add('commentairePersonne');

    $builder->addEventSubscriber($this->addUserDate);

    $this->upperCaseFields($builder, [
      'nomPersonne', 'nomComplet', 'nomPersonneRef',
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver) {
    parent::configureOptions($resolver);
    $resolver->setDefaults(array(
      'data_class' => 'App\Entity\Personne',
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function getBlockPrefix() {
    return 'bbees_e3sbundle_personne';
  }
}
