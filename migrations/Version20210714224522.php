<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210714224522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE webinar (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, webinar_category_id INT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_C9E29F4AA76ED395 (user_id), INDEX IDX_C9E29F4AFB331D40 (webinar_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE webinar_category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_37488B27727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE webinar ADD CONSTRAINT FK_C9E29F4AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE webinar ADD CONSTRAINT FK_C9E29F4AFB331D40 FOREIGN KEY (webinar_category_id) REFERENCES webinar_category (id)');
        $this->addSql('ALTER TABLE webinar_category ADD CONSTRAINT FK_37488B27727ACA70 FOREIGN KEY (parent_id) REFERENCES webinar_category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE webinar DROP FOREIGN KEY FK_C9E29F4AFB331D40');
        $this->addSql('ALTER TABLE webinar_category DROP FOREIGN KEY FK_37488B27727ACA70');
        $this->addSql('DROP TABLE webinar');
        $this->addSql('DROP TABLE webinar_category');
    }
}
