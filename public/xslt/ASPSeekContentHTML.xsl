<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:lxslt="http://xml.apache.org/xslt">

	<xsl:output method="html" encoding="utf-8" />
	
	<xsl:template match="span">
		<xsl:apply-templates/>
	</xsl:template>

	<!--  delete style processing instructions (xml-stylesheet)   -->
	<xsl:template match="processing-instruction('xml-stylesheet')"></xsl:template>
	
	<xsl:template match="*">
	<xsl:element name="{name()}">
		<xsl:apply-templates select="@*"/>
			<xsl:apply-templates/>
		</xsl:element>
	</xsl:template>

	<!-- Convert the ln tag into the headline	 -->
	<xsl:template match="ln">
		<h1 class="headline"><xsl:apply-templates select="node()"/></h1>
	</xsl:template>

	<!-- Convert the doc tag to a div - also need to deal with the language attribute -->
	<xsl:template match="doc">
		<div class="doc"><xsl:apply-templates select="node()"/></div>
	</xsl:template>

	<!-- Convert the story tag to a div -->
	<xsl:template match="story">
		<div><xsl:apply-templates select="node()"/></div>
	</xsl:template>

	<!-- Convert the text tag to a div -->
	<xsl:template match="text">
		<div><xsl:apply-templates select="node()"/></div>
	</xsl:template>

	<!-- Convert the extra tag to a div -->
	<xsl:template match="extra">
		<div><xsl:apply-templates select="node()"/></div>
	</xsl:template>

	<!-- Convert the headline tag to a div -->
	<xsl:template match="headline">
		<div><xsl:apply-templates select="node()"/></div>
	</xsl:template>

	<!-- Convert the byline tag to a div with the byline class -->
	<xsl:template match="byline">
		<div class="byline"><xsl:apply-templates select="node()"/></div>
	</xsl:template>

	<!-- Convert the name tag to a div with the name class -->
	<xsl:template match="name">
		<div class="name"><xsl:apply-templates select="node()"/></div>
	</xsl:template>

	<!-- Convert the p tag to a p -->
	<xsl:template match="p">
		<p><xsl:apply-templates select="node()"/></p>
	</xsl:template>

	<!-- Convert the grouphead tag to a div -->
	<xsl:template match="grouphead">
		<div class="grouphead"><xsl:apply-templates select="node()"/></div>
	</xsl:template>

	<!-- Convert the grouphead tag to a div -->
	<xsl:template match="grouphead">
		<div class="grouphead"><xsl:apply-templates select="node()"/></div>
	</xsl:template>

	<!-- Convert the photogroup tag -->
	<xsl:template match="photo-group">
		<div class="photo-group"><xsl:apply-templates select="node()"/></div>
	</xsl:template>

	<!-- Convert the fg-photo tag -->
	<xsl:template match="fg-photo">
		<div class="fg-photo"><xsl:apply-templates select="node()"/></div>
	</xsl:template>

	<!-- Convert the photo-caption tag -->
	<xsl:template match="photo-caption">
		<div class="photo-caption"><xsl:apply-templates select="node()"/></div>
	</xsl:template>

	<!-- Convert the ld tag -->
	<xsl:template match="ld">
		<div class="ld"><xsl:apply-templates select="node()"/></div>
	</xsl:template>

	<!-- Convert the source tag -->
	<xsl:template match="source">
		<div class="source"><xsl:apply-templates select="node()"/></div>
	</xsl:template>

	<!-- Convert the s1 tag -->
	<xsl:template match="s1">
		<div class="pic-caption"><xsl:apply-templates select="node()"/></div>
	</xsl:template>

</xsl:stylesheet>



