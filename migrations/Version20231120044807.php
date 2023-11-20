<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231120044807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add tracking_token column to order table';
    }

    public function up(Schema $schema): void
    {
        // Vérifier si la colonne 'tracking_token' existe déjà
        $table = $schema->getTable('order');
        if (!$table->hasColumn('tracking_token')) {
            $this->addSql('ALTER TABLE `order` ADD tracking_token VARCHAR(64) NOT NULL');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_F5299398B46BB896 ON `order` (tracking_token)');
        }
    }

    public function down(Schema $schema): void
    {
        // Supprimer la colonne et l'index si elle existe
        $table = $schema->getTable('order');
        if ($table->hasColumn('tracking_token')) {
            $this->addSql('DROP INDEX UNIQ_F5299398B46BB896 ON `order`');
            $this->addSql('ALTER TABLE `order` DROP tracking_token');
        }
    }
}
