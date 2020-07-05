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

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', AuthorType::class)
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add('content', TextType::class)
            ->add('image', FileType::class, [
                'label' => 'Image (JPG or PNG)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPEG, JPG or PNG image',
                    ])
                ],
            ])
            ->add('relations', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'ADD ARTICLE'])
            ->getForm();
    }

    public function configureOptions($resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}