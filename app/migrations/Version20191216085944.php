<?php

declare (strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Added tasks table
 * 
 * @author Константин Штыков (SHTIKOV)
 */
final class Version20191216085944 extends AbstractMigration {
    public function getDescription (): string {
        return '';
    }

    public function up (Schema $schema): void {
        $this->abortIf ($this->connection->getDatabasePlatform ()->getName () !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql ('CREATE TABLE tasks (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, isEdited TINYINT(1) NOT NULL, created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
    }

    public function down (Schema $schema): void {
        $this->abortIf ($this->connection->getDatabasePlatform ()->getName () !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql ('DROP TABLE tasks');
    }
}
