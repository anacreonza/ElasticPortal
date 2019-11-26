<?php
return [
    'keys' => [
        // Selected document types
        'doc_types' => [
            'image',
            'article',
            'pdf'
        ],
        // Doc type key
        'doctype' => 'ATTRIBUTES.METADATA.GENERAL.DOCTYPE',
        'doctype_exact' => 'ATTRIBUTES.METADATA.GENERAL.DOCTYPE.exact',
        'newspapers' => 'ATTRIBUTES.METADATA.PUBDATA.PAPER.NEWSPAPERS',
        'pageref' => 'ATTRIBUTES.METADATA.PUBDATA.PAPER.PAGEREFERENCE',
        'docauthor' => 'ATTRIBUTES.METADATA.GENERAL.DOCAUTHOR',
        'name' => 'OBJECTINFO.NAME',
        'objecttype' => 'OBJECTINFO.TYPE',
        'sysobjecttype' => 'SYSTEM.OBJECTTYPE',

        // Dates
        'date_created' => 'ATTRIBUTES.METADATA.GENERAL.DATE_CREATED',
        'issuedate' => 'SYSATTRIBUTES.PROPS.PRODUCTINFO.ISSUEDATE',
        'date_publication' => 'ATTRIBUTES.METADATA.PUBDATA.PAPER.DATEPUBLICATION',

        // 
        'category' => 'ATTRIBUTES.METADATA.GENERAL.CATEGORY',
        'section' => 'ATTRIBUTES.METADATA.PUBDATA.PAPER.SECTION',
        'docauthor' => 'ATTRIBUTES.METADATA.GENERAL.DOCAUTHOR',
        'imageauthor' => 'SYSATTRIBUTES.PROPS.IMAGEINFO.DOCUMENTINFO.AUTHOR',
        'dockeyword' => 'ATTRIBUTES.METADATA.GENERAL.DOCKEYWORD',
        'doctitle' => 'ATTRIBUTES.METADATA.GENERAL.DOCTITLE',

        // Path to object
        'path' => 'SYSTEM.ALERTPATH',

        // Publication key
        'product' => 'ATTRIBUTES.METADATA.PUBDATA.PAPER.PRODUCT.keyword',

        // Fields used for full text search
        'textsearch_fields' => [
            'CONTENT.XMLFLAT',
            'CONTENT.TEXT',
            'ATTRIBUTES.METADATA.GENERAL.DOCKEYWORD',
            'ATTRIBUTES.METADATA.GENERAL.DOCTITLE',
            'ATTRIBUTES.METADATA.GENERAL.CUSTOM_CAPTION',
            'SYSATTRIBUTES.PROPS.SUMMARY'
        ]
    ]
];
?>