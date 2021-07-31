<?php
namespace JDOUnivers\Helpers\MailTemplate;

abstract class MailTemplate{

	private $header = "";
	private $body = "";
	private $footer = "";

	protected static function getWebSiteUrl(){
		$parsed_url = parse_url($_SERVER['HTTP_ORIGIN']);
		return $parsed_url["scheme"] . "://" . $parsed_url["host"] . "/";
	}

	public function get_html(){
		$returnContent = file_get_contents("../vendor/jdo-univers/helpers/src/MailTemplate/header.html");
		$returnContent .= $this->get_content();
		$returnContent .= file_get_contents("../vendor/jdo-univers/helpers/src/MailTemplate/footer.html");
		return $returnContent;
	}

	/* HEADER FUNCTIONS */

	protected function add_header_preHeader(string $text){
		if(!empty($header))
			return false;
		$this->header .= '<!-- Visually Hidden Preheader Text : BEGIN -->
			<div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
				'.$text.'
			</div>
			<!-- Visually Hidden Preheader Text : END -->';
		return true;
	}

	protected function add_header_logo($imageurl, $width, $height, $altText, $backgroundColor='#222222'){
		$this->header .= '<!-- Email Header : BEGIN -->
		<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="600" style="margin: auto;" class="email-container">
				<tr>
						<td style="padding: 20px 0; text-align: center">
								<img src="'.$imageurl.'" width="'.$width.'" height="'.$height.'" alt="'.$altText.'" border="0" style="height: auto; background: '.$backgroundColor.'; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
						</td>
				</tr>
		</table>
		<!-- Email Header : END -->';
	}

	/* FOOTER FUNCTIONS */

	protected function add_footer_content($content, $textColor='#888888'){
		$this->footer .= '<!-- Email Footer : BEGIN -->
		<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px; font-family: sans-serif; color: '.$textColor.'; font-size: 12px; line-height: 140%;">
			<tr>
				<td style="padding: 40px 10px; width: 100%; font-family: sans-serif; font-size: 12px; line-height: 140%; text-align: center; color: '.$textColor.';"
					class="x-gmail-data-detectors">
					'.$content.'
				</td>
			</tr>
		</table>
		<!-- Email Footer : END -->';
	}

	protected function add_footer_fullPageContent($content, $backgroundColor='#709f2b', $textColor='#ffffff'){
		$this->footer .= '<!-- Full Bleed Background Section : BEGIN -->
		<table role="presentation" bgcolor="'.$backgroundColor.'" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
			<tr>
				<td valign="top" align="center">
					<div style="max-width: 600px; margin: auto;" class="email-container">
						<!--[if mso]>
							<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" align="center">
							<tr>
							<td>
							<![endif]-->
						<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
							<tr>
								<td style="padding: 40px; text-align: center; font-family: sans-serif; font-size: 15px; line-height: 140%; color: '.$textColor.';">
									'.$content.'
								</td>
							</tr>
						</table>
						<!--[if mso]>
							</td>
							</tr>
							</table>
							<![endif]-->
					</div>
				</td>
			</tr>
		</table>';
	}

	/* BODY FUNCTIONS */

	protected function add_body_heroImage(string $imageurl, int $width, int $height, string $altText){
		$this->body .= '<!-- Hero Image, Flush : BEGIN -->
    <tr>
        <td bgcolor="#ffffff" align="center">
            <img src="'.$imageurl.'" width="'.$width.'" height="'.$height.'" alt="'.$altText.'" border="0" align="center" style="width: 100%; max-width: 600px; height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; margin: auto;" class="g-img">
        </td>
    </tr>
    <!-- Hero Image, Flush : END -->';
	}

	protected function add_body_titleText(string $title, string $text){
		$this->body .= '<!-- 1 Column Text : BEGIN -->
    <tr>
        <td bgcolor="#ffffff" style="padding: 40px 40px 20px; text-align: center;">
            <h1 style="margin: 0; font-family: sans-serif; font-size: 24px; line-height: 125%; color: #333333; font-weight: normal;">'.$title.'</h1>
        </td>
    </tr>
    <tr>
        <td bgcolor="#ffffff" style="padding: 0 40px 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; text-align: center;">
            <p style="margin: 0;">'.$text.'</p>
        </td>
    </tr>
    <!-- 1 Column Text : END -->';
	}

