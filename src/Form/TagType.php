<?php


namespace App\Form;


use App\Entity\Tag;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', ChoiceType::class, [

            ]);
    }

    public function configureOptions($resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}