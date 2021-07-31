<?php
namespace JDOUnivers\Helpers\MailTemplate;

class MailActivation extends MailTemplate{

	public function __construct($pseudo,$activateKey){
		$this->add_header_preHeader("");
		$this->add_header_logo(self::getWebSiteUrl()."app/templates/default/img/logoCenter.png",200,200,"Logo JDO-Univers");
		$this->add_body_titleTextButton("Bienvenue chez nous <span style='font-weight: bold !important;'>".$pseudo."</span> !","Vous venez de vous inscrire sur notre formidable site, cependant il vous reste une dernière étape avant de pouvoir continuer l'aventure. Pour celà, il vous suffit de cliquer (ou d'appuyer avec votre doigt) sur le bouton ci-dessous afin de valider votre email. C'est vraiment simple et pourtant c'est nécessaire !","Activer mon compte",self::getWebSiteUrl()."activate/".$activateKey);
		$this->add_footer_content('<br><br>JDO-Univers<br><br><a href="mailto:contact@jdo-univers.eu">Nous contactez</a>.<br><a href="'.self::getWebSiteUrl().'">Notre site internet</a><br><br>');
		$this->add_footer_fullPageContent("<p style='margin: 0;'>Ce mail a été envoyé automatiquement, veuillez ne pas y répondre. Si vous rencontrer le moindre soucis, veuillez <a href='mailto:support@jdo-univers.eu'>nous avertir</a>.</p>");
	}

}
