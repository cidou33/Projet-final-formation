<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220415135155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_transcriptions (users_id INT NOT NULL, transcriptions_id INT NOT NULL, INDEX IDX_8F518D6E67B3B43D (users_id), INDEX IDX_8F518D6E10E59299 (transcriptions_id), PRIMARY KEY(users_id, transcriptions_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_transcriptions ADD CONSTRAINT FK_8F518D6E67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_transcriptions ADD CONSTRAINT FK_8F518D6E10E59299 FOREIGN KEY (transcriptions_id) REFERENCES transcriptions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users ADD username VARCHAR(255) NOT NULL, ADD img_profil VARCHAR(255) DEFAULT NULL, ADD create_date DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE users_transcriptions');
        $this->addSql('ALTER TABLE users DROP username, DROP img_profil, DROP create_date');
    }
}
