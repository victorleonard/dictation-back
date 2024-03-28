<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240328113257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_word (user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', word_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_B97039D8A76ED395 (user_id), INDEX IDX_B97039D8E357438D (word_id), PRIMARY KEY(user_id, word_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE word (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', value VARCHAR(255) NOT NULL, level VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_word ADD CONSTRAINT FK_B97039D8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_word ADD CONSTRAINT FK_B97039D8E357438D FOREIGN KEY (word_id) REFERENCES word (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_word DROP FOREIGN KEY FK_B97039D8A76ED395');
        $this->addSql('ALTER TABLE user_word DROP FOREIGN KEY FK_B97039D8E357438D');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_word');
        $this->addSql('DROP TABLE word');
    }
}
