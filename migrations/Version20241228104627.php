<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241228104627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe AS SELECT id, label, description, duration, person_count, photo FROM recipe');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('CREATE TABLE recipe (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, label VARCHAR(255) NOT NULL, description CLOB NOT NULL, duration INTEGER NOT NULL, person_count INTEGER NOT NULL, photo VARCHAR(255) NOT NULL, CONSTRAINT FK_DA88B137A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO recipe (id, label, description, duration, person_count, photo) SELECT id, label, description, duration, person_count, photo FROM __temp__recipe');
        $this->addSql('DROP TABLE __temp__recipe');
        $this->addSql('CREATE INDEX IDX_DA88B137A76ED395 ON recipe (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe AS SELECT id, label, description, duration, person_count, photo FROM recipe');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('CREATE TABLE recipe (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, label VARCHAR(255) NOT NULL, description CLOB NOT NULL, duration INTEGER NOT NULL, person_count INTEGER NOT NULL, photo VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO recipe (id, label, description, duration, person_count, photo) SELECT id, label, description, duration, person_count, photo FROM __temp__recipe');
        $this->addSql('DROP TABLE __temp__recipe');
    }
}
