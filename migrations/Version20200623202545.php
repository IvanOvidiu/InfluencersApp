<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200623202545 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, author_id_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, content LONGTEXT NOT NULL, submission_date DATETIME NOT NULL, no_reviews INT NOT NULL, INDEX IDX_23A0E6669CCBE9A (author_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(100) NOT NULL, name VARCHAR(50) NOT NULL, rating INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_relation (id INT AUTO_INCREMENT NOT NULL, article_id_id INT DEFAULT NULL, tag_id_id INT DEFAULT NULL, INDEX IDX_54B83C928F3EC46 (article_id_id), INDEX IDX_54B83C925DA88751 (tag_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6669CCBE9A FOREIGN KEY (author_id_id) REFERENCES author (id)');
        $this->addSql('ALTER TABLE tag_relation ADD CONSTRAINT FK_54B83C928F3EC46 FOREIGN KEY (article_id_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE tag_relation ADD CONSTRAINT FK_54B83C925DA88751 FOREIGN KEY (tag_id_id) REFERENCES tag (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag_relation DROP FOREIGN KEY FK_54B83C928F3EC46');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6669CCBE9A');
        $this->addSql('ALTER TABLE tag_relation DROP FOREIGN KEY FK_54B83C925DA88751');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_relation');
    }
}
