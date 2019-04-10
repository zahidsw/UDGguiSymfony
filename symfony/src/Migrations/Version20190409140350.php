<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190409140350 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, city_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, userName VARCHAR(255) NOT NULL, roles JSON NOT NULL, enabled TINYINT(1) NOT NULL, subjectToken VARCHAR(255) NOT NULL, keyrockId VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6498BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_route (user_id INT NOT NULL, route_id INT NOT NULL, INDEX IDX_E006DB21A76ED395 (user_id), INDEX IDX_E006DB2134ECB4E6 (route_id), PRIMARY KEY(user_id, route_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_menu (user_id INT NOT NULL, menu_id INT NOT NULL, color VARCHAR(255) NOT NULL, INDEX IDX_784765AA76ED395 (user_id), INDEX IDX_784765ACCD7E912 (menu_id), PRIMARY KEY(user_id, menu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE apps (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, url LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Purchase (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, timestamp VARCHAR(255) DEFAULT NULL, service_id VARCHAR(255) NOT NULL, service_description VARCHAR(255) NOT NULL, request_url VARCHAR(255) NOT NULL, order_number INT NOT NULL, product_sku VARCHAR(255) NOT NULL, product_name VARCHAR(255) NOT NULL, product_quantity INT NOT NULL, product_price INT NOT NULL, product_currency VARCHAR(255) NOT NULL, callback_url VARCHAR(255) DEFAULT NULL, INDEX IDX_9861B36DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_config_param (user_id INT NOT NULL, value VARCHAR(255) NOT NULL, configParam_id INT NOT NULL, INDEX IDX_991B52CBA76ED395 (user_id), INDEX IDX_991B52CBAE6589B8 (configParam_id), PRIMARY KEY(user_id, configParam_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE config_param (id INT AUTO_INCREMENT NOT NULL, param VARCHAR(255) NOT NULL, default_value VARCHAR(255) NOT NULL, paramCategorie_id INT NOT NULL, INDEX IDX_19D72EC913B46620 (paramCategorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, route_id INT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7D053A935E237E06 (name), INDEX IDX_7D053A9334ECB4E6 (route_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE route (id INT AUTO_INCREMENT NOT NULL, menu_id INT NOT NULL, display_name VARCHAR(255) NOT NULL, bundle_prefix VARCHAR(255) NOT NULL, route VARCHAR(255) NOT NULL, visibleForBookmark TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_2C420792C42079 (route), INDEX IDX_2C42079CCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE webservice_param (id INT AUTO_INCREMENT NOT NULL, param VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D0D26739A4FA7C89 (param), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE config_param_cat (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, abbreviation VARCHAR(10) NOT NULL, icon VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE user_route ADD CONSTRAINT FK_E006DB21A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_route ADD CONSTRAINT FK_E006DB2134ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_menu ADD CONSTRAINT FK_784765AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_menu ADD CONSTRAINT FK_784765ACCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE Purchase ADD CONSTRAINT FK_9861B36DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_config_param ADD CONSTRAINT FK_991B52CBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_config_param ADD CONSTRAINT FK_991B52CBAE6589B8 FOREIGN KEY (configParam_id) REFERENCES config_param (id)');
        $this->addSql('ALTER TABLE config_param ADD CONSTRAINT FK_19D72EC913B46620 FOREIGN KEY (paramCategorie_id) REFERENCES config_param_cat (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A9334ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id)');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C42079CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_route DROP FOREIGN KEY FK_E006DB21A76ED395');
        $this->addSql('ALTER TABLE user_menu DROP FOREIGN KEY FK_784765AA76ED395');
        $this->addSql('ALTER TABLE Purchase DROP FOREIGN KEY FK_9861B36DA76ED395');
        $this->addSql('ALTER TABLE user_config_param DROP FOREIGN KEY FK_991B52CBA76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498BAC62AF');
        $this->addSql('ALTER TABLE user_config_param DROP FOREIGN KEY FK_991B52CBAE6589B8');
        $this->addSql('ALTER TABLE user_menu DROP FOREIGN KEY FK_784765ACCD7E912');
        $this->addSql('ALTER TABLE route DROP FOREIGN KEY FK_2C42079CCD7E912');
        $this->addSql('ALTER TABLE user_route DROP FOREIGN KEY FK_E006DB2134ECB4E6');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A9334ECB4E6');
        $this->addSql('ALTER TABLE config_param DROP FOREIGN KEY FK_19D72EC913B46620');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_route');
        $this->addSql('DROP TABLE user_menu');
        $this->addSql('DROP TABLE apps');
        $this->addSql('DROP TABLE Purchase');
        $this->addSql('DROP TABLE user_config_param');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE config_param');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE route');
        $this->addSql('DROP TABLE webservice_param');
        $this->addSql('DROP TABLE config_param_cat');
        $this->addSql('DROP TABLE language');
    }
}
