<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200407161214 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, short VARCHAR(10) NOT NULL, abbr VARCHAR(10) NOT NULL, symbol VARCHAR(5) NOT NULL, display VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_method (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, short_description VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, key_1 VARCHAR(150) DEFAULT NULL, key_2 VARCHAR(150) DEFAULT NULL, key_3 VARCHAR(150) DEFAULT NULL, note_key_1 VARCHAR(255) DEFAULT NULL, note_key_2 VARCHAR(255) DEFAULT NULL, note_key_3 VARCHAR(255) DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL, email VARCHAR(100) NOT NULL, phone VARCHAR(20) DEFAULT NULL, first_name VARCHAR(50) DEFAULT NULL, middle_name VARCHAR(50) DEFAULT NULL, second_name VARCHAR(50) DEFAULT NULL, birth_date DATE DEFAULT NULL, registration_date DATETIME NOT NULL, is_block TINYINT(1) DEFAULT \'0\' NOT NULL, UNIQUE INDEX UNIQ_8D93D649AA08CB10 (login), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_item (id INT AUTO_INCREMENT NOT NULL, coefficient_id INT DEFAULT NULL, product_id INT DEFAULT NULL, name VARCHAR(150) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, discount_percentage DOUBLE PRECISION DEFAULT NULL, discount_start_date DATETIME DEFAULT NULL, discount_end_date DATETIME DEFAULT NULL, img VARCHAR(150) DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, is_checked TINYINT(1) DEFAULT \'0\' NOT NULL, position INT NOT NULL, displayed_quantity INT DEFAULT 1, min_order_quantity INT DEFAULT 1, max_order_quantity INT DEFAULT NULL, INDEX IDX_92F307BF6F010AB7 (coefficient_id), INDEX IDX_92F307BF4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_image (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, title VARCHAR(150) DEFAULT NULL, alt VARCHAR(150) DEFAULT NULL, is_main TINYINT(1) DEFAULT \'0\' NOT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, position INT NOT NULL, INDEX IDX_64617F034584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parent_category_id INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(200) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(100) DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, position INT NOT NULL, INDEX IDX_64C19C1796A8F92 (parent_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_method (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, short_description VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, key_1 VARCHAR(150) DEFAULT NULL, key_2 VARCHAR(150) DEFAULT NULL, key_3 VARCHAR(150) DEFAULT NULL, note_key_1 VARCHAR(255) DEFAULT NULL, note_key_2 VARCHAR(255) DEFAULT NULL, note_key_3 VARCHAR(255) DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filter_element (id INT AUTO_INCREMENT NOT NULL, filter_id INT NOT NULL, name VARCHAR(50) NOT NULL, position INT NOT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_F6D0295CD395B25E (filter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_tag (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(200) NOT NULL, description LONGTEXT DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_category (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(200) NOT NULL, description LONGTEXT DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, delivery_method_id INT DEFAULT NULL, payment_method_id INT DEFAULT NULL, admin_id INT DEFAULT NULL, discount DOUBLE PRECISION DEFAULT NULL, full_name VARCHAR(150) NOT NULL, phone VARCHAR(50) DEFAULT NULL, postcode VARCHAR(20) DEFAULT NULL, region VARCHAR(150) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, admin_note LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_E52FFDEEA76ED395 (user_id), INDEX IDX_E52FFDEE5DED75F5 (delivery_method_id), INDEX IDX_E52FFDEE5AA1164F (payment_method_id), INDEX IDX_E52FFDEE642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coefficient (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, ratio DOUBLE PRECISION NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_3F061B61A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, user_id INT DEFAULT NULL, currency_id INT DEFAULT NULL, availability_id INT DEFAULT NULL, slug VARCHAR(200) NOT NULL, name VARCHAR(150) NOT NULL, description LONGTEXT DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, creation_date DATETIME NOT NULL, update_date DATETIME NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), INDEX IDX_D34A04ADA76ED395 (user_id), INDEX IDX_D34A04AD38248176 (currency_id), INDEX IDX_D34A04AD61778466 (availability_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_product_caption (product_id INT NOT NULL, product_caption_id INT NOT NULL, INDEX IDX_152E57484584665A (product_id), INDEX IDX_152E5748C3325A7D (product_caption_id), PRIMARY KEY(product_id, product_caption_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_delivery_method (product_id INT NOT NULL, delivery_method_id INT NOT NULL, INDEX IDX_72C3C6734584665A (product_id), INDEX IDX_72C3C6735DED75F5 (delivery_method_id), PRIMARY KEY(product_id, delivery_method_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_payment_method (product_id INT NOT NULL, payment_method_id INT NOT NULL, INDEX IDX_2808DFDA4584665A (product_id), INDEX IDX_2808DFDA5AA1164F (payment_method_id), PRIMARY KEY(product_id, payment_method_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_filter_element (product_id INT NOT NULL, filter_element_id INT NOT NULL, INDEX IDX_A5B957704584665A (product_id), INDEX IDX_A5B95770C1725F2D (filter_element_id), PRIMARY KEY(product_id, filter_element_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, article_category_id INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(200) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(100) DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, publish DATETIME DEFAULT NULL, updated DATETIME NOT NULL, created DATETIME NOT NULL, position INT NOT NULL, INDEX IDX_23A0E6688C5F785 (article_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_article_tag (article_id INT NOT NULL, article_tag_id INT NOT NULL, INDEX IDX_B15FE9E7294869C (article_id), INDEX IDX_B15FE9ED015F491 (article_tag_id), PRIMARY KEY(article_id, article_tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE availability (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, color VARCHAR(40) DEFAULT NULL, description LONGTEXT DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, short_description VARCHAR(100) DEFAULT NULL, INDEX IDX_3FB7A2BFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filter (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filter_category (filter_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_3B231C61D395B25E (filter_id), INDEX IDX_3B231C6112469DE2 (category_id), PRIMARY KEY(filter_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_caption (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, color_fill VARCHAR(50) DEFAULT NULL, color_text VARCHAR(50) DEFAULT NULL, position INT NOT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_27A552D5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_product (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, in_order_id INT NOT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, price INT NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_2530ADE64584665A (product_id), INDEX IDX_2530ADE6CA661164 (in_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(255) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('ALTER TABLE product_item ADD CONSTRAINT FK_92F307BF6F010AB7 FOREIGN KEY (coefficient_id) REFERENCES coefficient (id)');
        $this->addSql('ALTER TABLE product_item ADD CONSTRAINT FK_92F307BF4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F034584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1796A8F92 FOREIGN KEY (parent_category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE filter_element ADD CONSTRAINT FK_F6D0295CD395B25E FOREIGN KEY (filter_id) REFERENCES filter (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE5DED75F5 FOREIGN KEY (delivery_method_id) REFERENCES delivery_method (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE5AA1164F FOREIGN KEY (payment_method_id) REFERENCES payment_method (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE coefficient ADD CONSTRAINT FK_3F061B61A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD38248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD61778466 FOREIGN KEY (availability_id) REFERENCES availability (id)');
        $this->addSql('ALTER TABLE product_product_caption ADD CONSTRAINT FK_152E57484584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_product_caption ADD CONSTRAINT FK_152E5748C3325A7D FOREIGN KEY (product_caption_id) REFERENCES product_caption (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_delivery_method ADD CONSTRAINT FK_72C3C6734584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_delivery_method ADD CONSTRAINT FK_72C3C6735DED75F5 FOREIGN KEY (delivery_method_id) REFERENCES delivery_method (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_payment_method ADD CONSTRAINT FK_2808DFDA4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_payment_method ADD CONSTRAINT FK_2808DFDA5AA1164F FOREIGN KEY (payment_method_id) REFERENCES payment_method (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_filter_element ADD CONSTRAINT FK_A5B957704584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_filter_element ADD CONSTRAINT FK_A5B95770C1725F2D FOREIGN KEY (filter_element_id) REFERENCES filter_element (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6688C5F785 FOREIGN KEY (article_category_id) REFERENCES article_category (id)');
        $this->addSql('ALTER TABLE article_article_tag ADD CONSTRAINT FK_B15FE9E7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_article_tag ADD CONSTRAINT FK_B15FE9ED015F491 FOREIGN KEY (article_tag_id) REFERENCES article_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE availability ADD CONSTRAINT FK_3FB7A2BFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE filter_category ADD CONSTRAINT FK_3B231C61D395B25E FOREIGN KEY (filter_id) REFERENCES filter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE filter_category ADD CONSTRAINT FK_3B231C6112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_caption ADD CONSTRAINT FK_27A552D5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE64584665A FOREIGN KEY (product_id) REFERENCES product_item (id)');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE6CA661164 FOREIGN KEY (in_order_id) REFERENCES orders (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD38248176');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE5AA1164F');
        $this->addSql('ALTER TABLE product_payment_method DROP FOREIGN KEY FK_2808DFDA5AA1164F');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEA76ED395');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE642B8210');
        $this->addSql('ALTER TABLE coefficient DROP FOREIGN KEY FK_3F061B61A76ED395');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA76ED395');
        $this->addSql('ALTER TABLE availability DROP FOREIGN KEY FK_3FB7A2BFA76ED395');
        $this->addSql('ALTER TABLE product_caption DROP FOREIGN KEY FK_27A552D5A76ED395');
        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE64584665A');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1796A8F92');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE filter_category DROP FOREIGN KEY FK_3B231C6112469DE2');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE5DED75F5');
        $this->addSql('ALTER TABLE product_delivery_method DROP FOREIGN KEY FK_72C3C6735DED75F5');
        $this->addSql('ALTER TABLE product_filter_element DROP FOREIGN KEY FK_A5B95770C1725F2D');
        $this->addSql('ALTER TABLE article_article_tag DROP FOREIGN KEY FK_B15FE9ED015F491');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6688C5F785');
        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE6CA661164');
        $this->addSql('ALTER TABLE product_item DROP FOREIGN KEY FK_92F307BF6F010AB7');
        $this->addSql('ALTER TABLE product_item DROP FOREIGN KEY FK_92F307BF4584665A');
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F034584665A');
        $this->addSql('ALTER TABLE product_product_caption DROP FOREIGN KEY FK_152E57484584665A');
        $this->addSql('ALTER TABLE product_delivery_method DROP FOREIGN KEY FK_72C3C6734584665A');
        $this->addSql('ALTER TABLE product_payment_method DROP FOREIGN KEY FK_2808DFDA4584665A');
        $this->addSql('ALTER TABLE product_filter_element DROP FOREIGN KEY FK_A5B957704584665A');
        $this->addSql('ALTER TABLE article_article_tag DROP FOREIGN KEY FK_B15FE9E7294869C');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD61778466');
        $this->addSql('ALTER TABLE filter_element DROP FOREIGN KEY FK_F6D0295CD395B25E');
        $this->addSql('ALTER TABLE filter_category DROP FOREIGN KEY FK_3B231C61D395B25E');
        $this->addSql('ALTER TABLE product_product_caption DROP FOREIGN KEY FK_152E5748C3325A7D');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE payment_method');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE product_item');
        $this->addSql('DROP TABLE product_image');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE delivery_method');
        $this->addSql('DROP TABLE filter_element');
        $this->addSql('DROP TABLE article_tag');
        $this->addSql('DROP TABLE article_category');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE coefficient');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_product_caption');
        $this->addSql('DROP TABLE product_delivery_method');
        $this->addSql('DROP TABLE product_payment_method');
        $this->addSql('DROP TABLE product_filter_element');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_article_tag');
        $this->addSql('DROP TABLE availability');
        $this->addSql('DROP TABLE filter');
        $this->addSql('DROP TABLE filter_category');
        $this->addSql('DROP TABLE product_caption');
        $this->addSql('DROP TABLE order_product');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE ext_log_entries');
    }
}
