<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220415140002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, create_date DATE NOT NULL, update_date DATETIME DEFAULT NULL, active TINYINT(1) NOT NULL, img_news VARCHAR(255) DEFAULT NULL, img_alt VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news_users (news_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_6E3C22B7B5A459A0 (news_id), INDEX IDX_6E3C22B767B3B43D (users_id), PRIMARY KEY(news_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE news_users ADD CONSTRAINT FK_6E3C22B7B5A459A0 FOREIGN KEY (news_id) REFERENCES news (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE news_users ADD CONSTRAINT FK_6E3C22B767B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news_users DROP FOREIGN KEY FK_6E3C22B7B5A459A0');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE news_users');
    }
}
