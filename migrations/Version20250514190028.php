<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250514190028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE ingrediente (id INT AUTO_INCREMENT NOT NULL, receta_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, cantidad DOUBLE PRECISION NOT NULL, unidad VARCHAR(255) NOT NULL, INDEX IDX_BFB4A41E54F853F8 (receta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE paso (id INT AUTO_INCREMENT NOT NULL, receta_id INT DEFAULT NULL, descripcion LONGTEXT NOT NULL, orden INT NOT NULL, INDEX IDX_DA71886B54F853F8 (receta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE receta (id INT AUTO_INCREMENT NOT NULL, num_comensales INT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE receta_nutriente (id INT AUTO_INCREMENT NOT NULL, receta_id INT DEFAULT NULL, tipo_nutriente_id INT DEFAULT NULL, cantidad DOUBLE PRECISION NOT NULL, unidad VARCHAR(255) NOT NULL, INDEX IDX_5A698B7C54F853F8 (receta_id), INDEX IDX_5A698B7C1A19FC9D (tipo_nutriente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tipo_nutriente (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, unidad VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE voto (id INT AUTO_INCREMENT NOT NULL, receta_id INT DEFAULT NULL, valor INT NOT NULL, ip VARCHAR(255) NOT NULL, INDEX IDX_BAC56C7A54F853F8 (receta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingrediente ADD CONSTRAINT FK_BFB4A41E54F853F8 FOREIGN KEY (receta_id) REFERENCES receta (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE paso ADD CONSTRAINT FK_DA71886B54F853F8 FOREIGN KEY (receta_id) REFERENCES receta (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE receta_nutriente ADD CONSTRAINT FK_5A698B7C54F853F8 FOREIGN KEY (receta_id) REFERENCES receta (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE receta_nutriente ADD CONSTRAINT FK_5A698B7C1A19FC9D FOREIGN KEY (tipo_nutriente_id) REFERENCES tipo_nutriente (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE voto ADD CONSTRAINT FK_BAC56C7A54F853F8 FOREIGN KEY (receta_id) REFERENCES receta (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE ingrediente DROP FOREIGN KEY FK_BFB4A41E54F853F8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE paso DROP FOREIGN KEY FK_DA71886B54F853F8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE receta_nutriente DROP FOREIGN KEY FK_5A698B7C54F853F8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE receta_nutriente DROP FOREIGN KEY FK_5A698B7C1A19FC9D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE voto DROP FOREIGN KEY FK_BAC56C7A54F853F8
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ingrediente
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE paso
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE receta
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE receta_nutriente
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tipo_nutriente
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE voto
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
