<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221013133423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON student');
        $this->addSql('ALTER TABLE student ADD nsc INT NOT NULL, DROP id');
        $this->addSql('ALTER TABLE student ADD PRIMARY KEY (nsc)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student ADD id INT AUTO_INCREMENT NOT NULL, DROP nsc, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }
}
