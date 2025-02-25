# ==============================================
# Persistence object mapping configuration
# ==============================================
config.tx_extbase.persistence.classes {
    GeorgRinger\News\Domain\Model\News {
        subclasses {
            0 = GeorgRinger\News\Domain\Model\NewsDefault
            1 = GeorgRinger\News\Domain\Model\NewsInternal
            2 = GeorgRinger\News\Domain\Model\NewsExternal
        }
    }
    GeorgRinger\News\Domain\Model\NewsDefault {
        mapping {
            recordType = 0
            tableName = tx_news_domain_model_news
        }
    }
    GeorgRinger\News\Domain\Model\NewsInternal {
        mapping {
            recordType = 1
            tableName = tx_news_domain_model_news
        }
    }
    GeorgRinger\News\Domain\Model\NewsExternal {
        mapping {
            recordType = 2
            tableName = tx_news_domain_model_news
        }
    }
    GeorgRinger\News\Domain\Model\FileReference {
        mapping {
            tableName = sys_file_reference
        }
    }
    GeorgRinger\News\Domain\Model\TtContent {
        mapping {
            tableName = tt_content
            columns {
                altText.mapOnProperty = altText
                titleText.mapOnProperty = titleText
                colPos.mapOnProperty = colPos
                CType.mapOnProperty = CType
            }
        }
    }
    GeorgRinger\News\Domain\Model\Category {
        mapping {
            tableName = sys_category
            columns {
                parent.mapOnProperty = parentcategory
            }
        }
    }
    Ssch\TYPO3Rector\Tests\FileProcessor\TypoScript\Source\Address {
        mapping {
            tableName = tt_address
            #recordType = 0
        }
        subclasses {
            Extension14v\Abook\Domain\Model\Address = Extension14v\Abook\Domain\Model\Address
        }
    }
}

plugin.tx_maps2 {
    persistence {
        classes {
            JWeiland\Maps2\Domain\Model\PoiCollection {
                mapping {
                    columns {
                        distance {
                            config {
                                type = passthrough
                            }
                        }
                        foo.config {
                            type = passthrough
                        }
                    }
                }
            }
        }
    }
}
