<?php

namespace tsCMS\TemplateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use tsCMS\TemplateBundle\Form\TemplateType;
use tsCMS\TemplateBundle\Services\TemplateService;

/**
 * @Route("/template")
 */
class TemplateController extends Controller
{
    /**
     * @Route("")
     * @Secure("ROLE_ADMIN")
     * @Template()
     */
    public function indexAction()
    {
        /** @var TemplateService $templateService */
        $templateService = $this->get("tsCMS_template.templateservice");

        $templateTypes = $templateService->getTemplateTypes();
        $templates = $this->getDoctrine()->getRepository("tsCMSTemplateBundle:Template")->findAll();
        return array(
            'templateTypes' => $templateTypes,
            'templates' => $templates
        );
    }

    /**
     * @Route("/create")
     * @Secure("ROLE_ADMIN")
     * @Template("tsCMSTemplateBundle:Template:template.html.twig")
     */
    public function createAction(Request $request)
    {
        /** @var TemplateService $templateService */
        $templateService = $this->get("tsCMS_template.templateservice");

        $templateTypes = $templateService->getTemplateTypes();

        $template = new \tsCMS\TemplateBundle\Entity\Template();
        $form = $this->createForm(new TemplateType(null, $templateTypes), $template);
        $form->handleRequest($request);
        if ($form->isValid()) {

            $this->getDoctrine()->getManager()->persist($template);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl("tscms_template_template_edit", array("id" => $template->getId())));
        }
        return array(
            "form" => $form->createView()
        );
    }

    /**
     * @Route("/edit/{id}")
     * @Secure("ROLE_ADMIN")
     * @Template("tsCMSTemplateBundle:Template:template.html.twig")
     */
    public function editAction(\tsCMS\TemplateBundle\Entity\Template $template, Request $request)
    {
        /** @var TemplateService $templateService */
        $templateService = $this->get("tsCMS_template.templateservice");

        $templateTypes = $templateService->getTemplateTypes();

        $form = $this->createForm(new TemplateType($template, $templateTypes), $template);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl("tscms_template_template_edit", array("id" => $template->getId())));
        }
        return array(
            "form" => $form->createView()
        );
    }

    /**
     * @Route("/delete/{id}")
     * @Secure("ROLE_ADMIN")
     */
    public function deleteAction(\tsCMS\TemplateBundle\Entity\Template $template, Request $request)
    {
        $this->getDoctrine()->getManager()->remove($template);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl("tscms_template_template_index"));
    }

    /**
     * @Route("/render/preview", name="tscms_template_template_renderTemplatePreview", options={"expose"=true})
     * @Secure("ROLE_ADMIN")
     */
    public function renderTemplatePreviewAction(Request $request) {
        /** @var TemplateService $templateService */
        $templateService = $this->get("tsCMS_template.templateservice");

        $templateTypes = $templateService->getTemplateTypes();

        $template = new \tsCMS\TemplateBundle\Entity\Template();
        $form = $this->createForm(new TemplateType(null, $templateTypes), $template);
        $form->handleRequest($request);

        $content = "";
        if ($form->isValid()) {
            $params = $templateTypes[$template->getType()]->getExampleData();

            $content = $templateService->renderTemplate($template, $params);
        }

        return new Response($content);
    }
}
