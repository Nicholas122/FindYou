<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171113195429 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE external_characteristic (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type ENUM(\'int\', \'string\', \'choice\') NOT NULL COMMENT \'(DC2Type:enum_external_characteristic_type)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE external_characteristic_choise_value (id INT AUTO_INCREMENT NOT NULL, external_characteristic_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_3A68881AC5F33C8D (external_characteristic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE external_characteristic_choise_value ADD CONSTRAINT FK_3A68881AC5F33C8D FOREIGN KEY (external_characteristic_id) REFERENCES external_characteristic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE translation_constant_value DROP FOREIGN KEY FK_100D44452FED526E');
        $this->addSql('ALTER TABLE translation_constant_value DROP FOREIGN KEY FK_100D444582F1BAF4');
        $this->addSql('ALTER TABLE translation_constant_value ADD CONSTRAINT FK_100D44452FED526E FOREIGN KEY (translation_constant_id) REFERENCES translation_constant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE translation_constant_value ADD CONSTRAINT FK_100D444582F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE external_characteristic_choise_value DROP FOREIGN KEY FK_3A68881AC5F33C8D');
        $this->addSql('DROP TABLE external_characteristic');
        $this->addSql('DROP TABLE external_characteristic_choise_value');
        $this->addSql('ALTER TABLE translation_constant_value DROP FOREIGN KEY FK_100D444582F1BAF4');
        $this->addSql('ALTER TABLE translation_constant_value DROP FOREIGN KEY FK_100D44452FED526E');
        $this->addSql('ALTER TABLE translation_constant_value ADD CONSTRAINT FK_100D444582F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE translation_constant_value ADD CONSTRAINT FK_100D44452FED526E FOREIGN KEY (translation_constant_id) REFERENCES translation_constant (id)');
    }
}
