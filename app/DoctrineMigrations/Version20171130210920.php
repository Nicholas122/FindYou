<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171130210920 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE translation_constant_value DROP FOREIGN KEY FK_100D44452FED526E');
        $this->addSql('ALTER TABLE translation_constant_value ADD CONSTRAINT FK_100D44452FED526E FOREIGN KEY (translation_constant_id) REFERENCES translation_constant (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE translation_constant_value DROP FOREIGN KEY FK_100D44452FED526E');
        $this->addSql('ALTER TABLE translation_constant_value ADD CONSTRAINT FK_100D44452FED526E FOREIGN KEY (translation_constant_id) REFERENCES translation_constant (id) ON DELETE CASCADE');
    }
}
