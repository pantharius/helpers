<?php
namespace JDOUnivers\Helpers\MailTemplate;

class MailPasswordRecovery extends MailTemplate{

	public function __construct($pseudo,$passwordKey){
		$this->add_header_preHeader("");
		$this->add_header_logo(self::getWebSiteUrl()."app/templates/default/img/logoCenter.png",200,200,"Logo JDO-Univers");
		$this->add_body_titleTextButton("Mot de passe oublié ?","Hey ".$pseudo.", vous vennez de demander la réinitialisation de votre mot de passe. Si vous êtes à l'origine de cette demande cliquez sur le bouton ci-dessous pour continuer, sinon vous pouvez oublier ce mail.","Récupérer mon mot de passe",self::getWebSiteUrl()."passwordrecover/".$passwordKey);
		$this->add_footer_content('<br><br>JDO-Univers<br><br><a href="mailto:contact@jdo-univers.eu">Nous contactez</a>.<br><a href="'.self::getWebSiteUrl().'">Notre site internet</a><br><br>');
		$this->add_footer_fullPageContent("<p style='margin: 0;'>Ce mail a été envoyé automatiquement, veuillez ne pas y répondre. Si vous rencontrer le moindre soucis, veuillez <a href='mailto:support@jdo-univers.eu'>nous avertir</a>.</p>");
	}

}
