<?php

namespace Bbees\E3sBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MotuEstGenerePar
 *
 * @ORM\Table(name="motu_est_genere_par", indexes={@ORM\Index(name="IDX_17A90EA3503B4409", columns={"motu_fk"}), @ORM\Index(name="IDX_17A90EA3B53CD04C", columns={"personne_fk"})})
 * @ORM\Entity
 */
class MotuEstGenerePar
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="motu_est_genere_par_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_cre", type="datetime", nullable=true)
     */
    private $dateCre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_maj", type="datetime", nullable=true)
     */
    private $dateMaj;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_cre", type="bigint", nullable=true)
     */
    private $userCre;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_maj", type="bigint", nullable=true)
     */
    private $userMaj;

    /**
     * @var \Motu
     *
     * @ORM\ManyToOne(targetEntity="Motu", inversedBy="motuEstGenerePars")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="motu_fk", referencedColumnName="id")
     * })
     */
    private $motuFk;

    /**
     * @var \Personne
     *
     * @ORM\ManyToOne(targetEntity="Personne")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="personne_fk", referencedColumnName="id")
     * })
     */
    private $personneFk;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateCre
     *
     * @param \DateTime $dateCre
     *
     * @return MotuEstGenerePar
     */
    public function setDateCre($dateCre)
    {
        $this->dateCre = $dateCre;

        return $this;
    }

    /**
     * Get dateCre
     *
     * @return \DateTime
     */
    public function getDateCre()
    {
        return $this->dateCre;
    }

    /**
     * Set dateMaj
     *
     * @param \DateTime $dateMaj
     *
     * @return MotuEstGenerePar
     */
    public function setDateMaj($dateMaj)
    {
        $this->dateMaj = $dateMaj;

        return $this;
    }

    /**
     * Get dateMaj
     *
     * @return \DateTime
     */
    public function getDateMaj()
    {
        return $this->dateMaj;
    }

    /**
     * Set userCre
     *
     * @param integer $userCre
     *
     * @return MotuEstGenerePar
     */
    public function setUserCre($userCre)
    {
        $this->userCre = $userCre;

        return $this;
    }

    /**
     * Get userCre
     *
     * @return integer
     */
    public function getUserCre()
    {
        return $this->userCre;
    }

    /**
     * Set userMaj
     *
     * @param integer $userMaj
     *
     * @return MotuEstGenerePar
     */
    public function setUserMaj($userMaj)
    {
        $this->userMaj = $userMaj;

        return $this;
    }

    /**
     * Get userMaj
     *
     * @return integer
     */
    public function getUserMaj()
    {
        return $this->userMaj;
    }

    /**
     * Set motuFk
     *
     * @param \Bbees\E3sBundle\Entity\Motu $motuFk
     *
     * @return MotuEstGenerePar
     */
    public function setMotuFk(\Bbees\E3sBundle\Entity\Motu $motuFk = null)
    {
        $this->motuFk = $motuFk;

        return $this;
    }

    /**
     * Get motuFk
     *
     * @return \Bbees\E3sBundle\Entity\Motu
     */
    public function getMotuFk()
    {
        return $this->motuFk;
    }

    /**
     * Set personneFk
     *
     * @param \Bbees\E3sBundle\Entity\Personne $personneFk
     *
     * @return MotuEstGenerePar
     */
    public function setPersonneFk(\Bbees\E3sBundle\Entity\Personne $personneFk = null)
    {
        $this->personneFk = $personneFk;

        return $this;
    }

    /**
     * Get personneFk
     *
     * @return \Bbees\E3sBundle\Entity\Personne
     */
    public function getPersonneFk()
    {
        return $this->personneFk;
    }
}
