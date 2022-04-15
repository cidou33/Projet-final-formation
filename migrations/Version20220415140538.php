<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220415140538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trainings (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, create_date DATE NOT NULL, update_date DATETIME DEFAULT NULL, active TINYINT(1) NOT NULL, pdf_file VARCHAR(255) NOT NULL, numb_download INT DEFAULT NULL, INDEX IDX_66DC433012469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trainings_users (trainings_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_A54ADDF2161BA2FF (trainings_id), INDEX IDX_A54ADDF267B3B43D (users_id), PRIMARY KEY(trainings_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trainings ADD CONSTRAINT FK_66DC433012469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE trainings_users ADD CONSTRAINT FK_A54ADDF2161BA2FF FOREIGN KEY (trainings_id) REFERENCES trainings (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trainings_users ADD CONSTRAINT FK_A54ADDF267B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trainings_users DROP FOREIGN KEY FK_A54ADDF2161BA2FF');
        $this->addSql('DROP TABLE trainings');
        $this->addSql('DROP TABLE trainings_users');
    }
}
