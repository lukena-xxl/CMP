<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200304122526 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product ADD currency_purchase_id INT DEFAULT NULL, ADD price_purchase DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADE467B018 FOREIGN KEY (currency_purchase_id) REFERENCES currency (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADE467B018 ON product (currency_purchase_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADE467B018');
        $this->addSql('DROP INDEX IDX_D34A04ADE467B018 ON product');
        $this->addSql('ALTER TABLE product DROP currency_purchase_id, DROP price_purchase');
    }
}
