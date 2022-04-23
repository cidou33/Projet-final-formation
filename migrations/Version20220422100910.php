<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220422100910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trainings DROP FOREIGN KEY FK_66DC433012469DE2');
        $this->addSql('ALTER TABLE trainings CHANGE category_id category_id INT NOT NULL, CHANGE pdf_file pdf_file VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE trainings ADD CONSTRAINT FK_66DC433012469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trainings DROP FOREIGN KEY FK_66DC433012469DE2');
        $this->addSql('ALTER TABLE trainings CHANGE category_id category_id INT DEFAULT NULL, CHANGE pdf_file pdf_file VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE trainings ADD CONSTRAINT FK_66DC433012469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
    }
}
