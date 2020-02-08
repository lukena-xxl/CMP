<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200207141002 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE filter (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filter_category (filter_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_3B231C61D395B25E (filter_id), INDEX IDX_3B231C6112469DE2 (category_id), PRIMARY KEY(filter_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE filter_category ADD CONSTRAINT FK_3B231C61D395B25E FOREIGN KEY (filter_id) REFERENCES filter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE filter_category ADD CONSTRAINT FK_3B231C6112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE filter_category DROP FOREIGN KEY FK_3B231C61D395B25E');
        $this->addSql('DROP TABLE filter');
        $this->addSql('DROP TABLE filter_category');
    }
}
