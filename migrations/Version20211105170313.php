<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211105170313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE spell (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, higher_level VARCHAR(255) DEFAULT NULL, spell_range VARCHAR(255) DEFAULT NULL, components LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', material VARCHAR(255) DEFAULT NULL, ritual TINYINT(1) NOT NULL, duration VARCHAR(255) NOT NULL, concentration TINYINT(1) NOT NULL, casting_time VARCHAR(255) NOT NULL, level INT NOT NULL, attack_type VARCHAR(255) NOT NULL, damage LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', school VARCHAR(255) NOT NULL, classes LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', subclasses LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE spell');
    }
}
