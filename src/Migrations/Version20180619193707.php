<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180619193707 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE articles CHANGE title title VARCHAR(100) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BFDD31682B36786B ON articles (title)');
        $this->addSql('ALTER TABLE categories CHANGE title title VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE comments CHANGE name name VARCHAR(100) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F9E962A5E237E06 ON comments (name)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_BFDD31682B36786B ON articles');
        $this->addSql('ALTER TABLE articles CHANGE title title VARCHAR(190) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE categories CHANGE title title VARCHAR(190) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('DROP INDEX UNIQ_5F9E962A5E237E06 ON comments');
        $this->addSql('ALTER TABLE comments CHANGE name name VARCHAR(190) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
