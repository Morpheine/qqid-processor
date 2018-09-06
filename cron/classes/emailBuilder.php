<?php
/**
 * Created by PhpStorm.
 * User: brent
 * Date: 2015-03-26
 * Time: 2:04 PM
 */
class emailBuilder
{
    private $mailError;
    private $mailSend;
    private $qqidBody;
    private $toAddress;
    private $fromAddress;
    private $bccAddress;
    private $body;
    private $altBody;
    private $subject;


    /**
     * @param $type
     */
    public function __construct()
    {
        //phpmailer API dependency
        include "./phpmailer/vendor/phpmailer/phpmailer/PHPMailerAutoload.php";
        $this->setAltBody('Please view this email from an HTML enabled mail client.');
    }

    /**
     * @return bool
     * @throws Exception
     * @throws phpmailerException
     */
    public function sendEmail()
    {
        $mail = new PHPMailer;
        //$mail->SMTPDebug = 3;                                 // Enable verbose debug output

        // Set mailer to use SMTP
        $mail->isSMTP();
        // Specify main and backup SMTP servers
        $mail->Host = 'appsmtp.utoronto.ca';
        $mail->Port = 25;
        $mail->From = $this->getFromAddress();
        $mail->FromName = 'University of Toronto School of Continuing Studies';
        // Add a recipient
        $mail->addAddress($this->getToAddress());
        $mail->addReplyTo('learn@utoronto.ca', 'University of Toronto School of Continuing Studies');
        $mail->addBCC($this->getBccAddress());
        $mail->WordWrap = 50;                                   // Set word wrap to 50 characters
        $mail->isHTML(true);                                    // Set email format to HTML
        $mail->Subject = $this->getSubject();     // Email subject
        // Email Body
        $mail->Body = $this->getBody();
        $mail->AltBody = $this->getAltBody();

        if($mail->send()) {
            echo "Message Sent to: ".$this->getToAddress()."<br/>";
            return true;
        } else{
            echo "Message Could Not Be Sent!";
            $this->setMailError($mail->ErrorInfo);
            return false;
        }
    }

