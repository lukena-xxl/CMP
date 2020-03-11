<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200303140550 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_parameter (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, parameter_id INT DEFAULT NULL, description VARCHAR(200) NOT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_4437279D4584665A (product_id), INDEX IDX_4437279D7C56DBD6 (parameter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_filter (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, filter_id INT DEFAULT NULL, description VARCHAR(200) NOT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_1DB81EB94584665A (product_id), INDEX IDX_1DB81EB9D395B25E (filter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, user_id INT DEFAULT NULL, currency_id INT DEFAULT NULL, availability_id INT DEFAULT NULL, slug VARCHAR(200) NOT NULL, name VARCHAR(150) NOT NULL, description LONGTEXT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, discount_percentage DOUBLE PRECISION DEFAULT NULL, discount_start_date DATETIME DEFAULT NULL, discount_end_date DATETIME DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, creation_date DATETIME NOT NULL, update_date DATETIME NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), INDEX IDX_D34A04ADA76ED395 (user_id), INDEX IDX_D34A04AD38248176 (currency_id), INDEX IDX_D34A04AD61778466 (availability_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_product_caption (product_id INT NOT NULL, product_caption_id INT NOT NULL, INDEX IDX_152E57484584665A (product_id), INDEX IDX_152E5748C3325A7D (product_caption_id), PRIMARY KEY(product_id, product_caption_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_parameter ADD CONSTRAINT FK_4437279D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_parameter ADD CONSTRAINT FK_4437279D7C56DBD6 FOREIGN KEY (parameter_id) REFERENCES parameter (id)');
        $this->addSql('ALTER TABLE product_filter ADD CONSTRAINT FK_1DB81EB94584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_filter ADD CONSTRAINT FK_1DB81EB9D395B25E FOREIGN KEY (filter_id) REFERENCES filter (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD38248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD61778466 FOREIGN KEY (availability_id) REFERENCES availability (id)');
        $this->addSql('ALTER TABLE product_product_caption ADD CONSTRAINT FK_152E57484584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_product_caption ADD CONSTRAINT FK_152E5748C3325A7D FOREIGN KEY (product_caption_id) REFERENCES product_caption (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_image ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F034584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_64617F034584665A ON product_image (product_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F034584665A');
        $this->addSql('ALTER TABLE product_parameter DROP FOREIGN KEY FK_4437279D4584665A');
        $this->addSql('ALTER TABLE product_filter DROP FOREIGN KEY FK_1DB81EB94584665A');
        $this->addSql('ALTER TABLE product_product_caption DROP FOREIGN KEY FK_152E57484584665A');
        $this->addSql('DROP TABLE product_parameter');
        $this->addSql('DROP TABLE product_filter');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_product_caption');
        $this->addSql('DROP INDEX IDX_64617F034584665A ON product_image');
        $this->addSql('ALTER TABLE product_image DROP product_id');
    }
}
