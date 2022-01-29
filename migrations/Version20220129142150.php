<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220129142150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bookings (id INT AUTO_INCREMENT NOT NULL, program_id INT NOT NULL, user_cnp INT NOT NULL, INDEX IDX_7A853C353EB8070A (program_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `interval` (id INT AUTO_INCREMENT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE program (id INT AUTO_INCREMENT NOT NULL, time_interval_id INT NOT NULL, max_participants INT NOT NULL, room INT NOT NULL, INDEX IDX_92ED77848E6930C7 (time_interval_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bookings ADD CONSTRAINT FK_7A853C353EB8070A FOREIGN KEY (program_id) REFERENCES program (id)');
        $this->addSql('ALTER TABLE program ADD CONSTRAINT FK_92ED77848E6930C7 FOREIGN KEY (time_interval_id) REFERENCES `interval` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE program DROP FOREIGN KEY FK_92ED77848E6930C7');
        $this->addSql('ALTER TABLE bookings DROP FOREIGN KEY FK_7A853C353EB8070A');
        $this->addSql('DROP TABLE bookings');
        $this->addSql('DROP TABLE `interval`');
        $this->addSql('DROP TABLE program');
    }
}
