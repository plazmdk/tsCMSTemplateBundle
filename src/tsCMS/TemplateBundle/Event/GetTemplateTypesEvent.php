<?php
/**
 * Created by PhpStorm.
 * User: plazm
 * Date: 4/16/14
 * Time: 4:05 PM
 */

namespace tsCMS\TemplateBundle\Event;


use Symfony\Component\EventDispatcher\Event;
use tsCMS\TemplateBundle\Model\TemplateType;

class GetTemplateTypesEvent extends Event {
    private $templateTypes = array();

    /**
     * @return TemplateType[]
     */
    public function getTemplateTypes()
    {
        return $this->templateTypes;
    }

    /**
     * @param $type TemplateType
     */
    public function addTemplateType(TemplateType $type) {
        if (isset($this->templateTypes[$type->getKey()])) {
            throw new \Exception("Template type key: ".$type->getKey()." already added - seems to be a problem!");
        }
        $this->templateTypes[$type->getKey()] = $type;
    }
} 