<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190221141733 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Menu (id INT AUTO_INCREMENT NOT NULL, route_id INT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_DD3795AD5E237E06 (name), INDEX IDX_DD3795AD34ECB4E6 (route_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Route (id INT AUTO_INCREMENT NOT NULL, menu_id INT NOT NULL, display_name VARCHAR(255) NOT NULL, bundle_prefix VARCHAR(255) NOT NULL, route VARCHAR(255) NOT NULL, visibleForBookmark TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_C3050F7D2C42079 (route), INDEX IDX_C3050F7DCCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Menu ADD CONSTRAINT FK_DD3795AD34ECB4E6 FOREIGN KEY (route_id) REFERENCES Route (id)');
        $this->addSql('ALTER TABLE Route ADD CONSTRAINT FK_C3050F7DCCD7E912 FOREIGN KEY (menu_id) REFERENCES Menu (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Route DROP FOREIGN KEY FK_C3050F7DCCD7E912');
        $this->addSql('ALTER TABLE Menu DROP FOREIGN KEY FK_DD3795AD34ECB4E6');
        $this->addSql('DROP TABLE Menu');
        $this->addSql('DROP TABLE Route');
    }
}
