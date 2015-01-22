<?xml version="1.0"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:template match="/screen">
		<xsl:apply-templates />
	</xsl:template>


	<xsl:template match="button">
		<button>
			<xsl:apply-templates />
		</button>
	</xsl:template>


	<xsl:template match="answer[@type='radio']">		
		<xsl:for-each select="option">
			<input type="radio">
				<xsl:attribute name="id"><xsl:value-of select="../../@name" />_<xsl:value-of select="@value" /></xsl:attribute>				
				<xsl:attribute name="name"><xsl:value-of select="../../@name" /></xsl:attribute>
				<xsl:attribute name="value"><xsl:value-of select="@value" /></xsl:attribute>				
			</input>
			<label>
				<xsl:attribute name="for"><xsl:value-of select="../../@name" />_<xsl:value-of select="@value" /></xsl:attribute>
				<xsl:apply-templates />
			</label>
		</xsl:for-each>
	</xsl:template>


	<xsl:template match="answer[@type='number']">
		<input type="text">
			<xsl:attribute name="name"><xsl:value-of select="../../@name" /></xsl:attribute>		
		</input>
	</xsl:template>


	<xsl:template match="answer[@type='dropdown']">
		<select>
			<xsl:copy-of select="." />
		</select>
	</xsl:template>



	<xsl:template match="question">
		<xsl:variable name="name" select="@name" />

		<p>
			<span style="display: block;">
				<xsl:apply-templates select="text" />
			</span>
			
			<span style="display: block;">
				<xsl:apply-templates select="answer" />
			</span>			
		</p>
	</xsl:template>




	<!--
		Table of questions
		-->
	<xsl:template match="question_table">			
		<table>
			<tr>
				<th></th>
				
				<xsl:for-each select="answer/option">
					<th><xsl:apply-templates /></th>
				</xsl:for-each>				
			</tr>
			
			<xsl:for-each select="questions/question">
				<xsl:variable name="name" select="@name" />
				<tr>
					<td><xsl:apply-templates select="text" /></td>
					
					<xsl:for-each select="../../answer/option">
						<td>
							<input type="radio">
								<xsl:attribute name="name">
									<xsl:value-of select="$name" />
								</xsl:attribute>
								
								<xsl:attribute name="value">
									<xsl:value-of select="@value" />
								</xsl:attribute>
							</input>
						</td>
					</xsl:for-each>
					
				</tr>
			</xsl:for-each>
			
		</table>
	</xsl:template>  

</xsl:stylesheet>