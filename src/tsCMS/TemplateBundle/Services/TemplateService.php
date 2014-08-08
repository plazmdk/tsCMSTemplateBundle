<?php
/**
 * Created by PhpStorm.
 * User: plazm
 * Date: 4/16/14
 * Time: 5:04 PM
 */

namespace tsCMS\TemplateBundle\Services;


use Doctrine\ORM\EntityManager;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\Translator;
use tsCMS\SystemBundle\Event\BuildSiteStructureEvent;
use tsCMS\SystemBundle\Model\SiteStructureAction;
use tsCMS\SystemBundle\Model\SiteStructureGroup;
use tsCMS\TemplateBundle\Entity\Template;
use tsCMS\TemplateBundle\Event\GetTemplateTypesEvent;
use tsCMS\TemplateBundle\Model\TemplateType;
use tsCMS\TemplateBundle\tsCMSTemplateEvents;

class TemplateService {
    /** @var \Doctrine\ORM\EntityManager  */
    private $em;
    /** @var RouterInterface */
    private $router;
    /** @var EventDispatcherInterface */
    private $eventDispatcher;
    /** @var \Symfony\Component\Translation\Translator */
    private $translator;
    /** @var \Twig_Environment */
    private $twig;


    function __construct(EntityManager $em, RouterInterface $router, EventDispatcherInterface $eventDispatcher, Translator $translator, \Twig_Environment $environment)
    {
        $this->em = $em;
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->translator = $translator;
        $this->twig = $environment;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    public function getRouter()
    {
        return $this->router;
    }


    public function onBuildSiteStructure(BuildSiteStructureEvent $event) {
        $pagesElement = new SiteStructureGroup("Skabeloner","fa-pencil-square-o");
        $pagesElement->addElement(new SiteStructureAction("Skabeloner",$this->getRouter()->generate("tscms_template_template_index")));

        $event->addElement($pagesElement);
    }


    /**
     * @return \tsCMS\TemplateBundle\Model\TemplateType[]
     */
    public function getTemplateTypes() {
        $getTemplateTypesEvent = new GetTemplateTypesEvent();

        $masterType = new TemplateType("MASTER", $this->translator->trans("template.masterTemplate"), array(
            "content" => "<h1>Test title</h1><p>And a lot of content, here be content, to show that this is the place for the content. A placeholder for content some would call it. Lovely right?</p>"
        ));

        $getTemplateTypesEvent->addTemplateType($masterType);
        $this->eventDispatcher->dispatch(tsCMSTemplateEvents::GET_TEMPLATE_TYPES, $getTemplateTypesEvent);

        return $getTemplateTypesEvent->getTemplateTypes();
    }

    /**
     * @param array $restrictTypes
     * @return Template[]
     */
    public function getTemplates($restrictTypes = null) {
        $qb = $this->em->createQueryBuilder();
        $qb->select("t");
        $qb->from("tsCMSTemplateBundle:Template","t");

        if ($restrictTypes !== null) {
            if (!is_array($restrictTypes)) {
                $restrictTypes = array($restrictTypes);
            }

            $qb->where("t.type IN (:types)");
            $qb->setParameter("types", $restrictTypes);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $id
     * @return Template
     */
    public function getTemplate($id) {
        return $this->em->getRepository("tsCMSTemplateBundle:Template")->find($id);
    }

    /**
     * @param Template $template
     * @param $params
     * @return string
     */
    public function renderTemplate(Template $template, $params) {
        $twig = clone $this->twig;
        $twig->setLoader(new \Twig_Loader_String());
        $twig->disableDebug();
        $twig->disableStrictVariables();

        $content = $twig->render($template->getContent(), $params);

        if ($template->getMaster() != null) {
            return $this->renderTemplate($template->getMaster(),array(
                "content" => $content
            ));
        }
        return $content;
    }
} 