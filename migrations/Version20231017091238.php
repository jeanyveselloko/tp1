<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231017091238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accomodation_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE announce (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, city_id INT DEFAULT NULL, accomodation_type_id INT DEFAULT NULL, announce_type_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, bedroom_number INT NOT NULL, price_by_night INT NOT NULL, disponibility DATE NOT NULL, address LONGTEXT NOT NULL, INDEX IDX_E6D6DD75A76ED395 (user_id), INDEX IDX_E6D6DD758BAC62AF (city_id), INDEX IDX_E6D6DD753951D299 (accomodation_type_id), INDEX IDX_E6D6DD75FC535DD2 (announce_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE announce_facilities (announce_id INT NOT NULL, facilities_id INT NOT NULL, INDEX IDX_C99B9BB06F5DA3DE (announce_id), INDEX IDX_C99B9BB05263402 (facilities_id), PRIMARY KEY(announce_id, facilities_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE announce_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, announce_id INT DEFAULT NULL, booking_date DATETIME NOT NULL, check_in_date DATETIME NOT NULL, check_out_date DATETIME NOT NULL, number_of_night INT NOT NULL, total_booking INT NOT NULL, price_by_night INT NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_E00CEDDEA76ED395 (user_id), INDEX IDX_E00CEDDE6F5DA3DE (announce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, post_code INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facilities (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, announce_id INT DEFAULT NULL, storage_name VARCHAR(255) NOT NULL, create_at DATE NOT NULL, file_type LONGTEXT NOT NULL, INDEX IDX_14B784186F5DA3DE (announce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, booking_id INT DEFAULT NULL, rate INT NOT NULL, comment LONGTEXT NOT NULL, create_at DATETIME NOT NULL, modifie_at DATETIME NOT NULL, INDEX IDX_D88926223301C60 (booking_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, email LONGTEXT DEFAULT NULL, phone_number VARCHAR(255) NOT NULL, biography LONGTEXT NOT NULL, profile_picture LONGTEXT DEFAULT NULL, birth_date DATE NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE announce ADD CONSTRAINT FK_E6D6DD75A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE announce ADD CONSTRAINT FK_E6D6DD758BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE announce ADD CONSTRAINT FK_E6D6DD753951D299 FOREIGN KEY (accomodation_type_id) REFERENCES accomodation_type (id)');
        $this->addSql('ALTER TABLE announce ADD CONSTRAINT FK_E6D6DD75FC535DD2 FOREIGN KEY (announce_type_id) REFERENCES announce_type (id)');
        $this->addSql('ALTER TABLE announce_facilities ADD CONSTRAINT FK_C99B9BB06F5DA3DE FOREIGN KEY (announce_id) REFERENCES announce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE announce_facilities ADD CONSTRAINT FK_C99B9BB05263402 FOREIGN KEY (facilities_id) REFERENCES facilities (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE6F5DA3DE FOREIGN KEY (announce_id) REFERENCES announce (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B784186F5DA3DE FOREIGN KEY (announce_id) REFERENCES announce (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926223301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE announce DROP FOREIGN KEY FK_E6D6DD75A76ED395');
        $this->addSql('ALTER TABLE announce DROP FOREIGN KEY FK_E6D6DD758BAC62AF');
        $this->addSql('ALTER TABLE announce DROP FOREIGN KEY FK_E6D6DD753951D299');
        $this->addSql('ALTER TABLE announce DROP FOREIGN KEY FK_E6D6DD75FC535DD2');
        $this->addSql('ALTER TABLE announce_facilities DROP FOREIGN KEY FK_C99B9BB06F5DA3DE');
        $this->addSql('ALTER TABLE announce_facilities DROP FOREIGN KEY FK_C99B9BB05263402');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEA76ED395');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE6F5DA3DE');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B784186F5DA3DE');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926223301C60');
        $this->addSql('DROP TABLE accomodation_type');
        $this->addSql('DROP TABLE announce');
        $this->addSql('DROP TABLE announce_facilities');
        $this->addSql('DROP TABLE announce_type');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE facilities');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE user');
    }
}
