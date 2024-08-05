<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240804195344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE market (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE market_product (market_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_DADCEC2D622F3F37 (market_id), INDEX IDX_DADCEC2D4584665A (product_id), PRIMARY KEY(market_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE market_product ADD CONSTRAINT FK_DADCEC2D622F3F37 FOREIGN KEY (market_id) REFERENCES market (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE market_product ADD CONSTRAINT FK_DADCEC2D4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product CHANGE description description VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE market_product DROP FOREIGN KEY FK_DADCEC2D622F3F37');
        $this->addSql('ALTER TABLE market_product DROP FOREIGN KEY FK_DADCEC2D4584665A');
        $this->addSql('DROP TABLE market');
        $this->addSql('DROP TABLE market_product');
        $this->addSql('ALTER TABLE product CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE image_url image_url VARCHAR(10000) DEFAULT NULL');
    }
}
