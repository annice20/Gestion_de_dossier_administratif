<?php

namespace App\DataFixtures;

use App\Entity\RequestType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RequestTypeFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            [
                'code' => 'ACTE_NAISSANCE',
                'libelle' => 'Acte de naissance',
                'schema_formulaire' => [
                    'nom' => 'string',
                    'prenom' => 'string',
                    'date_naissance' => 'date',
                    'lieu_naissance' => 'string',
                ],
                'piece_requise' => [
                    'documents' => ['Livre de famille', 'Résidence'],
                ],
                'delais_cible' => 2,
                'workflow' => [
                    'etapes' => ['dépôt', 'validation', 'impression', 'retrait'],
                ],
            ],
            [
                'code' => 'ACTE_MARIAGE',
                'libelle' => 'Acte de mariage',
                'schema_formulaire' => [
                    'nom_epoux' => 'string',
                    'prenom_epoux' => 'string',
                    'nom_epouse' => 'string',
                    'prenom_epouse' => 'string',
                    'date_mariage' => 'date',
                    'lieu_mariage' => 'string',
                ],
                'piece_requise' => [
                    'documents' => ['Acte de naissance', 'Résidence', 'Acte de célibat'],
                ],
                'delais_cible' => 1,
                'workflow' => [
                    'etapes' => ['dépôt', 'validation', 'célébration', 'enregistrement', 'retrait'],
                ],
            ],
            [
                'code' => 'ACTE_DECES',
                'libelle' => 'Acte de décès',
                'schema_formulaire' => [
                    'nom_defunt' => 'string',
                    'prenom_defunt' => 'string',
                    'date_deces' => 'date',
                    'lieu_deces' => 'string',
                ],
                'piece_requise' => [
                    'documents' => ['Certificat de décès', 'Pièce d\'identité du demandeur', 'Informations sur le défunt', 'Livret de famille'],
                ],
                'delais_cible' => 24,
                'workflow' => [
                    'etapes' => ['déclaration', 'validation', 'enregistrement', 'retrait'],
                ],
            ],
        ];

        foreach ($data as $requestData) {
            $requestType = new RequestType();
            $requestType->setCode($requestData['code']);
            $requestType->setLibelle($requestData['libelle']);
            $requestType->setSchemaFormulaire($requestData['schema_formulaire']);
            $requestType->setPieceRequise($requestData['piece_requise']);
            $requestType->setDelaisCible($requestData['delais_cible']);
            $requestType->setWorkflow($requestData['workflow']);

            $manager->persist($requestType);
        }

        $manager->flush();
    }
}