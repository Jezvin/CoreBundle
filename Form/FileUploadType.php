<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 06/06/17
 * Time: 19:44
 */

namespace Umbrella\CoreBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrellac\CoreBundle\Entity\UmbrellaFile;
use Umbrellac\CoreBundle\Services\UmbrellaFileUploader;
use Umbrellac\CoreBundle\Utils\ArrayUtils;

/**
 * Class FileUploadType
 */
class FileUploadType extends AbstractType
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UmbrellaFileUploader
     */
    private $manager;

    /**
     * FileUploadType constructor.
     * @param UmbrellaFileUploader $manager
     * @param EntityManagerInterface $em
     */
    public function __construct(UmbrellaFileUploader $manager, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // return UmbrellaFile entity to view
        $view->vars['entity'] = $form->getData();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // hidden widget
        $builder->add('file', FileType::class, array(
            'required' => false,
            'error_bubbling' => true, // pass error to the parent
            'attr' => $options['file_attr'],
            'constraints' => $options['file_constraints']
        ));
        $builder->add('id', TextType::class, array(
            'required' => false
        ));
        $builder->add('delete', CheckboxType::class, array(
            'required' => false
        ));

        // text widget
        $builder->add('text', TextType::class, array(
            'required' => $options['required']
        ));

        $builder->addModelTransformer(new FileUploadTransformer($this->manager, $this->em));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'file_attr' => array(),
            'file_constraints' => array(),
            'error_bubbling' => false // resolve error at this level
        ));

        $resolver->setAllowedTypes('file_attr', 'array');
        $resolver->setAllowedTypes('file_constraints', 'array');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fileupload';
    }
}

/**
 * Class FileUploadTransformer
 */
class FileUploadTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UmbrellaFileUploader
     */
    private $manager;

    /**
     * FileUploadTransformer constructor.
     *
     * @param UmbrellaFileUploader $manager
     * @param EntityManagerInterface $em
     */
    public function __construct(UmbrellaFileUploader $manager, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->manager = $manager;
    }

    /**
     * Transform UmbrellaFile => array
     * @param UmbrellaFile $umbrellaFile
     * @return array
     */
    public function transform($umbrellaFile)
    {
        return array(
            'file' => null,
            'id' => $umbrellaFile ? $umbrellaFile->id : null,
            'text' => $umbrellaFile ? $umbrellaFile->name . ' (' . $umbrellaFile->getHumanSize() .')' : null
        );
    }

    /**
     * Transform array => UmbrellaFile
     *
     * @param array $array
     * @return UmbrellaFile|null
     */
    public function reverseTransform($array)
    {
        $id = ArrayUtils::get($array, 'id', null);
        $umbrellaFile = $id ? $this->em->getRepository(UmbrellaFile::class)->find($id) : null;
        $uploadedFile = ArrayUtils::get($array, 'file', null);
        $delete = ArrayUtils::get($array, 'delete', false);

        if ($umbrellaFile && $uploadedFile) { // update
            $this->em->remove($umbrellaFile);
            return $this->manager->createUmbrellaFile($uploadedFile);
        }

        if ($umbrellaFile && $uploadedFile === null && $delete) { // delete
            $this->em->remove($umbrellaFile);
            return null;
        }

        if ($umbrellaFile === null && $uploadedFile) { // create
            return $this->manager->createUmbrellaFile($uploadedFile);
        }

        // nothing to do
        return $umbrellaFile;
    }
}