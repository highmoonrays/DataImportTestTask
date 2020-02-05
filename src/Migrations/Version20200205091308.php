<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200205091308 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Product (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, code VARCHAR(10) NOT NULL, added DATETIME DEFAULT NULL, discontinued DATETIME DEFAULT NULL, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, stock INT DEFAULT NULL, cost DOUBLE PRECISION NOT NULL, UNIQUE INDEX Product (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE tblProductData');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tblProductData (intProductDataId INT UNSIGNED AUTO_INCREMENT NOT NULL, strProductName VARCHAR(50) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, strProductDesc VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, strProductCode VARCHAR(10) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, dtmAdded DATETIME DEFAULT NULL, dtmDiscontinued DATETIME DEFAULT NULL, stmTimestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, stock INT DEFAULT NULL, cost_in_gbp DOUBLE PRECISION NOT NULL, UNIQUE INDEX strProductCode (strProductCode), PRIMARY KEY(intProductDataId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'Stores product data\' ');
        $this->addSql('DROP TABLE Product');
    }
}
