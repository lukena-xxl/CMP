<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200221180345 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE payment_method (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, short_description VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(100) DEFAULT NULL, key_1 VARCHAR(150) DEFAULT NULL, key_2 VARCHAR(150) DEFAULT NULL, key_3 VARCHAR(150) DEFAULT NULL, note_key_1 VARCHAR(255) DEFAULT NULL, note_key_2 VARCHAR(255) DEFAULT NULL, note_key_3 VARCHAR(255) DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_method (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, short_description VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(100) DEFAULT NULL, key_1 VARCHAR(150) DEFAULT NULL, key_2 VARCHAR(150) DEFAULT NULL, key_3 VARCHAR(150) DEFAULT NULL, note_key_1 VARCHAR(255) DEFAULT NULL, note_key_2 VARCHAR(255) DEFAULT NULL, note_key_3 VARCHAR(255) DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP INDEX UNIQ_8D93D649444F97DD ON user');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE payment_method');
        $this->addSql('DROP TABLE delivery_method');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649444F97DD ON user (phone)');
    }
}
