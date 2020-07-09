<?php


namespace App\Form;


use App\Entity\Article;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class EditArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('newContent', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'EDIT ARTICLE'])
            ->getForm();
    }

    public function configureOptions($resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'validation_groups' => ['edit'],
        ]);
    }
}