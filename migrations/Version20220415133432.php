<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220415133432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transcriptions (id INT AUTO_INCREMENT NOT NULL, difficulty_level_id INT NOT NULL, band_name VARCHAR(255) NOT NULL, song_name VARCHAR(255) NOT NULL, pdf_file VARCHAR(255) NOT NULL, media_link VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, create_date DATE NOT NULL, update_date DATETIME DEFAULT NULL, numb_download INT DEFAULT NULL, INDEX IDX_F1E914DB64890943 (difficulty_level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transcriptions ADD CONSTRAINT FK_F1E914DB64890943 FOREIGN KEY (difficulty_level_id) REFERENCES difficulty (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE transcriptions');
    }
}
