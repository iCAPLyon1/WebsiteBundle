<?php

namespace Icap\WebsiteBundle\Migrations\pdo_sqlite;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2014/07/07 03:19:14
 */
class Version20140707151913 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE icap__website_page (
                id INTEGER NOT NULL, 
                resource_node_id INTEGER DEFAULT NULL, 
                website_id INTEGER NOT NULL, 
                parent_id INTEGER DEFAULT NULL, 
                visible BOOLEAN NOT NULL, 
                creation_date DATETIME NOT NULL, 
                title VARCHAR(255) NOT NULL, 
                richText CLOB DEFAULT NULL, 
                url VARCHAR(255) DEFAULT NULL, 
                isSection BOOLEAN NOT NULL, 
                description VARCHAR(255) DEFAULT NULL, 
                lft INTEGER NOT NULL, 
                lvl INTEGER NOT NULL, 
                rgt INTEGER NOT NULL, 
                root INTEGER DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_FB66D1D41BAD783F ON icap__website_page (resource_node_id)
        ");
        $this->addSql("
            CREATE INDEX IDX_FB66D1D418F45C82 ON icap__website_page (website_id)
        ");
        $this->addSql("
            CREATE INDEX IDX_FB66D1D4727ACA70 ON icap__website_page (parent_id)
        ");
        $this->addSql("
            CREATE TABLE icap__website_options (
                id INTEGER NOT NULL, 
                website_id INTEGER DEFAULT NULL, 
                copyrightEnabled BOOLEAN NOT NULL, 
                copyrightText VARCHAR(255) DEFAULT NULL, 
                analyticsProvider VARCHAR(255) DEFAULT NULL, 
                analyticsAccountId VARCHAR(255) DEFAULT NULL, 
                cssCode CLOB DEFAULT NULL, 
                bgColor VARCHAR(255) DEFAULT NULL, 
                bgImage VARCHAR(255) DEFAULT NULL, 
                bgRepeat VARCHAR(255) DEFAULT NULL, 
                bgPosition VARCHAR(255) DEFAULT NULL, 
                bannerBgColor VARCHAR(255) DEFAULT NULL, 
                bannerBgImage VARCHAR(255) DEFAULT NULL, 
                bannerBgRepeat VARCHAR(255) DEFAULT NULL, 
                bannerBgPosition VARCHAR(255) DEFAULT NULL, 
                bannerHeight INTEGER DEFAULT NULL, 
                bannerEnabled BOOLEAN NOT NULL, 
                bannerText CLOB DEFAULT NULL, 
                footerBgColor VARCHAR(255) DEFAULT NULL, 
                footerBgImage VARCHAR(255) DEFAULT NULL, 
                footerBgRepeat VARCHAR(255) DEFAULT NULL, 
                footerBgPosition VARCHAR(255) DEFAULT NULL, 
                footerHeight INTEGER DEFAULT NULL, 
                footerEnabled BOOLEAN NOT NULL, 
                footerText CLOB DEFAULT NULL, 
                menuBgColor VARCHAR(255) DEFAULT NULL, 
                sectionBgColor VARCHAR(255) DEFAULT NULL, 
                menuBorderColor VARCHAR(255) DEFAULT NULL, 
                menuFontColor VARCHAR(255) DEFAULT NULL, 
                menuHoverColor VARCHAR(255) DEFAULT NULL, 
                menuFontFamily VARCHAR(255) DEFAULT NULL, 
                menuFontStyle VARCHAR(255) DEFAULT NULL, 
                menuFontWeight VARCHAR(255) DEFAULT NULL, 
                menuWidth INTEGER DEFAULT NULL, 
                menuOrientation VARCHAR(255) DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_C40F17718F45C82 ON icap__website_options (website_id)
        ");
        $this->addSql("
            CREATE TABLE icap__website (
                id INTEGER NOT NULL, 
                creation_date DATETIME NOT NULL, 
                resourceNode_id INTEGER DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_452309F8B87FAB32 ON icap__website (resourceNode_id)
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            DROP TABLE icap__website_page
        ");
        $this->addSql("
            DROP TABLE icap__website_options
        ");
        $this->addSql("
            DROP TABLE icap__website
        ");
    }
}