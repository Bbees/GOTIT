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
use App\Form\Enums\Action;
use App\Form\Type\CountryVocType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommuneType extends ActionFormType {
  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add('codeCommune', null, [
        'disabled' => $this->canEditAdminOnly($options),
        'attr' => [
          'readonly' => $options['action_type'] == Action::create(),
        ],
      ])
      ->add('nomCommune')
      ->add('nomRegion')
      ->add('paysFk', CountryVocType::class)
      ->addEventSubscriber($this->addUserDate);

    $uppercase_fields = ['codeCommune', 'nomCommune', 'nomRegion'];
    $this->upperCaseFields($builder, $uppercase_fields);
  }

  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver) {
    parent::configureOptions($resolver);
    $resolver->setDefaults(array(
      'data_class' => 'App\Entity\Commune',
      'id_pays' => null,
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function getBlockPrefix() {
    return 'commune';
  }
}
