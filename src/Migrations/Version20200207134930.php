<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200207134930 extends AbstractMigration
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
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(100) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, first_name VARCHAR(50) DEFAULT NULL, middle_name VARCHAR(50) DEFAULT NULL, second_name VARCHAR(50) DEFAULT NULL, region VARCHAR(100) DEFAULT NULL, birth_date DATE DEFAULT NULL, registration_date DATETIME NOT NULL, is_block TINYINT(1) DEFAULT \'0\' NOT NULL, UNIQUE INDEX UNIQ_8D93D649AA08CB10 (login), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649444F97DD (phone), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parent_category_id INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(200) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(100) DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, position INT NOT NULL, INDEX IDX_64C19C1796A8F92 (parent_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_tag (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(200) NOT NULL, description LONGTEXT DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_category (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(200) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(100) DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, article_category_id INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(200) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(100) DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, publish DATETIME DEFAULT NULL, updated DATETIME NOT NULL, created DATETIME NOT NULL, position INT NOT NULL, INDEX IDX_23A0E6688C5F785 (article_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_article_tag (article_id INT NOT NULL, article_tag_id INT NOT NULL, INDEX IDX_B15FE9E7294869C (article_id), INDEX IDX_B15FE9ED015F491 (article_tag_id), PRIMARY KEY(article_id, article_tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE availability (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, color VARCHAR(40) DEFAULT NULL, description LONGTEXT DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(255) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1796A8F92 FOREIGN KEY (parent_category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6688C5F785 FOREIGN KEY (article_category_id) REFERENCES article_category (id)');
        $this->addSql('ALTER TABLE article_article_tag ADD CONSTRAINT FK_B15FE9E7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_article_tag ADD CONSTRAINT FK_B15FE9ED015F491 FOREIGN KEY (article_tag_id) REFERENCES article_tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1796A8F92');
        $this->addSql('ALTER TABLE article_article_tag DROP FOREIGN KEY FK_B15FE9ED015F491');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6688C5F785');
        $this->addSql('ALTER TABLE article_article_tag DROP FOREIGN KEY FK_B15FE9E7294869C');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE article_tag');
        $this->addSql('DROP TABLE article_category');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_article_tag');
        $this->addSql('DROP TABLE availability');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE ext_log_entries');
    }
}
