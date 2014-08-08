<?php
/**
 * Created by PhpStorm.
 * User: plazm
 * Date: 7/26/14
 * Time: 3:13 PM
 */

namespace tsCMS\TemplateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="template")
 */
class Template {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     */
    protected $title;
    /**
     * @ORM\Column(type="string")
     */
    protected $type;
    /**
     * @ORM\ManyToOne(targetEntity="Template")
     * @ORM\JoinColumn(name="master_id", referencedColumnName="id")
     **/
    protected $master;
    /**
     * @ORM\Column(type="text")
     */
    protected $content;

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Template $master
     */
    public function setMaster($master)
    {
        $this->master = $master;
    }

    /**
     * @return Template
     */
    public function getMaster()
    {
        return $this->master;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }


} 