<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240103115942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pet (id INT AUTO_INCREMENT NOT NULL, race_id INT NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, xp INT NOT NULL, niveau INT NOT NULL, ad INT NOT NULL, ap INT NOT NULL, mana INT NOT NULL, pv INT NOT NULL, INDEX IDX_E4529B856E59D40D (race_id), INDEX IDX_E4529B857E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE race (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pet ADD CONSTRAINT FK_E4529B856E59D40D FOREIGN KEY (race_id) REFERENCES race (id)');
        $this->addSql('ALTER TABLE pet ADD CONSTRAINT FK_E4529B857E3C61F9 FOREIGN KEY (owner_id) REFERENCES player (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pet DROP FOREIGN KEY FK_E4529B856E59D40D');
        $this->addSql('ALTER TABLE pet DROP FOREIGN KEY FK_E4529B857E3C61F9');
        $this->addSql('DROP TABLE pet');
        $this->addSql('DROP TABLE race');
    }
}
