<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Pays
 *
 * @ORM\Table(name="country",
 *  uniqueConstraints={@ORM\UniqueConstraint(name="uk_country__country_code", columns={"country_code"})})
 * @ORM\Entity
 * @UniqueEntity(fields={"codePays"}, message="This code is already registered")
 * @author Philippe Grison  <philippe.grison@mnhn.fr>
 */
class Pays {
  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="bigint", nullable=false)
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="IDENTITY")
   * @ORM\SequenceGenerator(sequenceName="country_id_seq", allocationSize=1, initialValue=1)
   */
  private $id;

  /**
   * @var string
   *
   * @ORM\Column(name="country_code", type="string", length=255, nullable=false)
   */
  private $codePays;

  /**
   * @var string
   *
   * @ORM\Column(name="country_name", type="string", length=1024, nullable=false)
   */
  private $nomPays;

  /**
   * @ORM\OneToMany(targetEntity="Commune", mappedBy="paysFk")
   * @ORM\OrderBy({"codeCommune" = "asc"})
   */
  private $communes;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="date_of_creation", type="datetime", nullable=true)
   */
  private $dateCre;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="date_of_update", type="datetime", nullable=true)
   */
  private $dateMaj;

  /**
   * @var integer
   *
   * @ORM\Column(name="creation_user_name", type="bigint", nullable=true)
   */
  private $userCre;

  /**
   * @var integer
   *
   * @ORM\Column(name="update_user_name", type="bigint", nullable=true)
   */
  private $userMaj;

  /**
   * @inheritdoc
   */
  public function __construct() {
    $this->communes = new ArrayCollection();
  }

  /**
   * Get id
   *
   * @return integer
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set codePays
   *
   * @param string $codePays
   *
   * @return Pays
   */
  public function setCodePays($codePays) {
    $this->codePays = $codePays;

    return $this;
  }

  /**
   * Get codePays
   *
   * @return string
   */
  public function getCodePays() {
    return $this->codePays;
  }

  /**
   * Set nomPays
   *
   * @param string $nomPays
   *
   * @return Pays
   */
  public function setNomPays($nomPays) {
    $this->nomPays = $nomPays;

    return $this;
  }

  /**
   * Get nomPays
   *
   * @return string
   */
  public function getNomPays() {
    return $this->nomPays;
  }

  public function getCommunes() {
    return $this->communes;
  }

  /**
   * Set dateCre
   *
   * @param \DateTime $dateCre
   *
   * @return Pays
   */
  public function setDateCre($dateCre) {
    $this->dateCre = $dateCre;

    return $this;
  }

  /**
   * Get dateCre
   *
   * @return \DateTime
   */
  public function getDateCre() {
    return $this->dateCre;
  }

  /**
   * Set dateMaj
   *
   * @param \DateTime $dateMaj
   *
   * @return Pays
   */
  public function setDateMaj($dateMaj) {
    $this->dateMaj = $dateMaj;

    return $this;
  }

  /**
   * Get dateMaj
   *
   * @return \DateTime
   */
  public function getDateMaj() {
    return $this->dateMaj;
  }

  /**
   * Set userCre
   *
   * @param integer $userCre
   *
   * @return Pays
   */
  public function setUserCre($userCre) {
    $this->userCre = $userCre;

    return $this;
  }

  /**
   * Get userCre
   *
   * @return integer
   */
  public function getUserCre() {
    return $this->userCre;
  }

  /**
   * Set userMaj
   *
   * @param integer $userMaj
   *
   * @return Pays
   */
  public function setUserMaj($userMaj) {
    $this->userMaj = $userMaj;

    return $this;
  }

  /**
   * Get userMaj
   *
   * @return integer
   */
  public function getUserMaj() {
    return $this->userMaj;
  }
}
