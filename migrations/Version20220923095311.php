<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220923095311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_permissions (user_id INT NOT NULL, permissions_id INT NOT NULL, INDEX IDX_84F605FAA76ED395 (user_id), INDEX IDX_84F605FA9C3E4F87 (permissions_id), PRIMARY KEY(user_id, permissions_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_permissions ADD CONSTRAINT FK_84F605FAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_permissions ADD CONSTRAINT FK_84F605FA9C3E4F87 FOREIGN KEY (permissions_id) REFERENCES permissions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permissions_user DROP FOREIGN KEY FK_765F0E6C9C3E4F87');
        $this->addSql('ALTER TABLE permissions_user DROP FOREIGN KEY FK_765F0E6CA76ED395');
        $this->addSql('DROP TABLE permissions_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE permissions_user (permissions_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_765F0E6C9C3E4F87 (permissions_id), INDEX IDX_765F0E6CA76ED395 (user_id), PRIMARY KEY(permissions_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE permissions_user ADD CONSTRAINT FK_765F0E6C9C3E4F87 FOREIGN KEY (permissions_id) REFERENCES permissions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permissions_user ADD CONSTRAINT FK_765F0E6CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_permissions DROP FOREIGN KEY FK_84F605FAA76ED395');
        $this->addSql('ALTER TABLE user_permissions DROP FOREIGN KEY FK_84F605FA9C3E4F87');
        $this->addSql('DROP TABLE user_permissions');
    }
}
