<?php
/**
 * Created by PhpStorm.
 * User: plazm
 * Date: 7/28/14
 * Time: 6:47 PM
 */

namespace tsCMS\TemplateBundle\Model;


class TemplateType {
    private $key;
    private $name;
    private $exampleData;

    function __construct($key, $name,$exampleData)
    {
        $this->key = $key;
        $this->name = $name;
        $this->exampleData = $exampleData;
    }

    /**
     * @return mixed
     */
    public function getExampleData()
    {
        return $this->exampleData;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
} 