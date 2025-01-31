<?php

namespace App\Controller\Admin;

use App\Entity\Category\Category;
use App\Entity\Category\CategoryAttachment;
use App\Entity\Product\Product;
use App\Entity\Product\ProductPrice;
use App\Entity\Product\ProductTag;
use App\Entity\Project\Project;
use App\Entity\Project\ProjectAttachment;
use App\Entity\Project\ProjectTag;
use App\Entity\Review\ReviewProduct;
use App\Entity\Review\ReviewProject;
use App\Entity\Vendor\Vendor;
use App\Entity\Vendor\VendorDocument;
use App\Entity\Vendor\VendorEnGb;
use App\Entity\Vendor\VendorMedia;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;


class DashboardController extends AbstractDashboardController
{
    // TODO: осмотреть этот репозиторий на предмет полезностей easyAdmin
    // https://github.com/EasyCorp/easyadmin-demo/blob/main/src/Controller/EasyAdmin/FormFieldReferenceController.php

    /*
     * по дизайну сюда
     * https://symfony.com/bundles/EasyAdminBundle/4.x/design.html
     */

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route(path: '/easyadmin', name: 'easyadmin')]
    public function index() : Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($routeBuilder->setController(CategoryEnGbCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {

        return Dashboard::new()
            ->setTitle('ISponsor')
            ->setTitle('<img src="#" alt=""> <span class="text-small">iCorp</span>')
            ->setFaviconPath('public/favicon.ico')
            ->setTextDirection('ltr')
            ->renderContentMaximized()
            # ->renderSidebarMinimized()
            ->disableUrlSignatures()
            ->generateRelativeUrls()
            ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Projects');
        yield MenuItem::linkToCrud('Projects', 'fa fa-tags', Project::class);
//        yield MenuItem::section('ProjectAttachments');
        yield MenuItem::linkToCrud('Projects', 'fa fa-tags', ProjectAttachment::class);
//        yield MenuItem::section('Rewards');
//        yield MenuItem::linkToCrud('Rewards', 'fa fa-tags', ProjectPlatformReward::class);

        yield MenuItem::section('Product');
        yield MenuItem::linkToCrud('Product', 'fa fa-tags', Product::class);
//        yield MenuItem::section('Price');
        yield MenuItem::linkToCrud('Price', 'fa fa-tags', ProductPrice::class);

        yield MenuItem::section('Users|Vendors');
        yield MenuItem::linkToCrud('Vendors', 'fa fa-tags', Vendor::class);
//        yield MenuItem::section('VendorAttachments');
        yield MenuItem::linkToCrud('VendorDocument', 'fa fa-tags', VendorDocument::class);
        yield MenuItem::linkToCrud('VendorMedia', 'fa fa-tags', VendorMedia::class);

        yield MenuItem::section('Categories');
        yield MenuItem::linkToCrud('Categories', 'fa fa-tags', Category::class);
//        yield MenuItem::section('CategoryAttachments');
        yield MenuItem::linkToCrud('CategoryAttachment', 'fa fa-tags', CategoryAttachment::class);

        yield MenuItem::section('Reviews');
        yield MenuItem::linkToCrud('ProductReviews', 'fa fa-tags', ReviewProduct::class);
        yield MenuItem::linkToCrud('ProjectReviews', 'fa fa-tags', ReviewProject::class);

        yield MenuItem::section('Tags');
        yield MenuItem::linkToCrud('ProjectTags', 'fa fa-tags', ProjectTag::class);
        yield MenuItem::linkToCrud('ProductTags', 'fa fa-tags', ProductTag::class);

        yield MenuItem::section('Events');

        yield MenuItem::linkToLogout('Logout', 'fa fa-exit');
        yield MenuItem::linkToExitImpersonation('Stop impersonation', 'fa fa-exit');

    }

    public function configureUserMenu(UserInterface $vendor): UserMenu
    {
        return parent::configureUserMenu($vendor)
            ->setName($vendor->getUserIdentifier());
//        // use the given $user object to get the user name
//        ->setName($user->getFullName())
//        // use this method if you don't want to display the name of the user
//        ->displayUserName(false)
//
//        // you can return an URL with the avatar image
//        ->setAvatarUrl('https://...')
//        ->setAvatarUrl($user->getProfileImageUrl())
//        // use this method if you don't want to display the user image
//        ->displayUserAvatar(false)
//        // you can also pass an email address to use gravatar's service
//        ->setGravatarEmail($user->getMainEmailAddress())
//
//        // you can use any type of menu item, except submenus
//        ->addMenuItems([
//            MenuItem::linkToRoute('My Profile', 'fa fa-id-card', '...', ['...' => '...']),
//            MenuItem::linkToRoute('Settings', 'fa fa-user-cog', '...', ['...' => '...']),
//            MenuItem::section(),
//            MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
//        ]);
    }

}
