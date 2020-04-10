<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200409083753 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE6CA661164');
        $this->addSql('ALTER TABLE order_product CHANGE in_order_id in_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE6CA661164 FOREIGN KEY (in_order_id) REFERENCES orders (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE6CA661164');
        $this->addSql('ALTER TABLE order_product CHANGE in_order_id in_order_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE6CA661164 FOREIGN KEY (in_order_id) REFERENCES orders (id)');
    }
}
