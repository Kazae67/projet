<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231120042118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD tracking_token VARCHAR(64) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F5299398B46BB896 ON `order` (tracking_token)');
        $this->addSql("UPDATE order SET tracking_token = CONCAT('order_', id, '_', SUBSTRING(MD5(RAND()), 1, 8)) WHERE tracking_token IS NULL OR tracking_token = ''");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_F5299398B46BB896 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP tracking_token');
    }
}