	protected function add_body_titleTextButton(string $title, string $text, string $buttonText, string $buttonUrl){
		$this->body .= '<!-- 1 Column Text + Button : BEGIN -->
    <tr>
        <td bgcolor="#ffffff" style="padding: 40px 40px 20px; text-align: center;">
            <h1 style="margin: 0; font-family: sans-serif; font-size: 24px; line-height: 125%; color: #333333; font-weight: normal;">'.$title.'</h1>
        </td>
    </tr>
    <tr>
        <td bgcolor="#ffffff" style="padding: 0 40px 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; text-align: center;">
            <p style="margin: 0;">'.$text.'</p>
        </td>
    </tr>
    <tr>
        <td bgcolor="#ffffff" style="padding: 0 40px 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
            <!-- Button : BEGIN -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto">
                <tr>
                    <td style="border-radius: 3px; background: #222222; text-align: center;" class="button-td">
                        <a href="'.$buttonUrl.'" style="background: #222222; border: 15px solid #222222; font-family: sans-serif; font-size: 13px; line-height: 110%; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a">
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <span style="color:#ffffff;">'.$buttonText.'</span>&nbsp;&nbsp;&nbsp;&nbsp;
                        </a>
                    </td>
                </tr>
            </table>
            <!-- Button : END -->
        </td>
    </tr>
    <!-- 1 Column Text + Button : END -->';
	}

	protected function add_body_titleTextQuote(string $title, string $text, string $textToQuote){
		$this->body .= '<!-- 1 Column Text + Button : BEGIN -->
    <tr>
        <td bgcolor="#ffffff" style="padding: 40px 40px 20px; text-align: center;">
            <h1 style="margin: 0; font-family: sans-serif; font-size: 24px; line-height: 125%; color: #333333; font-weight: normal;">'.$title.'</h1>
        </td>
    </tr>
    <tr>
        <td bgcolor="#ffffff" style="padding: 0 40px 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; text-align: center;">
            <p style="margin: 0;">'.$text.'</p>
        </td>
    </tr>
    <tr>
		<td bgcolor="#ffffff" style="padding: 0 40px 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; text-align: center;">
            <!-- Code : BEGIN -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto">
                <tr>
					<td bgcolor="#151515" width="550" valign="middle" style="text-align: center; padding: 10px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #ffffff; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;">
						<p style="margin: 0;">'.$textToQuote.'</p>
					</td>
                </tr>
            </table>
            <!-- Code : END -->
        </td>
    </tr>
    <!-- 1 Column Text + Button : END -->';
	}

	protected function add_body_textWithBackgroundImage($text, $imageurl, $height = "175"){
		$this->body .= '<!-- Text With Background Image : BEGIN -->
    <tr>
        <!-- Bulletproof Background Images c/o https://backgrounds.cm -->
        <td background="'.$imageurl.'" bgcolor="#222222" valign="middle" style="text-align: center; background-position: center center !important; background-size: cover !important;">
            <!--[if gte mso 9]>
                <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:600px;height:'.$height.'px; background-position: center center !important;">
                <v:fill type="tile" src="'.$imageurl.'" color="#222222" />
                <v:textbox inset="0,0,0,0">
                <![endif]-->
            <div>
                <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td valign="middle" style="text-align: center; padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #ffffff;">
                            <p style="margin: 0;">'.$text.'</p>
                        </td>
                    </tr>
                </table>
            </div>
            <!--[if gte mso 9]>
                </v:textbox>
                </v:rect>
                <![endif]-->
        </td>
    </tr>
    <!-- Background Image with Text : END -->';
	}

	protected function add_body_text($text, $backgroundColor="#ffffff", $textColor="#555555"){
		$this->body .= '<!-- 1 Column Text : BEGIN -->
    <tr>
        <td bgcolor="'.$backgroundColor.'">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: '.$textColor.';">
                        '.$text.'
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- 1 Column Text : END -->';
	}

	protected function add_body_spacer(int $height){
		$this->body .= '<!-- Clear Spacer : BEGIN -->
    <tr>
        <td aria-hidden="true" height="'.$height.'" style="font-size: 0; line-height: 0;">
            &nbsp;
        </td>
    </tr>
    <!-- Clear Spacer : END -->';
	}

	/* PRIVATE FUNCTIONS */

	private function get_content(): string{
		$content = $this->header;
		$content .= '<!-- Email Body : BEGIN --><table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="600" style="margin: auto;" class="email-container">';
		$content .= $this->body;
		$content .= '</table><!-- Email Body : END -->';
		$content .= $this->footer;
		return $content;
	}

}