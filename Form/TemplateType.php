<?php
/**
 * Created by PhpStorm.
 * User: plazm
 * Date: 7/13/14
 * Time: 9:51 PM
 */

namespace tsCMS\TemplateBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use tsCMS\TemplateBundle\Entity\Template;

class TemplateType extends AbstractType {
    /**
     * @var Template
     */
    private $self;

    /**
     * @var \tsCMS\TemplateBundle\Model\TemplateType[]
     */
    private $templateTypes;

    function __construct($self = null, $templateTypes = array())
    {
        $this->self = $self;
        $this->templateTypes = $templateTypes;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array();
        foreach ($this->templateTypes as $templateType) {
            $choices[$templateType->getKey()] = $templateType->getName();
        }
        $self = $this->self;
        $builder
            ->add('title', "text", array(
                'label' => 'template.title',
                'required' => true
            ))
            ->add('type', 'choice', array(
                'label' => 'template.type',
                'required' => true,
                'choices' => $choices
            ))
            ->add('master','entity',array(
                'class'    => 'tsCMSTemplateBundle:Template',
                'required' => false,
                'property' => 'title',
                'query_builder' => function(EntityRepository $er) use($self) {
                        $qb = $er->createQueryBuilder('t');

                        if ($self) {
                            $qb->where("t.id != :id")
                            ->setParameter("id", $self->getId());
                        }

                        return $qb;
                }
            ))
            ->add('content', 'textarea', array(
                'label' => 'template.content',
                'required' => false,
                'attr' => array(
                    'style' => 'height: 180px;'
                )
            ))
            ->add("save","submit",array(
                'label' => 'template.save',
            ));

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'tsCMS\TemplateBundle\Entity\Template'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tscms_templatebundle_template';
    }
} 