<?php

namespace App\DataFixtures;

use App\Entity\MesureConformite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MesureConformiteFixtures extends Fixture
{
    // Jeu de données NIS2 réel basé sur la directive UE 2022/2555
    private const MESURES = [
        // --- GOUVERNANCE (Art. 21.1) ---
        [
            'module' => 'gouvernance',
            'code' => 'GOV-01',
            'titre' => 'Politique de sécurité des systèmes d\'information',
            'description' => 'Disposer d\'une politique PSSI formalisée, approuvée par la direction et communiquée à l\'ensemble des collaborateurs. Révisée annuellement.',
            'statut' => 'partiel',
            'responsable' => 'RSSI',
        ],
        [
            'module' => 'gouvernance',
            'code' => 'GOV-02',
            'titre' => 'Rôles et responsabilités en matière de sécurité',
            'description' => 'Définir et documenter les rôles, responsabilités et autorités pour la sécurité de l\'information au sein de l\'organisation.',
            'statut' => 'conforme',
            'responsable' => 'DRH / RSSI',
        ],
        [
            'module' => 'gouvernance',
            'code' => 'GOV-03',
            'titre' => 'Formation et sensibilisation des collaborateurs',
            'description' => 'Programme de sensibilisation régulier incluant les cyber-menaces courantes, le phishing, la politique de mots de passe et les procédures de signalement.',
            'statut' => 'non_conforme',
            'responsable' => 'DRH',
            'echeance' => '2026-06-30',
        ],
        [
            'module' => 'gouvernance',
            'code' => 'GOV-04',
            'titre' => 'Audit et contrôle interne de la sécurité',
            'description' => 'Audits internes périodiques de la conformité aux politiques de sécurité, avec reporting à la direction.',
            'statut' => 'non_conforme',
            'responsable' => 'RSSI',
            'echeance' => '2026-09-30',
        ],

        // --- GESTION DES RISQUES (Art. 21.2.a) ---
        [
            'module' => 'gestion_risques',
            'code' => 'RSK-01',
            'titre' => 'Analyse et évaluation des risques cyber',
            'description' => 'Processus formalisé d\'identification, d\'analyse et d\'évaluation des risques liés aux SIC. Inclut les risques métier et techniques.',
            'statut' => 'partiel',
            'responsable' => 'RSSI',
            'echeance' => '2026-07-31',
        ],
        [
            'module' => 'gestion_risques',
            'code' => 'RSK-02',
            'titre' => 'Plan de traitement des risques',
            'description' => 'Pour chaque risque identifié, définir une stratégie de traitement (acceptation, réduction, transfert, évitement) avec un responsable et une échéance.',
            'statut' => 'non_conforme',
            'responsable' => 'RSSI / DSI',
            'echeance' => '2026-09-30',
        ],
        [
            'module' => 'gestion_risques',
            'code' => 'RSK-03',
            'titre' => 'Gestion des actifs informationnels',
            'description' => 'Inventaire exhaustif et à jour des actifs (matériels, logiciels, données) avec classification par niveau de sensibilité.',
            'statut' => 'partiel',
            'responsable' => 'DSI',
        ],

        // --- INCIDENTS (Art. 21.2.b et Art. 23) ---
        [
            'module' => 'incidents',
            'code' => 'INC-01',
            'titre' => 'Procédure de détection et signalement des incidents',
            'description' => 'Procédure documentée pour la détection, le triage et le signalement interne des incidents de sécurité.',
            'statut' => 'conforme',
            'responsable' => 'SOC / RSSI',
        ],
        [
            'module' => 'incidents',
            'code' => 'INC-02',
            'titre' => 'Notification aux autorités compétentes (ANSSI)',
            'description' => 'Processus de notification à l\'ANSSI dans les délais NIS2 : préalerte 24h, notification initiale 72h, rapport final 1 mois.',
            'statut' => 'non_conforme',
            'responsable' => 'RSSI / Direction',
            'echeance' => '2026-05-31',
        ],
        [
            'module' => 'incidents',
            'code' => 'INC-03',
            'titre' => 'Réponse aux incidents et forensique',
            'description' => 'Plan de réponse aux incidents formalisé avec équipes, outils et procédures de forensique numérique pour préserver les preuves.',
            'statut' => 'partiel',
            'responsable' => 'SOC',
            'echeance' => '2026-07-31',
        ],

        // --- SUPPLY CHAIN (Art. 21.2.d) ---
        [
            'module' => 'supply_chain',
            'code' => 'SUP-01',
            'titre' => 'Sécurité des fournisseurs et sous-traitants',
            'description' => 'Clauses contractuelles de sécurité obligatoires dans tous les contrats fournisseurs. Évaluation périodique du niveau de sécurité.',
            'statut' => 'partiel',
            'responsable' => 'Achats / RSSI',
            'echeance' => '2026-10-31',
        ],
        [
            'module' => 'supply_chain',
            'code' => 'SUP-02',
            'titre' => 'Inventaire et évaluation des fournisseurs critiques',
            'description' => 'Cartographie des fournisseurs avec qualification du niveau de criticité et risque associé pour la continuité de service.',
            'statut' => 'non_conforme',
            'responsable' => 'DSI / Achats',
            'echeance' => '2026-08-31',
        ],

        // --- CONTINUITE (Art. 21.2.c) ---
        [
            'module' => 'continuite',
            'code' => 'CON-01',
            'titre' => 'Plan de continuité d\'activité (PCA)',
            'description' => 'PCA documenté, testé et mis à jour annuellement, couvrant les scénarios de cyberattaque (ransomware, DDoS).',
            'statut' => 'partiel',
            'responsable' => 'DSI / Direction',
            'echeance' => '2026-12-31',
        ],
        [
            'module' => 'continuite',
            'code' => 'CON-02',
            'titre' => 'Plan de reprise d\'activité (PRA) et sauvegardes',
            'description' => 'Stratégie de sauvegarde 3-2-1, RTO et RPO définis et testés. Procédures de restauration validées.',
            'statut' => 'conforme',
            'responsable' => 'DSI',
        ],

        // --- CRYPTOGRAPHIE (Art. 21.2.h) ---
        [
            'module' => 'cryptographie',
            'code' => 'CRY-01',
            'titre' => 'Politique d\'utilisation de la cryptographie',
            'description' => 'Politique définissant les algorithmes autorisés (AES-256, RSA-4096, SHA-256 minimum), la gestion des clés et les cas d\'usage.',
            'statut' => 'non_conforme',
            'responsable' => 'RSSI',
            'echeance' => '2026-06-30',
        ],
        [
            'module' => 'cryptographie',
            'code' => 'CRY-02',
            'titre' => 'Chiffrement des données sensibles au repos et en transit',
            'description' => 'Toutes les données sensibles chiffrées en base de données et lors des transferts (TLS 1.3 minimum). Certificats gérés et renouvelés.',
            'statut' => 'conforme',
            'responsable' => 'DSI',
        ],

        // --- CONTROLE D'ACCES (Art. 21.2.i) ---
        [
            'module' => 'controle_acces',
            'code' => 'ACC-01',
            'titre' => 'Gestion des identités et des accès (IAM)',
            'description' => 'Principe du moindre privilège appliqué. Revue périodique des droits d\'accès. Procédure d\'arrivee/départ formalisée.',
            'statut' => 'partiel',
            'responsable' => 'DSI / DRH',
            'echeance' => '2026-07-31',
        ],
        [
            'module' => 'controle_acces',
            'code' => 'ACC-02',
            'titre' => 'Authentification multi-facteurs (MFA)',
            'description' => 'MFA obligatoire pour tous les accès distants, les comptes à privilèges et les accès aux systèmes critiques.',
            'statut' => 'partiel',
            'responsable' => 'DSI',
            'echeance' => '2026-06-30',
        ],
        [
            'module' => 'controle_acces',
            'code' => 'ACC-03',
            'titre' => 'Gestion des comptes à privilèges (PAM)',
            'description' => 'Inventaire et contrôle strict des comptes administrateurs. Enregistrement des sessions privilégiées. Solution PAM recommandée.',
            'statut' => 'non_conforme',
            'responsable' => 'DSI',
            'echeance' => '2026-09-30',
        ],

        // --- VULNERABILITES (Art. 21.2.e) ---
        [
            'module' => 'vulnerabilites',
            'code' => 'VUL-01',
            'titre' => 'Gestion des vulnérabilités et des correctifs',
            'description' => 'Processus de veille, détection et remédiation des vulnérabilités. Patching critique sous 72h, majeur sous 30 jours.',
            'statut' => 'partiel',
            'responsable' => 'DSI',
            'echeance' => '2026-06-30',
        ],
        [
            'module' => 'vulnerabilites',
            'code' => 'VUL-02',
            'titre' => 'Tests d\'intrusion et audits techniques',
            'description' => 'Tests de pénétration annuels par un prestataire qualifié PASSI. Revue des résultats et suivi du plan de remédiation.',
            'statut' => 'non_conforme',
            'responsable' => 'RSSI',
            'echeance' => '2026-12-31',
        ],
        [
            'module' => 'vulnerabilites',
            'code' => 'VUL-03',
            'titre' => 'Surveillance et détection (SOC / SIEM)',
            'description' => 'Supervision en continu des événements de sécurité via un SIEM. Définition des cas d\'usage de détection et des seuils d\'alerte.',
            'statut' => 'non_conforme',
            'responsable' => 'SOC / DSI',
            'echeance' => '2026-12-31',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::MESURES as $data) {
            $mesure = new MesureConformite();
            $mesure->setModule($data['module']);
            $mesure->setCode($data['code']);
            $mesure->setTitre($data['titre']);
            $mesure->setDescription($data['description']);
            $mesure->setStatut($data['statut']);
            $mesure->setResponsable($data['responsable'] ?? null);

            if (isset($data['echeance'])) {
                $mesure->setEcheance(new \DateTimeImmutable($data['echeance']));
            }

            $manager->persist($mesure);
        }

        $manager->flush();
    }
}
