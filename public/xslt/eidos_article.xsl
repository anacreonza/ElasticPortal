<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:html="http://www.w3.org/Profiles/XHTML-transitional" exclude-result-prefixes="html">
    <xsl:strip-space elements="*"/>
    <xsl:output method="xml" indent="no" encoding="utf-8" doctype-system="/SysConfig/Rules/eidosint.dtd"/>
    <xsl:template match="*|@*">
        <doc>
            <xsl:attribute name="xml:lang"><xsl:value-of select="/doc/@xml:lang"/></xsl:attribute>
            <xsl:copy-of select="//doc/story"/>
            <dbMetadata>
                <Metadata>
                    <xsl:copy-of select="//doc/dbMetadata/Metadata/PubData/Web"/>
                </Metadata>
            </dbMetadata>
        </doc>
    </xsl:template>
</xsl:stylesheet>