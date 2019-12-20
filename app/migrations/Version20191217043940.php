<?php

declare (strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Fix for task description
 * 
 * @author Константин Штыков (SHTIKOV)
 */
final class Version20191217043940 extends AbstractMigration {
    public function getDescription (): string {
        return '';
    }

    public function up (Schema $schema): void {
        $this->abortIf ($this->connection->getDatabasePlatform ()->getName () !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql ('ALTER TABLE tasks CHANGE description description LONGTEXT NOT NULL');
    }

    public function down (Schema $schema): void {
        $this->abortIf ($this->connection->getDatabasePlatform ()->getName () !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql ('ALTER TABLE tasks CHANGE description description VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
    }
}
