<?php
namespace src\Services;

class HTMLMailService
{
    private $header;
    private $footer;
    private $data;
    private const URI = 'http://localhost/sources/zouglouzik-web/model'; // https://app.zouglouzik.com

    public function __construct($data)
    {
        $this->data = $data;
        $this->setHeader();
        $this->setFooter();
    }

    public function getBodyStyle()
    {
        return 'box-sizing:border-box;
                font-size: 13px !important;
                font-family:"Roboto",sans-serif !important;
                line-height: 26px !important;
                color: #6c6c6c !important';
    }

    public function getShadowMStyle()
    {
        return 'box-shadow: 0 2px 14px 0 rgb(0 0 0 / 8%) !important';
    }

    public function getRoundedMStyle()
    {
        return 'border-radius: 15px !important';
    }

    public function getH2Style()
    {
        return 'font-size: 22px !important;
                line-height: 25px;
                font-weight: 700;
                color: #1f1f1f;
                font-family: "Poppins",sans-serif !important;
                margin-bottom: 5px;
                letter-spacing: -0.4px;
                display: block;
                margin-block-start: 0.83em;
                margin-block-end: 0.83em;
                margin-inline-start: 0px;
                margin-inline-end: 0px';
    }

    public function getPStyle()
    {
        return 'color: #6c6c6c;
                font-size:3vw;
                margin-bottom: 20px;
                padding-bottom: 0px;
                margin-top: 0;
                display: block;
                margin-block-start: 1em;
                margin-block-end: 1em;
                margin-inline-start: 0px;
                margin-inline-end: 0px;
                font-size:14px';
    }

    public function getP1Style()
    {
        return 'padding: 0.25rem !important';
    }

    public function getDlStyle()
    {
        return 'margin-top: 0;
                margin-bottom: 1rem;
                display: block;
                margin-block-start: 1em;
                margin-block-end: 1em;
                margin-inline-start: 0px;
                margin-inline-end: 0px';
    }

    public function getDtStyle()
    {
        return 'float: left;
                font-weight: 700;
                display: block';
    }

    public function getDdStyle()
    {
        return 'margin-bottom: 0.5rem;
                margin-left: 0;
                display: block;
                margin-inline-start: 40px';
    }

