<?php
namespace JDOUnivers\Helpers\MailTemplate;

class MailPasswordReinitalised extends MailTemplate{

	public function __construct($pseudo,$newPassword){
		$this->add_header_preHeader("");
		$this->add_header_logo(self::getWebSiteUrl()."app/templates/default/img/logoCenter.png",200,200,"Logo JDO-Univers");
		$this->add_body_titleTextQuote("Mot de passe réinitialisé !","Hey ".$pseudo.", vous vennez de réinitialiser votre mot de passe. Vous pouvez désormais vous connecter avec le mot de passe ci-dessous. A bientôt !",$newPassword);
		$this->add_footer_content('<br><br>JDO-Univers<br><br><a href="mailto:contact@jdo-univers.eu">Nous contactez</a>.<br><a href="'.self::getWebSiteUrl().'">Notre site internet</a><br><br>');
		$this->add_footer_fullPageContent("<p style='margin: 0;'>Ce mail a été envoyé automatiquement, veuillez ne pas y répondre. Si vous rencontrer le moindre soucis, veuillez <a href='mailto:support@jdo-univers.eu'>nous avertir</a>.</p>");
	}

}
