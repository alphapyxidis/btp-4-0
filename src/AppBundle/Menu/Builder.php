<?php
// src/AppBundle/Menu/Builder.php
namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('Menu principal');

        $menu->addChild('Accueil', ['route' => 'demo']);

        // create another menu item
        $menu->addChild('Chantiers', ['route' => 'chantier_index']);
        // you can also add sub levels to your menus as follows
        $menu['Chantiers']->addChild('Tous', ['route' => 'chantier_index']);
        $menu['Chantiers']->addChild('Ajouter', ['route' => 'chantier_new']);
        $submenu = $menu['Chantiers']->addChild('Exemples');
        $submenu->addChild('exemple chantier', [
            'route' => 'chantier_show',
            'routeParameters' => ['slug' => 'unite-de-production-premurs']
        ]);
        $submenu->addChild('exemple PIC', ['route' => 'pic']);
        $menu['Chantiers']->addChild('Autour de moi', ['route' => 'demo']);
        $menu->addChild('Documents', ['route' => 'document_index']);
        $menu['Documents']->addChild('Tous', ['route' => 'document_index']);
        $menu['Documents']->addChild('Ajouter', ['route' => 'document_new']);
        return $menu;
    }
}
?>