<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250511163000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fix DateTime types in LikesStudent, LikesOffer and MatchEntity tables';
    }

    public function up(Schema $schema): void
    {
        // Cette migration ne nécessite pas de modifications de schéma
        // car le changement de DateTimeInterface vers DateTime n'affecte que le type PHP
        // et non le type de base de données qui reste DATETIME
        $this->addSql('-- Migration pour corriger les types DateTime dans les entités');
    }

    public function down(Schema $schema): void
    {
        // Pas de rollback nécessaire car c'est juste un changement de type PHP
    }
} 