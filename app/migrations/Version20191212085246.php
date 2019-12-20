<?php

declare (strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Added password for user
 * 
 * @author Константин Штыков (SHTIKOV)
 */
final class Version20191212085246 extends AbstractMigration {
    public function getDescription (): string {
        return '';
    }

    public function up (Schema $schema): void {
        $this->abortIf ($this->connection->getDatabasePlatform ()->getName () !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql ('ALTER TABLE users ADD password VARCHAR(255) NOT NULL');
    }

    public function down (Schema $schema): void {
        $this->abortIf ($this->connection->getDatabasePlatform ()->getName () !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql ('ALTER TABLE users DROP password');
    }
}
