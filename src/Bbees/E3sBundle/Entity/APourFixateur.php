<?php

namespace Bbees\E3sBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * APourFixateur
 *
 * @ORM\Table(name="a_pour_fixateur", indexes={@ORM\Index(name="IDX_60129A315FD841AC", columns={"fixateur_voc_fk"}), @ORM\Index(name="IDX_60129A31662D9B98", columns={"collecte_fk"})})
 * @ORM\Entity
 */
class APourFixateur
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="a_pour_fixateur_id_seq", allocationSize=1, initialValue=1)
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
     * @var \Voc
     *
     * @ORM\ManyToOne(targetEntity="Voc")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fixateur_voc_fk", referencedColumnName="id")
     * })
     */
    private $fixateurVocFk;

    /**
     * @var \Collecte
     *
     * @ORM\ManyToOne(targetEntity="Collecte", inversedBy="aPourFixateurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="collecte_fk", referencedColumnName="id")
     * })
     */
    private $collecteFk;



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
     * @return APourFixateur
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
     * @return APourFixateur
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
     * @return APourFixateur
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
     * @return APourFixateur
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
     * Set fixateurVocFk
     *
     * @param \Bbees\E3sBundle\Entity\Voc $fixateurVocFk
     *
     * @return APourFixateur
     */
    public function setFixateurVocFk(\Bbees\E3sBundle\Entity\Voc $fixateurVocFk = null)
    {
        $this->fixateurVocFk = $fixateurVocFk;

        return $this;
    }

    /**
     * Get fixateurVocFk
     *
     * @return \Bbees\E3sBundle\Entity\Voc
     */
    public function getFixateurVocFk()
    {
        return $this->fixateurVocFk;
    }

    /**
     * Set collecteFk
     *
     * @param \Bbees\E3sBundle\Entity\Collecte $collecteFk
     *
     * @return APourFixateur
     */
    public function setCollecteFk(\Bbees\E3sBundle\Entity\Collecte $collecteFk = null)
    {
        $this->collecteFk = $collecteFk;

        return $this;
    }

    /**
     * Get collecteFk
     *
     * @return \Bbees\E3sBundle\Entity\Collecte
     */
    public function getCollecteFk()
    {
        return $this->collecteFk;
    }
}
