<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230107165506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bien_porteur (bien_id INT NOT NULL, porteur_id INT NOT NULL, INDEX IDX_6B969585BD95B80F (bien_id), INDEX IDX_6B9695855176442D (porteur_id), PRIMARY KEY(bien_id, porteur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bien_porteur ADD CONSTRAINT FK_6B969585BD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bien_porteur ADD CONSTRAINT FK_6B9695855176442D FOREIGN KEY (porteur_id) REFERENCES porteur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien_porteur DROP FOREIGN KEY FK_6B969585BD95B80F');
        $this->addSql('ALTER TABLE bien_porteur DROP FOREIGN KEY FK_6B9695855176442D');
        $this->addSql('DROP TABLE bien_porteur');
    }
}
