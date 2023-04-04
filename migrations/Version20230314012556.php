<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230314012556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedback DROP INDEX IDX_D2294458166D1F9C, ADD UNIQUE INDEX UNIQ_D2294458166D1F9C (project_id)');
        $this->addSql('ALTER TABLE project ADD feedback_id INT NOT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EED249A887 FOREIGN KEY (feedback_id) REFERENCES feedback (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EED249A887 ON project (feedback_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedback DROP INDEX UNIQ_D2294458166D1F9C, ADD INDEX IDX_D2294458166D1F9C (project_id)');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EED249A887');
        $this->addSql('DROP INDEX UNIQ_2FB3D0EED249A887 ON project');
        $this->addSql('ALTER TABLE project DROP feedback_id');
    }
}
