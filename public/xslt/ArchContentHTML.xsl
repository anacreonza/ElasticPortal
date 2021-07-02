<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:lxslt="http://xml.apache.org/xslt">

	<xsl:output method="html" encoding="utf-8" />
	
	<!-- <xsl:template match="/">
		<xsl:processing-instruction name="xml-stylesheet">href="/SysConfig/Styles/css/Shared/archiveMainprint.css" type="text/css"</xsl:processing-instruction>
		<xsl:apply-templates/>
	</xsl:template> -->
	
	<xsl:template match="span">
		<xsl:apply-templates/>
	</xsl:template>

	<!--  delete style processing instructions (xml-stylesheet)   -->
	<xsl:template match="processing-instruction('xml-stylesheet')"></xsl:template>

	<!--  delete external DTD   -->
	<xsl:template match="processing-instruction('EM-dtdExt')"></xsl:template>

	<!--  delete EM templatename   -->
	<xsl:template match="processing-instruction('EM-templateName')"></xsl:template>

	<!--  delete EM dummytext   -->
	<xsl:template match="processing-instruction('EM-dummyText')"></xsl:template>

	 <!-- keep other processing instructions (dtx, template name, dummy text, ...)  
	<xsl:template match="processing-instruction()">
		<xsl:processing-instruction name="{name()}">
			<xsl:value-of select="."/>
		</xsl:processing-instruction>
	</xsl:template> -->
	
	<!--  discard the style element (internal stylesheet)  -->
	<xsl:template match="style">
	</xsl:template>
	
	
	<!-- discard the style, csl and id attributes  -->
	<xsl:template match="@style|@csl|@id">
	</xsl:template>
	
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

	<!-- Convert the ln tag into the headline	 -->
	<xsl:template match="subhead">
		<h2 class="subhead"><xsl:apply-templates select="node()"/></h2>
	</xsl:template>

	<!-- Convert the doc tag to a div - also need to deal with the language attribute -->
	<xsl:template match="doc">
		<div class="doc" language="{@xml:lang}"><xsl:apply-templates select="node()"/></div>
	</xsl:template>

	<!-- <xsl:template match="@story">
		<xsl:attribute name="{name()}">
			<p><xsl:value-of select="."/></p>
		</xsl:attribute>
	</xsl:template> -->

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

	<!-- Convert the fg-photo tag into an img -->
	<xsl:template match="fg-photo">
		<div class="story-image"><a href="http://152.111.25.65:4400{@fileref}"><img class="fg-photo" src="http://152.111.25.65:4400{@fileref}&amp;f=image_lowres" width="590px" alt="Image"></img></a></div>
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



