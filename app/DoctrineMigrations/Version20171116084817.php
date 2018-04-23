<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171116084817 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE external_characteristic_choice_value (id INT AUTO_INCREMENT NOT NULL, external_characteristic_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_46D3B931C5F33C8D (external_characteristic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE external_characteristic_choice_value ADD CONSTRAINT FK_46D3B931C5F33C8D FOREIGN KEY (external_characteristic_id) REFERENCES external_characteristic (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE external_characteristic_choise_value');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE external_characteristic_choise_value (id INT AUTO_INCREMENT NOT NULL, external_characteristic_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_3A68881AC5F33C8D (external_characteristic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE external_characteristic_choise_value ADD CONSTRAINT FK_3A68881AC5F33C8D FOREIGN KEY (external_characteristic_id) REFERENCES external_characteristic (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE external_characteristic_choice_value');
    }
}
