<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\Database\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

final class Version20260501000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create mesure_conformite table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE mesure_conformite (
                id UUID NOT NULL,
                module VARCHAR(50) NOT NULL,
                code VARCHAR(20) NOT NULL,
                titre VARCHAR(255) NOT NULL,
                description TEXT DEFAULT NULL,
                statut VARCHAR(20) NOT NULL DEFAULT 'non_conforme',
                responsable VARCHAR(100) DEFAULT NULL,
                echeance DATE DEFAULT NULL,
                preuves JSON NOT NULL DEFAULT '[]',
                commentaire TEXT DEFAULT NULL,
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                PRIMARY KEY(id)
            )
        SQL);
        $this->addSql('CREATE INDEX idx_mesure_module ON mesure_conformite (module)');
        $this->addSql('CREATE INDEX idx_mesure_statut ON mesure_conformite (statut)');
        $this->addSql('CREATE INDEX idx_mesure_code ON mesure_conformite (code)');
        $this->addSql('COMMENT ON COLUMN mesure_conformite.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN mesure_conformite.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN mesure_conformite.updated_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE mesure_conformite');
    }
}