    /**
     * @param $details
     */
    public function buildQqidEmail($details){
        $fname = $details["fname"];
        $lname = $details["lname"];
        $email = $details["email"];
        $courseCode = $details["course_code"];
        $sectionCode = $details["section_code"];
        $courseTitle = $details["title"];
        $qqid = $details["qqid"];
        $password = $details["password"];
        $expiryDate = date('F d, Y', strtotime($details["expiry_date"]));


        $this->setToAddress($email, $fname . ' ' . $lname);
        $this->setFromAddress('elearning@utoronto.ca');
        $this->setBccAddress('elearning@utoronto.ca');
        $this->setAltBody('Please view this email from an HTML enabled mail client.');
        $this->setSubject('Your Course Access Information for Blackboard');

        $this->setBody("
                    <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                    <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head>
                        <meta http-equiv='X-UA-Compatible' content='IE=9; IE=8; IE=7; IE=EDGE' />
                        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                        <meta property='og:title' content='*|MC:SUBJECT|*'/>
                        <meta property='fb:page_id' content='6169515998' />
                        <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1' />
                        <meta name='format-detection' content='telephone=no' /> <!-- prevents iOS from setting links as a standard blue link -->
                        <style type='text/css'>
                            /* ----- RESETS ----- */
                            table{
                                border-collapse:collapse;
                            }
                            a{
                                word-wrap:break-word;
                            }
                            body,table,td,p,a,li,blockquote{
                                -ms-text-size-adjust:100%;
                                -webkit-text-size-adjust:100%;
                            }
                            .ExternalClass{
                                width:100%;
                            }
                            .ExternalClass center{
                                padding-bottom:20px;
                            }
                            .ExternalClass *{
                                line-height:120%;
                            }
                            .ExternalClass ul{
                                padding-top:12px;
                            }
                            .ExternalClass h1,.ExternalClass h2,.ExternalClass h3,.ExternalClass h4,.ExternalClass h5,.ExternalClass h6{
                                font-family: Helvetica,Arial,sans-serif;
                                margin-bottom:0px !important;
                                padding-bottom:0px !important;
                            }
                            #outlook a{
                                padding:0;
                            }
                            table,td{
                                mso-table-lspace:0pt;
                                mso-table-rspace:0pt;
                            }
                            img{
                                -ms-interpolation-mode:bicubic;
                            }
                            p{
                                margin-top:0;
                                margin-right:0;
                                margin-bottom:20px;
                                margin-left:0;
                            }

                            /* ----- CALL TO ACTIONS ----- */
                            .ExternalClass div.cta {
                                padding-bottom:15px;
                            }

                            /* ----- MAIN CONTENT ----- */
                            .im {
                                color:#333333 !important;
                                font-size:15px !important;
                            }

                            /* ----- MOBILE STYLES ----- */
                            @media only screen and (max-width: 480px){
                                body:not(.IE_M9) #emailWrapper, body:not(.IE_M9) table, body:not(.IE_M9) thead, body:not(.IE_M9) tbody, body:not(.IE_M9) tfoot, body:not(.IE_M9) tr, body:not(.IE_M9) td {
                                    display:block !important;
                                    height: auto !important;
                                    min-width:0 !important;
                                    padding-right:2% !important;
                                    padding-left:2% !important;
                                    width:95% !important;
                                }
                                body:not(.IE_M9) #headerWrapper img, body:not(.IE_M9) .headerlogo img, body:not(.IE_M9) .sidebarQuote img, body:not(.IE_M9) .col img, body:not(.IE_M9) .col a img, body:not(.IE_M9) .mainWrapper img {
                                    height:auto !important;
                                    margin: 0 auto;
                                    max-width:100% !important;
                                    width:auto !important;
                                }
                                body:not(.IE_M9) #headerWrapper img {
                                    display:inline !important;
                                    float: none !important;
                                }
                                body:not(.IE_M9) div.social a img {
                                    height:auto;
                                    max-width:15%;
                                }
                                body:not(.IE_M9) .mainWrapper img {
                                    margin-bottom:15px !important;
                                }
                                body:not(.IE_M9) .quote img.clear {
                                    height:auto;
                                    width:33% !important;
                                }
                                body:not(.IE_M9) .col img {
                                    display:block !important;
                                    margin:0 auto 15px auto !important;
                                }
                                body:not(.IE_M9) #headerWrapper td, body:not(.IE_M9) #headerRight.social {
                                    text-align:center !important;
                                }
                                body:not(.IE_M9) .mainWrapper {
                                    font-size:15px !important;
                                }
                                body:not(.IE_M9) tr {
                                    padding-bottom:4% !important;
                                }
                                body:not(.IE_M9) #consider_UTM {
                                    border-top:0px solid #ffffff !important;
                                    margin-bottom:15px;
                                }
                                body:not(.IE_M9) #headerWrapper, body:not(.IE_M9) #campusLinks {
                                    border-top:1px solid #c8c8c8 !important;
                                    border-bottom:1px solid #c8c8c8 !important;
                                    padding-bottom:10px !important;
                                }
                                body:not(.IE_M9) #headerWrapper td, body:not(.IE_M9) #campusLinks td, body:not(.IE_M9) #footerWrapper td  {
                                    border-top:0px solid #ffffff !important;
                                    border-bottom:0px solid #ffffff !important;
                                    text-align: left !important;
                                }
                                body:not(.IE_M9) a.cta {
                                    display:block !important;
                                    text-align:center !important;
                                }
                                body:not(.IE_M9) .mainWrapper a.cta {
                                    border-bottom:15px solid #065797 !important;
                                    border-top:15px solid #065797 !important;
                                    font-size:18px !important;
                                    font-weight:normal !important;
                                }
                                body:not(.IE_M9) .sidebarContent a.cta {
                                    border-bottom:15px solid #ffffff !important;
                                    border-top:15px solid #ffffff !important;
                                    font-size:15px !important;
                                }
                                body:not(.IE_M9) .sidebarContent, .sidebarQuote {
                                    margin-top:5% !important;
                                    padding:5% !important;
                                }
                                body:not(.IE_M9) .sidebarQuote {
                                    padding:0 !important;
                                }
                                body:not(.IE_M9) .smallList tr {
                                    padding-bottom:0 !important;
                                }
                                body:not(.IE_M9) .smallList td {
                                    border-bottom:0px solid #ffffff !important;
                                }
                                body:not(.IE_M9) .smallList .smallListLast {
                                    border-bottom:1px solid #c8c8c8 !important;
                                }
                                body:not(.IE_M9) .smallListEmpty {
                                    display:none !important;
                                }
                            }
                        </style>
                        <!--[if gte mso 9]>
                        <style type='text/css'>
                            .outlookhide {
                                display:none !important;
                            }
                            td {
                                line-height:100% !important;
                            }
                            .mainWrapper a, td a, .sidebarContent a, #footerWrapper a {
                                text-decoration:underline !important;
                            }
                            #campusLinks a {
                                text-decoration:none !important;
                            }
                            }
                            div.courselist a, div.courselistlast a, a.cta {
                                text-decoration:none !important;
                            }
                            div.courselist, div.courselistlast {
                                margin-bottom:8px;
                            }
                            #headerWrapper td {
                                border-top:1px solid #c8c8c8;
                                border-bottom:1px solid #c8c8c8;
                            }
                            #footerSpacer {
                                border-top:1px solid #c8c8c8;
                                font-size:10px;
                            }
                            #msosidecta, #considerutmcta {
                                border-top:4px solid #ffffff !important;
                            }
                            #considerutmcta {
                                border-color:#646665 !important;
                            }
                            #campusLinks td {
                                border-top:1px solid #c8c8c8;
                                width: 33% !important;
                            }
                            h1.num {
                                font-size:22px !important;
                            }
                        </style>
                        <![endif]-->
                        </head>
                        <body style='background-color:#ffffff;height:100%;margin:0;padding:0;padding-top:20px;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;width:100%;'>
                        <center>
                            <table id='emailWrapper' align='center' border='0' cellpadding='0' cellspacing='0' width='600' style='border:0;border-collapse:collapse;font-family:Arial,sans-serif;font-size:12px;max-width:600px;min-width:600px;table-layout:fixed;width:600px'>
                                <thead>
                                <tr id='headerWrapper' mc:repeatable='header_logos' mc:variant='SCS logo only'>
                                    <td id='headerLeft' align='left' colspan='6' valign='top' width='50%' style='border-bottom:1px solid #c8c8c8;border-top:1px solid #c8c8c8;padding-top:10px;padding-right:0px;padding-bottom:10px;padding-left:10px;' mc:edit='scs_logo'>
                                        <a class='headerlogo' href='http://learn.utoronto.ca' target='_blank' style='border-bottom:none !important;'><img id='SCS_logo' src='http://gallery.mailchimp.com/916dd59b254dcbbdc71183172/images/52af7921-b8ea-4d61-b1de-e09c43b35117.gif' alt='U of T SCS' border='0' height='56' width='250' style='border:0;display:block;color:#002A5C;font-family:Arial,sans-serif;font-size:12px;height:auto;margin:0;max-width:290px;outline:none;padding:0;text-decoration:none;width:auto;' /></a>
                                    </td>
                                    <td id='headerRight' class='social' align='right' colspan='6' valign='middle' width='50%' style='border-bottom:1px solid #c8c8c8;border-top:1px solid #c8c8c8;padding-right:10px;vertical-align:middle;'>
                                        <div class='social' mc:edit='social' mc:hideable>
                                            <a href='https://twitter.com/UofTLearnMore' target='_blank' style='border-bottom:none !important;'><img class='social' src='http://gallery.mailchimp.com/916dd59b254dcbbdc71183172/images/e17cca18-5a17-4083-850c-e3dc3d2ed00e.gif' border='0' height='27' width='28' style='border:0;color:#002A5C;font-family:Arial,sans-serif;font-size:12px;outline:0;padding:0;text-decoration:none;' /></a><!--[if mso]>&nbsp;<![endif]-->
                                            <a href='http://www.linkedin.com/company/university-of-toronto-school-of-continuing-studies' target='_blank' style='border-bottom:none !important;'><img class='social' src='http://gallery.mailchimp.com/916dd59b254dcbbdc71183172/images/9c9ef7ed-3193-4cad-ad4b-7246a9909a1a.gif' border='0' height='27' width='28' style='border:0;color:#002A5C;font-family:Arial,sans-serif;font-size:12px;outline:0;padding:0;text-decoration:none;' /></a><!--[if mso]>&nbsp;<![endif]-->
                                            <a href='https://www.facebook.com/pages/University-of-Toronto-School-of-Continuing-Studies/483289075072533' target='_blank' style='border-bottom:none !important;'><img class='social' src='https://gallery.mailchimp.com/916dd59b254dcbbdc71183172/images/6bca46bb-54fe-4a78-8688-766ecfef6d04.gif' border='0' height='27' width='28' style='border:0;color:#002A5C;font-family:Arial,sans-serif;font-size:12px;outline:0;padding:0;text-decoration:none;' /></a>
                                        </div>
                                    </td>
                                </tr>
                                <tr id='maincontent'><td colspan='12' width='100%' style='font-size:10px;'>&nbsp;</td></tr>
                                </thead>
                                <tbody mc:repeatable='main_row' mc:variant='no sidebar'>
                                <tr>
                                    <td class='mainWrapper' align='left' colspan='12' valign='top' width='100%' style='color:#333333;font-size:15px;line-height:125%;padding-top:15px;padding-right:10px;padding-bottom:30px;padding-left:10px;' mc:edit='body'>
                                        Dear <b>$fname $lname</b>,
                                        <br /><br/>
                                        You&#8217;re only a few steps away from accessing your course materials for <b>SCS_".$courseCode."_".$sectionCode." ".$courseTitle."</b> on Blackboard. Follow the steps below to login:<br/><br />
                                        <table border='0' cellpadding='0' cellspacing='0' width='600' style='border:0;border-collapse:collapse;color:#333333;font-family:Arial,sans-serif;font-size:15px;'>
                                            <tr>
                                                <td align='center' valign='top' width='10%'><h1 class='num' style='color: #002A5C;display: block;font-weight: normal;letter-spacing: -1px;margin: 0;padding-bottom: 0px;padding-top: 0px;font-size: 27px;line-height: 30px;'>&#10102;</h1></td>
                                                <td valign='top' width='90%'><h1 style='color: #002A5C;display: block;font-weight: bold;letter-spacing: -1px;margin: 0;padding-bottom: 0px;padding-top: 0px;font-size: 19px;line-height: 30px;'>Go to the University of Toronto Learning Portal webpage</h1></td>
                                            </tr>
                                            <tr>
                                                <td valign='top' width='10%' style='padding-bottom:25px;'>&nbsp;</td>
                                                <td valign='top' width='90%' style='padding-bottom:25px;'>Access the Learning Portal at <a href='https://portal.utoronto.ca' style='border-bottom:1px dotted #065797;color:#065797;text-decoration:none;'>https://portal.utoronto.ca</a>.</td>
                                            </tr>

                                            <tr>
                                                <td align='center' valign='top' width='10%'><h1 class='num' style='color: #002A5C;display: block;font-weight: normal;letter-spacing: -1px;margin: 0;padding-bottom: 0px;padding-top: 0px;font-size: 27px;line-height: 30px;'>&#10103;</h1></td>
                                                <td valign='top' width='90%'><h1 style='color: #002A5C;display: block;font-weight: bold;letter-spacing: -1px;margin: 0;padding-bottom: 0px;padding-top: 0px;font-size: 19px;line-height: 30px;'>Click on the &#34;log-in to the portal&#34; button</h1></td>
                                            </tr>
                                            <tr>
                                                <td valign='top' width='10%' style='padding-bottom:25px;'>&nbsp;</td>
                                                <td valign='top' width='90%' style='padding-bottom:25px;'>Click on the &#34;log-in to the portal&#34; button on the upper left hand side of the screen and enter your UTORid and password indicated below in this email. These login credentials are fixed and you will not be able to change this information.
                                                    <br /><br />
                                                    <strong>UTORid / JOINid:</strong> $qqid<br />
                                                    <strong>Password:</strong> $password<br />
                                                    <span style='color:#a8a8a8;font-style:italic'>Note: your username and password will expire on $expiryDate.</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td align='center' valign='top' width='10%'><h1 class='num' style='color: #002A5C;display: block;font-weight: normal;letter-spacing: -1px;margin: 0;padding-bottom: 0px;padding-top: 0px;font-size: 27px;line-height: 30px;'>&#10104;</h1></td>
                                                <td valign='top' width='90%'><h1 style='color: #002A5C;display: block;font-weight: bold;letter-spacing: -1px;margin: 0;padding-bottom: 0px;padding-top: 0px;font-size: 19px;line-height: 30px;'>View your course materials under &#34;My Courses&#34;</h1></td>
                                            </tr>
                                            <tr>
                                                <td valign='top' width='10%'>&nbsp;</td>
                                                <td valign='top' width='90%'>On the upper right-hand side of the screen, you will see &#34;My Courses&#34; which will list all of your active courses using Blackboard. Click on any of these courses to access your course materials. Happy learning!</td>
                                            </tr>
                                        </table>
                                        <br /><br />
                                        If you have any questions or require assistance logging into Blackboard, please contact our Student Services at: <a href='mailto:learn@utoronto.ca' style='border-bottom:1px dotted #065797;color:#065797;text-decoration:none;'>learn@utoronto.ca</a> or call 416-978-2400 and select option 2.
                                    </td>
                                </tr>
                                </tbody>
                                <tbody>
                                <!--[if gte mso 9]><tr><td colspan='12' width='100%'>&nbsp;</td></tr><![endif]-->
                                <tr id='campusLinks' mc:hideable>
                                    <td align='left' colspan='4' width='33%' style='border-collapse: collapse;color: #333333;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: Helvetica,Arial,sans-serif;border-top: 1px solid #c8c8c8;font-size: 15px;font-weight: bold;padding: 5px;'>
                                        <span style='font-weight:bold;' mc:edit='UTMlink'>U OF T <a href='http://learn.utoronto.ca/utm' target='_blank' style='border:none;color:#065797;font-weight:bold;text-decoration:none;'>MISSISSAUGA</a></span>
                                    </td>
                                    <td align='center' colspan='4' width='33%' style='border-collapse: collapse;color: #333333;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: Helvetica,Arial,sans-serif;border-top: 1px solid #c8c8c8;font-size: 15px;font-weight: bold;padding: 5px;'>
                                        <span style='font-weight:bold;' mc:edit='STGlink'>U OF T <a href='http://learn.utoronto.ca/' target='_blank' style='border:none;color:#065797;font-weight:bold;text-decoration:none;'>ST.GEORGE</a></span>
                                    </td>
                                    <td align='right' colspan='4' width='33%' style='border-collapse: collapse;color: #333333;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: Helvetica,Arial,sans-serif;border-top: 1px solid #c8c8c8;font-size: 15px;font-weight: bold;padding: 5px;'>
                                        <span style='font-weight:bold;' mc:edit='UTSClink'>U OF T <a href='http://learn.utoronto.ca/utsc' target='_blank' style='border:none;color:#065797;font-weight:bold;text-decoration:none;'>SCARBOROUGH</a></span>
                                    </td>
                                </tr>
                                <tr id='footerWrapper'>
                                    <td align='left' colspan='12' valign='top' width='100%' style='border-collapse: collapse;color: #6d6d6d;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: Helvetica,Arial,sans-serif;border-top: 1px solid #c8c8c8;font-size: 11px;line-height: 13px;padding-bottom: 25px;padding-top: 15px;' mc:edit='footer'>
                                        You have received this e-mail as a current, past, or prospective course participant at the School of Continuing Studies.
                                        <br /><br />
                                        University of Toronto, School of Continuing Studies 158 St. George Street Toronto, Ontario M5S 2V8 Canada
                                        <br>
                                        (416) 978-2400&nbsp;&nbsp;<span class='break'>|&nbsp;&nbsp;</span>
                                        <a href='http://learn.utoronto.ca' style='word-wrap: break-word;border-bottom-style: dotted;border-bottom-width: 1px;border-bottom-color: #065797;color: #065797;text-decoration: none;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;'>http://learn.utoronto.ca</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                                        <a href='http://www.utoronto.ca/privacy' target='_blank' style='word-wrap: break-word;border-bottom-style: dotted;border-bottom-width: 1px;border-bottom-color: #065797;color: #065797;text-decoration: none;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;'>Privacy Policy</a>
                                    </td>
                                </tr>
                                </tbody>
                                <tbody>
                                <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                </tbody>
                            </table>
                        </center>
                        </body>
                    </html>
        ");
    }

    /**
     *
     */
    public function buildBlankEmail($list){
        $fname = "SCS Tech Support";
        $lname = "";
        $email = "scs.techsupport@utoronto.ca";

        $this->setToAddress($email, $fname . ' ' . $lname);
        $this->setFromAddress('elearning@utoronto.ca');
        $this->setBccAddress('');
        $this->setAltBody('Please view this email from an HTML enabled mail client.');
        $this->setSubject('QQID Processed Students With Blank Emails');

        $this->setBody("Please see the following list of students without emails listed in Destiny:<br/>".$list."<br/><br/>Regards,<br/>QQID Processor");
    }

    /**
     * @return mixed
     */
    public function getMailError()
    {
        return $this->mailError;
    }

    /**
     * @param mixed $mailError
     */
    public function setMailError($mailError)
    {
        $this->mailError = $mailError;
    }

    /**
     * @return mixed
     */
    public function getMailSend()
    {
        return $this->mailSend;
    }

    /**
     * @param mixed $mailSend
     */
    public function setMailSend($mailSend)
    {
        $this->mailSend = $mailSend;
    }

    /**
     * @return mixed
     */
    public function getQqidBody()
    {
        return $this->qqidBody;
    }

    /**
     * @param mixed $qqidBody
     */
    public function setQqidBody($qqidBody)
    {
        $this->qqidBody = $qqidBody;
    }

    /**
     * @return mixed
     */
    public function getToAddress()
    {
        return $this->toAddress;
    }

    /**
     * @param mixed $toAddress
     */
    public function setToAddress($toAddress)
    {
        $this->toAddress = $toAddress;
    }

    /**
     * @return mixed
     */
    public function getAltBody()
    {
        return $this->altBody;
    }

    /**
     * @param mixed $altBody
     */
    public function setAltBody($altBody)
    {
        $this->altBody = $altBody;
    }

    /**
     * @return mixed
     */
    public function getBccAddress()
    {
        return $this->bccAddress;
    }

    /**
     * @param mixed $bccAddress
     */
    public function setBccAddress($bccAddress)
    {
        $this->bccAddress = $bccAddress;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getFromAddress()
    {
        return $this->fromAddress;
    }

    /**
     * @param mixed $fromAddress
     */
    public function setFromAddress($fromAddress)
    {
        $this->fromAddress = $fromAddress;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

}