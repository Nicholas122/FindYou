<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171111193227 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE translation_constant (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE translation_constant_value (id INT AUTO_INCREMENT NOT NULL, language_id INT DEFAULT NULL, translation_constant_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_100D444582F1BAF4 (language_id), INDEX IDX_100D44452FED526E (translation_constant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE translation_constant_value ADD CONSTRAINT FK_100D444582F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE translation_constant_value ADD CONSTRAINT FK_100D44452FED526E FOREIGN KEY (translation_constant_id) REFERENCES translation_constant (id)');
        $this->addSql('ALTER TABLE user ADD language_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64982F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64982F1BAF4 ON user (language_id)');
        $this->addSql('ALTER TABLE language ADD is_default SMALLINT NOT NULL, DROP prefix');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE translation_constant_value DROP FOREIGN KEY FK_100D44452FED526E');
        $this->addSql('DROP TABLE translation_constant');
        $this->addSql('DROP TABLE translation_constant_value');
        $this->addSql('ALTER TABLE language ADD prefix VARCHAR(11) NOT NULL COLLATE utf8_unicode_ci, DROP is_default');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64982F1BAF4');
        $this->addSql('DROP INDEX IDX_8D93D64982F1BAF4 ON user');
        $this->addSql('ALTER TABLE user DROP language_id');
    }
}
