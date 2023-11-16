<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231116173926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE archived_order (id INT AUTO_INCREMENT NOT NULL, archived_at DATETIME NOT NULL, user_name VARCHAR(255) NOT NULL, address_details LONGTEXT NOT NULL, total NUMERIC(10, 2) NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE archived_order_detail (id INT AUTO_INCREMENT NOT NULL, archived_order_id INT NOT NULL, product_name VARCHAR(255) NOT NULL, price NUMERIC(10, 2) NOT NULL, quantity INT NOT NULL, INDEX IDX_4271C0587765132B (archived_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE archived_order_detail ADD CONSTRAINT FK_4271C0587765132B FOREIGN KEY (archived_order_id) REFERENCES archived_order (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE archived_order_detail DROP FOREIGN KEY FK_4271C0587765132B');
        $this->addSql('DROP TABLE archived_order');
        $this->addSql('DROP TABLE archived_order_detail');
    }
}
