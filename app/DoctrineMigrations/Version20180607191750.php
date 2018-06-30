<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180607191750 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        //$this->addSql('DROP TABLE post_external_characteristic');
        $this->addSql('ALTER TABLE post ADD meeting_date_start DATETIME DEFAULT NULL, ADD meeting_date_end DATETIME DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE post_external_characteristic (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, external_characteristic_id INT DEFAULT NULL, external_characteristic_value TEXT DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_FE15ED7BC5F33C8D (external_characteristic_id), INDEX IDX_FE15ED7B4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_external_characteristic ADD CONSTRAINT FK_FE15ED7B4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_external_characteristic ADD CONSTRAINT FK_FE15ED7BC5F33C8D FOREIGN KEY (external_characteristic_id) REFERENCES external_characteristic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post DROP meeting_date_start, DROP meeting_date_end');
    }
}
