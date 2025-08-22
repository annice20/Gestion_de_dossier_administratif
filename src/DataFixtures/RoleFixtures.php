<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $roles = [
            ['code' => 'ADMIN', 'libelle' => 'Administration'],
            ['code' => 'USER',  'libelle' => 'Citoyen'],
        ];

        foreach ($roles as $data) {
            // Vérifie si un rôle avec ce code existe déjà
            $existing = $manager->getRepository(Role::class)->findOneBy(['code' => $data['code']]);
            
            if (!$existing) {
                $role = new Role();
                $role->setCode($data['code']);
                $role->setLibelle($data['libelle']);
                $manager->persist($role);
            }
        }

        $manager->flush();
    }
}
