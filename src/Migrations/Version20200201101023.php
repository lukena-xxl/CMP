<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200201101023 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, short VARCHAR(10) NOT NULL, abbr VARCHAR(10) NOT NULL, sign VARCHAR(5) NOT NULL, coefficient DOUBLE PRECISION NOT NULL, update_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(100) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, first_name VARCHAR(50) DEFAULT NULL, middle_name VARCHAR(50) DEFAULT NULL, second_name VARCHAR(50) DEFAULT NULL, region VARCHAR(100) DEFAULT NULL, birth_date DATE DEFAULT NULL, registration_date DATETIME NOT NULL, is_block TINYINT(1) DEFAULT \'0\' NOT NULL, UNIQUE INDEX UNIQ_8D93D649AA08CB10 (login), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649444F97DD (phone), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_image (id INT AUTO_INCREMENT NOT NULL, product_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, is_main TINYINT(1) DEFAULT \'0\' NOT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_64617F03DE18E50B (product_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parent_category_id INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, name VARCHAR(200) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(100) DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, position INT DEFAULT NULL, INDEX IDX_64C19C1796A8F92 (parent_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_methods (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, short_description VARCHAR(255) DEFAULT NULL, commission DOUBLE PRECISION DEFAULT NULL, image VARCHAR(100) DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, api_key_1 VARCHAR(255) DEFAULT NULL, api_key_2 VARCHAR(255) DEFAULT NULL, api_key_3 VARCHAR(255) DEFAULT NULL, note_api_key_1 VARCHAR(255) DEFAULT NULL, note_api_key_2 VARCHAR(255) DEFAULT NULL, note_api_key_3 VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, category_id_id INT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(150) NOT NULL, description LONGTEXT DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_389B7839777D11E (category_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_parameter (id INT AUTO_INCREMENT NOT NULL, product_id_id INT NOT NULL, parameter_id_id INT NOT NULL, description VARCHAR(255) NOT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_4437279DDE18E50B (product_id_id), INDEX IDX_4437279DF8164DB (parameter_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_order (id INT AUTO_INCREMENT NOT NULL, product_id_id INT NOT NULL, order_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, discount DOUBLE PRECISION DEFAULT NULL, availability VARCHAR(100) DEFAULT NULL, INDEX IDX_5475E8C4DE18E50B (product_id_id), INDEX IDX_5475E8C4FCDAEAAA (order_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, payment_method_id_id INT NOT NULL, delivery_method_id_id INT NOT NULL, delivery_status_id_id INT NOT NULL, payment_status_id_id INT NOT NULL, order_status_id_id INT NOT NULL, discount DOUBLE PRECISION DEFAULT NULL, order_date DATETIME NOT NULL, note LONGTEXT DEFAULT NULL, full_name VARCHAR(100) NOT NULL, phone VARCHAR(20) NOT NULL, region VARCHAR(100) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, postcode VARCHAR(20) DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, INDEX IDX_F52993989D86650F (user_id_id), INDEX IDX_F5299398A0CE293E (payment_method_id_id), INDEX IDX_F529939859BD6720 (delivery_method_id_id), INDEX IDX_F5299398179D36A3 (delivery_status_id_id), INDEX IDX_F5299398EEEE78BD (payment_status_id_id), INDEX IDX_F52993988CDE5BCD (order_status_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_number_status (id INT AUTO_INCREMENT NOT NULL, post_number_id_id INT NOT NULL, name VARCHAR(200) NOT NULL, description VARCHAR(255) DEFAULT NULL, code VARCHAR(50) DEFAULT NULL, creation_date DATETIME NOT NULL, INDEX IDX_F175D80E6F30604C (post_number_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_filter (id INT AUTO_INCREMENT NOT NULL, product_id_id INT NOT NULL, filter_id_id INT NOT NULL, description VARCHAR(255) NOT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_1DB81EB9DE18E50B (product_id_id), INDEX IDX_1DB81EB956F56DA8 (filter_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, color VARCHAR(40) DEFAULT NULL, codes JSON DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parameter (id INT AUTO_INCREMENT NOT NULL, category_id_id INT NOT NULL, name VARCHAR(100) NOT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_2A9791109777D11E (category_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id_id INT NOT NULL, user_id_id INT NOT NULL, currency_id_id INT NOT NULL, availability_id_id INT NOT NULL, name VARCHAR(200) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, coefficient DOUBLE PRECISION DEFAULT NULL, discount_percentage DOUBLE PRECISION DEFAULT NULL, discount_end_date DATETIME DEFAULT NULL, update_date DATETIME DEFAULT NULL, creation_date DATETIME NOT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, discount_start_date DATETIME DEFAULT NULL, INDEX IDX_D34A04AD9777D11E (category_id_id), INDEX IDX_D34A04AD9D86650F (user_id_id), INDEX IDX_D34A04AD28A69C31 (currency_id_id), INDEX IDX_D34A04ADBB95CB13 (availability_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_tag (product_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_E3A6E39C4584665A (product_id), INDEX IDX_E3A6E39CBAD26311 (tag_id), PRIMARY KEY(product_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_payment_methods (product_id INT NOT NULL, payment_methods_id INT NOT NULL, INDEX IDX_7D20FC1E4584665A (product_id), INDEX IDX_7D20FC1E1484032 (payment_methods_id), PRIMARY KEY(product_id, payment_methods_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_delivery_methods (product_id INT NOT NULL, delivery_methods_id INT NOT NULL, INDEX IDX_D2702C4B4584665A (product_id), INDEX IDX_D2702C4B2AFB56C3 (delivery_methods_id), PRIMARY KEY(product_id, delivery_methods_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_number (id INT AUTO_INCREMENT NOT NULL, order_id_id INT NOT NULL, delivery_method_id_id INT NOT NULL, number VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, creation_date DATETIME NOT NULL, INDEX IDX_A6B2FB31FCDAEAAA (order_id_id), INDEX IDX_A6B2FB3159BD6720 (delivery_method_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE availability (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, color VARCHAR(40) DEFAULT NULL, short_description VARCHAR(200) DEFAULT NULL, description LONGTEXT DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filter (id INT AUTO_INCREMENT NOT NULL, category_id_id INT NOT NULL, name VARCHAR(100) NOT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_7FC45F1D9777D11E (category_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, color VARCHAR(40) DEFAULT NULL, codes JSON DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_methods (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, short_description VARCHAR(255) DEFAULT NULL, commission DOUBLE PRECISION DEFAULT NULL, image VARCHAR(100) DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, api_key_1 VARCHAR(255) DEFAULT NULL, api_key_2 VARCHAR(255) DEFAULT NULL, api_key_3 VARCHAR(255) DEFAULT NULL, note_api_key_1 VARCHAR(255) DEFAULT NULL, note_api_key_2 VARCHAR(255) DEFAULT NULL, note_api_key_3 VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, color VARCHAR(40) DEFAULT NULL, codes JSON DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(255) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F03DE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1796A8F92 FOREIGN KEY (parent_category_id) REFERENCES category (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B7839777D11E FOREIGN KEY (category_id_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product_parameter ADD CONSTRAINT FK_4437279DDE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_parameter ADD CONSTRAINT FK_4437279DF8164DB FOREIGN KEY (parameter_id_id) REFERENCES parameter (id)');
        $this->addSql('ALTER TABLE product_order ADD CONSTRAINT FK_5475E8C4DE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_order ADD CONSTRAINT FK_5475E8C4FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993989D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A0CE293E FOREIGN KEY (payment_method_id_id) REFERENCES payment_methods (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939859BD6720 FOREIGN KEY (delivery_method_id_id) REFERENCES delivery_methods (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398179D36A3 FOREIGN KEY (delivery_status_id_id) REFERENCES delivery_status (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398EEEE78BD FOREIGN KEY (payment_status_id_id) REFERENCES payment_status (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993988CDE5BCD FOREIGN KEY (order_status_id_id) REFERENCES order_status (id)');
        $this->addSql('ALTER TABLE post_number_status ADD CONSTRAINT FK_F175D80E6F30604C FOREIGN KEY (post_number_id_id) REFERENCES post_number (id)');
        $this->addSql('ALTER TABLE product_filter ADD CONSTRAINT FK_1DB81EB9DE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_filter ADD CONSTRAINT FK_1DB81EB956F56DA8 FOREIGN KEY (filter_id_id) REFERENCES filter (id)');
        $this->addSql('ALTER TABLE parameter ADD CONSTRAINT FK_2A9791109777D11E FOREIGN KEY (category_id_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD9777D11E FOREIGN KEY (category_id_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD28A69C31 FOREIGN KEY (currency_id_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADBB95CB13 FOREIGN KEY (availability_id_id) REFERENCES availability (id)');
        $this->addSql('ALTER TABLE product_tag ADD CONSTRAINT FK_E3A6E39C4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_tag ADD CONSTRAINT FK_E3A6E39CBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_payment_methods ADD CONSTRAINT FK_7D20FC1E4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_payment_methods ADD CONSTRAINT FK_7D20FC1E1484032 FOREIGN KEY (payment_methods_id) REFERENCES payment_methods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_delivery_methods ADD CONSTRAINT FK_D2702C4B4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_delivery_methods ADD CONSTRAINT FK_D2702C4B2AFB56C3 FOREIGN KEY (delivery_methods_id) REFERENCES delivery_methods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_number ADD CONSTRAINT FK_A6B2FB31FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE post_number ADD CONSTRAINT FK_A6B2FB3159BD6720 FOREIGN KEY (delivery_method_id_id) REFERENCES delivery_methods (id)');
        $this->addSql('ALTER TABLE filter ADD CONSTRAINT FK_7FC45F1D9777D11E FOREIGN KEY (category_id_id) REFERENCES category (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD28A69C31');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993989D86650F');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD9D86650F');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1796A8F92');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B7839777D11E');
        $this->addSql('ALTER TABLE parameter DROP FOREIGN KEY FK_2A9791109777D11E');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD9777D11E');
        $this->addSql('ALTER TABLE filter DROP FOREIGN KEY FK_7FC45F1D9777D11E');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939859BD6720');
        $this->addSql('ALTER TABLE product_delivery_methods DROP FOREIGN KEY FK_D2702C4B2AFB56C3');
        $this->addSql('ALTER TABLE post_number DROP FOREIGN KEY FK_A6B2FB3159BD6720');
        $this->addSql('ALTER TABLE product_tag DROP FOREIGN KEY FK_E3A6E39CBAD26311');
        $this->addSql('ALTER TABLE product_order DROP FOREIGN KEY FK_5475E8C4FCDAEAAA');
        $this->addSql('ALTER TABLE post_number DROP FOREIGN KEY FK_A6B2FB31FCDAEAAA');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993988CDE5BCD');
        $this->addSql('ALTER TABLE product_parameter DROP FOREIGN KEY FK_4437279DF8164DB');
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F03DE18E50B');
        $this->addSql('ALTER TABLE product_parameter DROP FOREIGN KEY FK_4437279DDE18E50B');
        $this->addSql('ALTER TABLE product_order DROP FOREIGN KEY FK_5475E8C4DE18E50B');
        $this->addSql('ALTER TABLE product_filter DROP FOREIGN KEY FK_1DB81EB9DE18E50B');
        $this->addSql('ALTER TABLE product_tag DROP FOREIGN KEY FK_E3A6E39C4584665A');
        $this->addSql('ALTER TABLE product_payment_methods DROP FOREIGN KEY FK_7D20FC1E4584665A');
        $this->addSql('ALTER TABLE product_delivery_methods DROP FOREIGN KEY FK_D2702C4B4584665A');
        $this->addSql('ALTER TABLE post_number_status DROP FOREIGN KEY FK_F175D80E6F30604C');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADBB95CB13');
        $this->addSql('ALTER TABLE product_filter DROP FOREIGN KEY FK_1DB81EB956F56DA8');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398EEEE78BD');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A0CE293E');
        $this->addSql('ALTER TABLE product_payment_methods DROP FOREIGN KEY FK_7D20FC1E1484032');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398179D36A3');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE product_image');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE delivery_methods');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE product_parameter');
        $this->addSql('DROP TABLE product_order');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE post_number_status');
        $this->addSql('DROP TABLE product_filter');
        $this->addSql('DROP TABLE order_status');
        $this->addSql('DROP TABLE parameter');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_tag');
        $this->addSql('DROP TABLE product_payment_methods');
        $this->addSql('DROP TABLE product_delivery_methods');
        $this->addSql('DROP TABLE post_number');
        $this->addSql('DROP TABLE availability');
        $this->addSql('DROP TABLE filter');
        $this->addSql('DROP TABLE payment_status');
        $this->addSql('DROP TABLE payment_methods');
        $this->addSql('DROP TABLE delivery_status');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE ext_log_entries');
    }
}
