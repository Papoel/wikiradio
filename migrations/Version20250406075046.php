<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250406075046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE radiogrammes (id INT AUTO_INCREMENT NOT NULL, tranche INT NOT NULL, systeme VARCHAR(3) NOT NULL, ligne VARCHAR(255) DEFAULT NULL, bigramme VARCHAR(2) DEFAULT NULL, pp VARCHAR(3) DEFAULT NULL, iso VARCHAR(12) DEFAULT NULL, repere VARCHAR(20) NOT NULL, visite VARCHAR(6) DEFAULT NULL, traversee VARCHAR(5) NOT NULL, armoire VARCHAR(5) DEFAULT NULL, etagere VARCHAR(5) DEFAULT NULL, boite VARCHAR(20) DEFAULT NULL, oi VARCHAR(20) DEFAULT NULL, date DATE DEFAULT NULL, rf VARCHAR(255) DEFAULT NULL, is_ips TINYINT(1) NOT NULL, is_qs TINYINT(1) NOT NULL, cpp VARCHAR(255) DEFAULT NULL, csp VARCHAR(255) DEFAULT NULL, commande VARCHAR(255) DEFAULT NULL, titulaire VARCHAR(255) NOT NULL, observation LONGTEXT DEFAULT NULL, unique_value VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_F795F98B14079C24 (unique_value), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE radiogrammes
        SQL);
    }
}
