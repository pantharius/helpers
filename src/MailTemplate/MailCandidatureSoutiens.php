<?php
namespace JDOUnivers\Helpers\MailTemplate;

class MailCandidatureSoutiens extends MailTemplate{

	public function __construct($lastname,$firstname,$age,$pseudo,$email,$EtuProf,$attente,$competences,$connu,$micro,$connexion){
		$this->add_header_preHeader("");
		$this->add_header_logo(self::getWebSiteUrl()."app/templates/default/img/logoCenter.png",200,200,"Logo JDO-Univers");
		$this->add_body_titleText("Candidature de <span style='font-weight: bold !important;'>".$pseudo."</span>","Voici ses réponses aux diverses questions qui lui ont été posé :");
		$this->add_body_text("<span style='font-weight: bold !important;'>Quel est votre nom ?</span> ".$lastname);
		$this->add_body_text("<span style='font-weight: bold !important;'>Quel est votre prénom ?</span> ".$firstname);
		$this->add_body_text("<span style='font-weight: bold !important;'>Quel âge avez-vous ?</span> ".$age." ans");
		$this->add_body_text("<span style='font-weight: bold !important;'>Quel est votre pseudo ?</span> ".$pseudo);
		$this->add_body_text("<span style='font-weight: bold !important;'>Quel est votre adresse e-mail ?</span> ".$email);
		$this->add_body_text("<span style='font-weight: bold !important;'>Quel est votre niveau d'étude et votre profession ?</span><br />".$EtuProf);
		$this->add_body_text("<span style='font-weight: bold !important;'>Qu'attendez-vous de l'initiative soutiens ?</span><br />".$attente);
		$this->add_body_text("<span style='font-weight: bold !important;'>Quels sont vos compétences ?</span><br />".$competences);
		$this->add_body_text("<span style='font-weight: bold !important;'>Comment nous avez vous connus ?</span><br />".$connu);
		$this->add_body_text("<span style='font-weight: bold !important;'>Est-ce que vous avez un micro ?</span> ".$micro);
		$this->add_body_text("<span style='font-weight: bold !important;'>Quel est le débit de votre connexion ?</span><br />".$connexion);
		$this->add_footer_content('<br><br>JDO-Univers<br><br><a href="mailto:contact@jdo-univers.eu">Nous contactez</a>.<br><a href="'.self::getWebSiteUrl().'">Notre site internet</a><br><br>');
		$this->add_footer_fullPageContent("<p style='margin: 0;'>Ce mail a été envoyé automatiquement, veuillez ne pas y répondre. Si vous rencontrer le moindre soucis, veuillez <a href='mailto:support@jdo-univers.eu'>nous avertir</a>.</p>");
	}

}
