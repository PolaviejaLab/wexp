<?xml version="1.0"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

	<xsl:template match="/screen">
		<xsl:apply-templates />
	</xsl:template>


	<xsl:template match="h1">
		<div class="page-header">
			<h1><xsl:value-of select="." /></h1>
		</div>
	</xsl:template>

	<xsl:template match="p">
		<div class="container">
			<xsl:value-of select="." />
		</div>
	</xsl:template>

	<xsl:template match="h2">
		<h2><xsl:value-of select="." /></h2>
	</xsl:template>


	<xsl:template match="button">
		<button class="btn btn-primary">
			<xsl:attribute name="ng-click">changeScreen("<xsl:value-of select="@target" />")</xsl:attribute>
			<xsl:apply-templates />
		</button>
	</xsl:template>


	<xsl:template match="answer[@type='radio']">
		<div class="form-control">
			<xsl:for-each select="option">			
				<label class="radio-inline">
					<input type="radio">
						<xsl:attribute name="ng-model">responses.<xsl:value-of select="../../@name" /></xsl:attribute>
						<xsl:attribute name="id"><xsl:value-of select="../../@name" />_<xsl:value-of select="@value" /></xsl:attribute>				
						<xsl:attribute name="name"><xsl:value-of select="../../@name" /></xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="@value" /></xsl:attribute>
					</input>
								
					<xsl:apply-templates />
				</label>		
			</xsl:for-each>
		</div>
	</xsl:template>


	<xsl:template match="answer[@type='number']">
		<input type="text" class="form-control">
			<xsl:attribute name="ng-model">responses.<xsl:value-of select="../@name" /></xsl:attribute>		
			<xsl:attribute name="name"><xsl:value-of select="../@name" /></xsl:attribute>		
		</input>
	</xsl:template>


	<xsl:template match="answer[@type='dropdown']">
		<select class="form-control">
			<xsl:attribute name="ng-model">responses.<xsl:value-of select="../@name" /></xsl:attribute>
			<xsl:copy-of select="." />
		</select>
	</xsl:template>



	<xsl:template match="question">
		<div class="row">
		<div class="col-xs-4">
			<xsl:variable name="name" select="@name" />

			<div class="form-group">
				<label for=""><xsl:apply-templates select="text" /></label>			
				<xsl:apply-templates select="answer" />	
			</div>
		</div>
		</div>
	</xsl:template>




	<!--
		Table of questions
		-->
	<xsl:template match="question_table">			
		<table class="table">
			<tr>
				<th> </th>
				
				<xsl:for-each select="answer/option">
					<th><xsl:apply-templates /></th>
				</xsl:for-each>				
			</tr>
			
			<xsl:for-each select="questions/question">
				<xsl:variable name="name" select="@name" />
				<tr>
					<td><xsl:apply-templates select="text" /></td>
					
					<xsl:for-each select="../../answer/option">
						<td class="col-xs-1">
							<input type="radio" class="form-control">
								<xsl:attribute name="ng-model">responses.<xsl:value-of select="$name" /></xsl:attribute>							
								<xsl:attribute name="name"><xsl:value-of select="$name" /></xsl:attribute>								
								<xsl:attribute name="value"><xsl:value-of select="@value" /></xsl:attribute>
							</input>
						</td>
					</xsl:for-each>
					
				</tr>
			</xsl:for-each>
			
		</table>
	</xsl:template>  

</xsl:stylesheet>