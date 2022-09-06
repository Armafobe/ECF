<?php

namespace App\Controller\Admin;

use App\Entity\Franchise;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FranchiseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Franchise::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            EmailField::new('email'),
            TextField::new('password')
        ];
    }

}
