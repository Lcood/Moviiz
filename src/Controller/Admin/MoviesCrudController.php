<?php

namespace App\Controller\Admin;

use App\Entity\Movies;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class MoviesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Movies::class;
    }

     public function configureFields(string $pageName): iterable
    {
       $mappingsParam = $this->getParameter('vich_uploader.mappings');
       $moviesImagePath = $mappingsParam['movies']['uri_prefix'];


       yield TextField::new('name', 'Nom');
       yield DateField::new('released_year', 'Date de sortie');
       yield TimeField::new('duration', 'Durée');
       yield TextEditorField::new('synosis', 'Résumé');
       yield TextAreaField::new('imageFile')->setFormType(VichImageType::class)->hideOnIndex();
       yield ImageField::new('imageName')->setBasePath($moviesImagePath)->hideOnForm();
       yield AssociationField::new('directors', 'Realisateurs');
       yield AssociationField::new('genres', 'Genre');



    }
}
