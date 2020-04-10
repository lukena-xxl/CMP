<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200311111859 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_item (id INT AUTO_INCREMENT NOT NULL, coefficient_id INT DEFAULT NULL, product_id INT DEFAULT NULL, name VARCHAR(150) NOT NULL, price DOUBLE PRECISION NOT NULL, discount_percentage DOUBLE PRECISION DEFAULT NULL, discount_start_date DATETIME DEFAULT NULL, discount_end_date DATETIME DEFAULT NULL, img VARCHAR(150) DEFAULT NULL, is_visible TINYINT(1) NOT NULL, is_checked TINYINT(1) NOT NULL, INDEX IDX_92F307BF6F010AB7 (coefficient_id), INDEX IDX_92F307BF4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_item ADD CONSTRAINT FK_92F307BF6F010AB7 FOREIGN KEY (coefficient_id) REFERENCES coefficient (id)');
        $this->addSql('ALTER TABLE product_item ADD CONSTRAINT FK_92F307BF4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD6F010AB7');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADE467B018');
        $this->addSql('DROP INDEX IDX_D34A04ADE467B018 ON product');
        $this->addSql('DROP INDEX IDX_D34A04AD6F010AB7 ON product');
        $this->addSql('ALTER TABLE product DROP currency_purchase_id, DROP coefficient_id, DROP price, DROP discount_percentage, DROP discount_start_date, DROP discount_end_date, DROP price_purchase');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE product_item');
        $this->addSql('ALTER TABLE product ADD currency_purchase_id INT DEFAULT NULL, ADD coefficient_id INT DEFAULT NULL, ADD price DOUBLE PRECISION NOT NULL, ADD discount_percentage DOUBLE PRECISION DEFAULT NULL, ADD discount_start_date DATETIME DEFAULT NULL, ADD discount_end_date DATETIME DEFAULT NULL, ADD price_purchase DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD6F010AB7 FOREIGN KEY (coefficient_id) REFERENCES coefficient (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADE467B018 FOREIGN KEY (currency_purchase_id) REFERENCES currency (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADE467B018 ON product (currency_purchase_id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD6F010AB7 ON product (coefficient_id)');
    }
}
