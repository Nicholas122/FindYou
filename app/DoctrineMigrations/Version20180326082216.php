<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180326082216 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_conversation ADD post_id INT DEFAULT NULL, ADD receiver_id INT DEFAULT NULL, ADD creation_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user_conversation ADD CONSTRAINT FK_A425AEB4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_conversation ADD CONSTRAINT FK_A425AEBCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_A425AEB4B89032C ON user_conversation (post_id)');
        $this->addSql('CREATE INDEX IDX_A425AEBCD53EDB6 ON user_conversation (receiver_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_conversation DROP FOREIGN KEY FK_A425AEB4B89032C');
        $this->addSql('ALTER TABLE user_conversation DROP FOREIGN KEY FK_A425AEBCD53EDB6');
        $this->addSql('DROP INDEX IDX_A425AEB4B89032C ON user_conversation');
        $this->addSql('DROP INDEX IDX_A425AEBCD53EDB6 ON user_conversation');
        $this->addSql('ALTER TABLE user_conversation DROP post_id, DROP receiver_id, DROP creation_date');
    }
}
