<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200311130555 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_delivery_method (product_id INT NOT NULL, delivery_method_id INT NOT NULL, INDEX IDX_72C3C6734584665A (product_id), INDEX IDX_72C3C6735DED75F5 (delivery_method_id), PRIMARY KEY(product_id, delivery_method_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_payment_method (product_id INT NOT NULL, payment_method_id INT NOT NULL, INDEX IDX_2808DFDA4584665A (product_id), INDEX IDX_2808DFDA5AA1164F (payment_method_id), PRIMARY KEY(product_id, payment_method_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_delivery_method ADD CONSTRAINT FK_72C3C6734584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_delivery_method ADD CONSTRAINT FK_72C3C6735DED75F5 FOREIGN KEY (delivery_method_id) REFERENCES delivery_method (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_payment_method ADD CONSTRAINT FK_2808DFDA4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_payment_method ADD CONSTRAINT FK_2808DFDA5AA1164F FOREIGN KEY (payment_method_id) REFERENCES payment_method (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE product_delivery_method');
        $this->addSql('DROP TABLE product_payment_method');
    }
}