    public function setHeader() {
        $this->header = '<html>
                            <head>
                            </head>
                            <body>
                                <main style="' . $this->getBodyStyle() . '">';
                                // <div style="background:rgba(254,99,0,.8);padding:1rem 0rem;border-radius:.15rem;">
                                //     <div style="display:flex;justify-content:center;align-items:center;margin-bottom:.5rem;">
                                //         <img src="' . self::URI . '/assets/images/logo.png" width="100" alt="" style="border-radius:1rem;display:inline-block;margin:0 auto;">
                                //     </div>
                                //     <div style="background:#fff;padding:1rem;border-radius:.25rem;">';
        return $this;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function setFooter()
    {
        // $this->footer =                 '</div>
        //                             </div>'
        $this->footer =         '</main>
                            </body>
                        </html>';
        return $this;
    }
    public function getFooter()
    {
        return $this->footer;
    }

    public function getNewGenerateTokenMessage()
    {
        $user = null;
        $message = '';
        $token = '';
        $name = '';
        $route = $this->data['route'];
        if(isset($this->data['user'])) {
            $user = $this->data['user'];
            $token = $user->getToken()->getCode();
            $name = $user->getFullName();
        } elseif(isset($this->data['admin'])) {
            $user = $this->data['admin'];
            $token = $user->getToken();
            $name = 'Admin';
        }
        if($user){
            $message =  '<p style="' . $this->getPStyle() . '">Un nouveau Token vous a été généré ' . $name . '.</p>'.
                        '<p style="' . $this->getPStyle() . '">veuillez cliquer sur le lien<p>'.
                        '<p style="' . $this->getPStyle() . ';text-align:center;margin:.25rem 0;"><a href="' . self::URI . '/' . $route . '?key=account&amp;user=' . $user->getEmail() . '&amp;token=' . $token . '" style="padding:1rem;background:#f2f2f2;color:#777777;font-weight:300;">activer mon compte</a></p>'.
                        '<p style="' . $this->getPStyle() . '">pour activer votre compte et profiter aisément des fonctionalités à vous offrir.</p>'.
                        '<p style="' . $this->getPStyle() . '">Ce lien expire dans 72h.</p>';
        }
        return $this->getHeader() . $message . $this->getFooter();
    }

    public function getResetPasswordMessage()
    {
        $user = null;
        $message = '';
        $token = '';
        $route = $this->data['route'];
        if(isset($this->data['user'])) {
            $user = $this->data['user'];
            $token = $user->getToken()->getCode();
        } elseif(isset($this->data['admin'])) {
            $user = $this->data['admin'];
            $token = $user->getToken();
        }
        if($user) {
            $message = '<p style="' . $this->getPStyle() . '">veuillez cliquer sur le lien<p>'.
                        '<p style="' . $this->getPStyle() . ';text-align:center;margin:.25rem 0;"><a href="' . self::URI . '/' . $route . '?key=reset-password&amp;user=' . $user->getEmail() . '&amp;token=' . $token . '" style="padding:1rem;background:#f2f2f2;color:#777777;font-weight:300;">Réinitialisé mon mot de passe</a></p>'.
                        '<p style="' . $this->getPStyle() . '">pour procéder à la réinitialisation de votre mot de passe.</p>'.
                        '<p style="' . $this->getPStyle() . '">Ce lien expire dans 72h.</p>';
        }
        return $this->getHeader() . $message . $this->getFooter();
    }

    public function getResetPasswordForgetMessage()
    {
        $psw = $this->data['psw'];
        $message = '<p>Votre nouveau mot de passe: ' . $psw . '<p>'.
                    '<p>Vous pouvez le modifier une fois connecter à votre compte.</p>';

        return $this->getHeader() . $message . $this->getFooter();
    }

    public function getContactMail()
    {
        $text = $this->data['text'];
        $message = '<p style="' . $this->getPStyle() . '">' . nl2br($text) . '</p>';
        return $this->getHeader() . $message . $this->getFooter();
    }

    /**
     * est envoyé aux utilisateurs à la création de compte
     */
    public function getSignUpMessage()
    {
        $user = $this->data['user'];
        $message =  '<p style="' . $this->getPStyle() . '">Félicitation ' . $user->getFullName() . ', <br>Votre compte a été créée avec succès.</p>';
        return $this->getHeader() . $message . $this->getFooter();
    }

    /**
     * est envoyé (à l'administrateur) dès qu'une nouvelle prestation est créée
     */
    public function getNewPrestationMessage()
    {
        $prestation = $this->data['prestation'];
        $message = '<p>Le prestataire <span style="font-weight:bold;">' . $prestation->getUser()->getCode() . '</span> vient d\'enregistrer une nouvelle prestation (<span style="font-weight:bold;">' . $prestation->getCode() . '</span>) en attente de validation.</p>';
        return $this->getHeader() . $message . $this->getFooter();        
    }

    /**
     * est envoyé (au prestataire) dès que le status de la prestation
     */
    public function getEditStatusPrestationMessage()
    {
        $message = '';
        $prestation = $this->data['prestation'];
        if($prestation->getStatus() == 1) {
            $message = '<p>Votre récente prestation enregistrée sous le code <span style="font-weight:bold;">' . $prestation->getCode() . '</span> vient d\'être validée par l\'administrateur. Vous pouvez désormais faire un suivi de votre prestation.</p>';
        } elseif($prestation->getStatus() == -1) {
            $message = '<p>Votre récente prestation enregistrée sous le code <span style="font-weight:bold;">' . $prestation->getCode() . '</span> vient d\'être refusée. Pour plus d\'informations, Veuillez contacter l\'administrateur de <span style="font-weight:bold;color:#ff2222;">PRO</span><span style="font-weight:bold;color:#000000;">mit</span>.</p>';
        }
        return $this->getHeader() . $message . $this->getFooter();
    }

    /**
     * est envoyé (au prestataire) dès qu'une nouvelle demande de rdv est émise
     */
    public function getNewRdvMessage()
    {
        $rdv = $this->data['rdv'];
        $message = '<p style="' . $this->getPStyle() . '">Une nouvelle demande de rendez-vous <code>Code</code> <span style="font-weight:bold;">(' . $rdv->getCode() . ')</span> vous a été envoyée.</p>';
        return $this->getHeader() . $message . $this->getFooter();        
    }

    /**
     * est envoyé (au demandeur) de rdv dès que le status de la demande change
     */
    public function getEditStatusRdvMessage()
    {
        $rdv = $this->data['rdv'];
        $message = '';
        if($rdv->getStatus() == 1) {
            $message .= '<p style="' . $this->getPStyle() . '">Votre demande de rendez-vous <code>Code</code> <span style="font-weight:bold;">(' . $rdv->getCode() . ')</span> a été acceptée.</p>';
        } elseif($rdv->getStatus() == -1) {
            $message .= '<p style="' . $this->getPStyle() . '">Votre demande de rendez-vous <code>Code</code> <span style="font-weight:bold;">(' . $rdv->getCode() . ')</span> a été refusée. Veuillez contacter le prestataire du service désiré pour de ample explication.</p>';
        }
        return $this->getHeader() . $message . $this->getFooter();        
    }

    // public function getVenteTicketMail()
    // {
    //     $vente = $this->data['vente'];
    //     $mode = $this->data['mode'];
    //     $ticket = $vente->getTicket();
    //     $evenment = $ticket->getEvenement();
    //     $message = '<h3>Evenement: ' . $evenment->getTitre() . '</h3>'.
    //                 '<p style="' . $this->getPStyle() . '">Lieu: ' . $evenment->getLieu() . '</p>'.
    //                 '<p style="' . $this->getPStyle() . '">Date: ' . $evenment->getDat() . '</p>'.
    //                 '<p style="' . $this->getPStyle() . ';margin-bottom:2rem;">Heure: ' . (new \DateTime($evenment->getHeure()))->format('H\h:i') . '</p>'.
    //                 '<h4>Ticket: ' . $vente->getNumTicket() . '</h4>'.
    //                 '<p style="' . $this->getPStyle() . ';margin-bottom:2rem;">Prix: ' . ($mode == 'fedapay' ? $ticket->getPrixCfa() . ' XOF' : $ticket->getPrixEuro() . ' €') . '</p>'.
    //                 '<p style="' . $this->getPStyle() . ';text-align:center;">L\'équipe de ZouglouZik vous remercie pour votre aimable attachement.</p>'.
    //                 '<p style="color:#cccccc;text-align:center;display:flex;justify-content:center;align-items:center;width:100%;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dot" viewBox="0 0 16 16">
    //                         <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
    //                     </svg><span style="color:#fe6300;">Zouglou</span><span style="color:#25A56A;">Zik</span>, votre plateforme de musique 100% Zougou.<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dot" viewBox="0 0 16 16">
    //                         <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
    //                     </svg>
    //                 </p>';
    //     return $this->getHeader() . $message . $this->getFooter();
    // }
}