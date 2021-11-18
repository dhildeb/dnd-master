<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211118205607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `character` (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, hit_die INT NOT NULL, proficiencies LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', saving_throws LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', name VARCHAR(255) NOT NULL, spells LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', equipment LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', hp INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE class_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, hit_die INT NOT NULL, proficiencies LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', saving_throws LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(255) DEFAULT NULL, cost LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', weight INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, quantity INT NOT NULL, reach INT NOT NULL, damage LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', properties LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `character`');
        $this->addSql('DROP TABLE class_type');
        $this->addSql('DROP TABLE equipment');
    }
}
