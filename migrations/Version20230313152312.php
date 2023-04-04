<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230313152312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(128) NOT NULL, password VARCHAR(96) NOT NULL, created DATETIME NOT NULL, first_name VARCHAR(96) NOT NULL, last_name VARCHAR(96) NOT NULL, UNIQUE INDEX UNIQ_C7440455F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, vico_id INT NOT NULL, project_id INT NOT NULL, text LONGTEXT NOT NULL, overall_rating INT NOT NULL, INDEX IDX_D229445819F89217 (vico_id), INDEX IDX_D2294458166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, vico_id INT NOT NULL, created DATETIME NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_2FB3D0EE61220EA6 (creator_id), INDEX IDX_2FB3D0EE19F89217 (vico_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, rating_type_id INT NOT NULL, feedback_id INT NOT NULL, value INT NOT NULL, INDEX IDX_D8892622260075EB (rating_type_id), INDEX IDX_D8892622D249A887 (feedback_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(63) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vico (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D229445819F89217 FOREIGN KEY (vico_id) REFERENCES vico (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE61220EA6 FOREIGN KEY (creator_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE19F89217 FOREIGN KEY (vico_id) REFERENCES vico (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622260075EB FOREIGN KEY (rating_type_id) REFERENCES rating_type (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622D249A887 FOREIGN KEY (feedback_id) REFERENCES feedback (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D229445819F89217');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458166D1F9C');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE61220EA6');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE19F89217');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622260075EB');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622D249A887');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE rating_type');
        $this->addSql('DROP TABLE vico');
    }
}
