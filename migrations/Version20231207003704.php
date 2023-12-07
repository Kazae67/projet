<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231207003704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE archived_order_detail DROP FOREIGN KEY FK_4271C0588DE820D9');
        $this->addSql('DROP INDEX IDX_4271C0588DE820D9 ON archived_order_detail');
        $this->addSql('ALTER TABLE archived_order_detail DROP seller_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE archived_order_detail ADD seller_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE archived_order_detail ADD CONSTRAINT FK_4271C0588DE820D9 FOREIGN KEY (seller_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_4271C0588DE820D9 ON archived_order_detail (seller_id)');
    }
}
