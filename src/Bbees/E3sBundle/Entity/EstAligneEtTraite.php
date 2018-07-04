<?php

namespace Bbees\E3sBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EstAligneEtTraite
 *
 * @ORM\Table(name="est_aligne_et_traite", indexes={@ORM\Index(name="IDX_BD45639EEFCFD332", columns={"chromatogramme_fk"}), @ORM\Index(name="IDX_BD45639E5BE90E48", columns={"sequence_assemblee_fk"})})
 * @ORM\Entity
 */
class EstAligneEtTraite
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="est_aligne_et_traite_id_seq", allocationSize=1, initialValue=1)
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
     * @var \Chromatogramme
     *
     * @ORM\ManyToOne(targetEntity="Chromatogramme")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="chromatogramme_fk", referencedColumnName="id")
     * })
     */
    private $chromatogrammeFk;

    /**
     * @var \SequenceAssemblee
     *
     * @ORM\ManyToOne(targetEntity="SequenceAssemblee", inversedBy="estAligneEtTraites")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sequence_assemblee_fk", referencedColumnName="id")
     * })
     */
    private $sequenceAssembleeFk;



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
     * @return EstAligneEtTraite
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
     * @return EstAligneEtTraite
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
     * @return EstAligneEtTraite
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
     * @return EstAligneEtTraite
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
     * Set chromatogrammeFk
     *
     * @param \Bbees\E3sBundle\Entity\Chromatogramme $chromatogrammeFk
     *
     * @return EstAligneEtTraite
     */
    public function setChromatogrammeFk(\Bbees\E3sBundle\Entity\Chromatogramme $chromatogrammeFk = null)
    {
        $this->chromatogrammeFk = $chromatogrammeFk;

        return $this;
    }

    /**
     * Get chromatogrammeFk
     *
     * @return \Bbees\E3sBundle\Entity\Chromatogramme
     */
    public function getChromatogrammeFk()
    {
        return $this->chromatogrammeFk;
    }

    /**
     * Set sequenceAssembleeFk
     *
     * @param \Bbees\E3sBundle\Entity\SequenceAssemblee $sequenceAssembleeFk
     *
     * @return EstAligneEtTraite
     */
    public function setSequenceAssembleeFk(\Bbees\E3sBundle\Entity\SequenceAssemblee $sequenceAssembleeFk = null)
    {
        $this->sequenceAssembleeFk = $sequenceAssembleeFk;

        return $this;
    }

    /**
     * Get sequenceAssembleeFk
     *
     * @return \Bbees\E3sBundle\Entity\SequenceAssemblee
     */
    public function getSequenceAssembleeFk()
    {
        return $this->sequenceAssembleeFk;
    }
}
