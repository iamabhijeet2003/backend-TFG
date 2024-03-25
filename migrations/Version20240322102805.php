<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240322102805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ratings (id INT AUTO_INCREMENT NOT NULL, product_id_id INT NOT NULL, user_id_id INT DEFAULT NULL, rating_value INT NOT NULL, comment VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_CEB607C9DE18E50B (product_id_id), INDEX IDX_CEB607C99D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ratings ADD CONSTRAINT FK_CEB607C9DE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE ratings ADD CONSTRAINT FK_CEB607C99D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ratings DROP FOREIGN KEY FK_CEB607C9DE18E50B');
        $this->addSql('ALTER TABLE ratings DROP FOREIGN KEY FK_CEB607C99D86650F');
        $this->addSql('DROP TABLE ratings');
    }
}
