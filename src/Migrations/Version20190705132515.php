<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190705132515 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE files (id INT AUTO_INCREMENT NOT NULL, commercial_id INT NOT NULL, sent_by_id INT DEFAULT NULL, name VARCHAR(190) NOT NULL, type VARCHAR(190) NOT NULL, path VARCHAR(190) NOT NULL, description LONGTEXT NOT NULL, slug VARCHAR(190) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_6354059989D9B62 (slug), INDEX IDX_63540597854071C (commercial_id), INDEX IDX_6354059A45BB98C (sent_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE files_company (files_id INT NOT NULL, company_id INT NOT NULL, INDEX IDX_EE621618A3E65B2F (files_id), INDEX IDX_EE621618979B1AD6 (company_id), PRIMARY KEY(files_id, company_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, commercial_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, first_adress_field VARCHAR(255) DEFAULT NULL, second_adress_field VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_4FBF094F989D9B62 (slug), INDEX IDX_4FBF094F7854071C (commercial_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, title VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, picture VARCHAR(190) DEFAULT NULL, slug VARCHAR(84) DEFAULT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_5A8A6C8DF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(190) NOT NULL, firstname VARCHAR(190) DEFAULT NULL, lastname VARCHAR(190) DEFAULT NULL, slug VARCHAR(190) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649989D9B62 (slug), UNIQUE INDEX UNIQ_8D93D649979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE files ADD CONSTRAINT FK_63540597854071C FOREIGN KEY (commercial_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE files ADD CONSTRAINT FK_6354059A45BB98C FOREIGN KEY (sent_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE files_company ADD CONSTRAINT FK_EE621618A3E65B2F FOREIGN KEY (files_id) REFERENCES files (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE files_company ADD CONSTRAINT FK_EE621618979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F7854071C FOREIGN KEY (commercial_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE files_company DROP FOREIGN KEY FK_EE621618A3E65B2F');
        $this->addSql('ALTER TABLE files_company DROP FOREIGN KEY FK_EE621618979B1AD6');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649979B1AD6');
        $this->addSql('ALTER TABLE files DROP FOREIGN KEY FK_63540597854071C');
        $this->addSql('ALTER TABLE files DROP FOREIGN KEY FK_6354059A45BB98C');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F7854071C');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DF675F31B');
        $this->addSql('DROP TABLE files');
        $this->addSql('DROP TABLE files_company');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE user');
    }
}