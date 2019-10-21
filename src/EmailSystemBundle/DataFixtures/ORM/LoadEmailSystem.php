<?php
namespace EmailSystemBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EmailBundle\Entity\EmailSystem;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUEmailData implements FixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $emails = $this->getEmails();

        foreach ($emails as $fakeEmail){
            $email = new EmailSystem();
            $email->setLabel($fakeEmail->label);
            $email->setSubject($fakeEmail->subject);
            $email->setBodyHtml($fakeEmail->bodyHtml);
            $email->setBodyText($fakeEmail->bodyText);
            $email->setCode($fakeEmail->code);
            $email->setBundleLabel($fakeEmail->bundleLabel);

            $manager->persist($email);
        }

        $manager->flush();
    }

    public function getEmails()
    {
        return [
            (object) array(
                'label' => 'E-mail de bienvenue',
                'subject' => "{associationName} : Bienvenue dans notre association",
                'bodyHtml' => "<p>Bonjour {lastName} {firstName},<br /> <br /> Merci de rejoindre notre association.<br /> <br /> A bient&ocirc;t dans notre association.<br /> <br /> Cordialement,<br /> <br /> L'&eacute;quipe de {associationName}</p>",
                'bodyText' => "Bonjour {lastName} {firstName}, \r\n\r\n Merci de rejoindre notre association. \r\n\r\n A bientôt dans notre association. \r\n\r\n Cordialement, \r\n\r\n L'équipe de {associationName}",
                'bundleLabel' => "Inscription",
                'code' => "SUBCRIPTION_WELCOME"

            ),
            (object) array(
                'label' => 'E-mail Mot de passe perdu',
                'subject' => "{associationName} : Demande de changement de mot de passe",
                'bodyText' => "Bonjour [name],\r\n\r\n Une demande de modification de mot de passe a été effectué pour cet e-mail.\r\n
                        Si vous n'êtes pas l'auteur de cette demande veuillez simplement l'ignorer, \r\n
                        sinon veuillez cliquer le lien suivant : [link] pour changer de mot de passe\r\n
                        et suivre les instructions de la page qui va s'afficher.\r\n\r\nCordialement,",
                'bodyHtml' => "Bonjour {name},<br/>
                        <br/>
                        Une demande de modification de mot de passe a été effectué pour cet e-mail.<br/>
                        Si vous n'êtes pas l'auteur de cette demande veuillez simplement l'ignorer, <br/>
                        sinon veuillez cliquer le lien suivant <a href=\"{link}\">changer de mot de passe</a><br/>
                        et suivre les instructions de la page qui va s'afficher.<br/>
                        <br/>
                        Cordialement,",
                'bundleLabel' => "Sécurité",
                'code' => "SECURITY_LOST_PASSWORD"
            ),
            (object) array(
                'label' => 'E-mail de confirmation de souscription à un abonnement',
                'subject' => "{associationName} : Confirmation inscription \"{subscriptionLabel}\"",
                'bodyHtml' => "<p>Bonjour {lastName} {firstName},<br /> <br /> Merci pour votre abonnement &agrave; \"{subscriptionLabel}\".<br /> <br /> Votre abonnement commence le {startDate} et fini le {endDate} pour un co&ucirc;t de {totalCost} &euro;.<br /> <br /> A bient&ocirc;t dans votre FabLab.<br /> <br /> Cordialement,<br /> <br /> L'&eacute;quipe de {associationName}</p>",
                'bodyText' => "Bonjour {lastName} {firstName}, \r\n\r\n Merci pour votre abonnement à \"{subscriptionLabel}\".\r\n\r\n Votre abonnement commence le {startDate} et fini le {endDate} pour un co&ucirc;t de {totalCost} &euro;.\r\n\r\n A bientôt dans notre association.\r\n\r\n Cordialement,\r\n\r\n L'équipe de {associationName}",
                'bundleLabel' => "Abonnement",
                'code' => "SUBSCRIPTION_CONFIRM"
            ),
            (object) array(
                'label' => 'E-mail de notification de nouvelle échéance de cotisation',
                'subject' => "{associationName} : Une nouvelle échéance de cotisation pour l'abonnement  \"{subscriptionLabel}\" arrive",
                'bodyHtml' => "<p>Bonjour {lastName} {firstName},<br /> <br />Votre cotisation pour l'abonnement \"{subscriptionLabel}\" arrive &agrave; &eacute;ch&eacute;ance (le {endDate}).</p>
<p>Une nouvelle cotisation est pr&eacute;vu le {startDateNewFee}, vous pouvez r&eacute;gler votre nouvelle cotisation &agrave; tout moment avant son &eacute;ch&eacute;ance.</p>
<p>Si ce n'est pas le cas, votre compte sera d&eacute;sactiv&eacute; et vous ne pourez plus b&eacute;n&eacute;ficier de nos service.</p>
<p>Cordialement,<br /> <br /> L'&eacute;quipe de {associationName}</p>",
                'bodyText' => "Bonjour {lastName} {firstName},\r\n\r\nVotre cotisation pour l'abonnement \"{subscriptionLabel}\" arrive à échéance (le {endDate}).\r\n
\r\nUne nouvelle cotisation est pr&eacute;vu le {startDateNewFee}, vous pouvez r&eacute;gler votre nouvelle cotisation &agrave; tout moment avant son &eacute;ch&eacute;ance.\r\n
\r\nSi ce n'est pas le cas, votre compte sera désactivé et vous ne pourez plus bénéficier de nos service.\r\n
\r\nCordialement,\r\n\r\n L'équipe de {associationName}",
                'bundleLabel' => "Abonnement",
                'code' => "SUBSCRIPTION_NEW_FEE_COMING"
            ),
            (object) array(
                'label' => "E-mail de notification de retard d'échéance de cotisation",
                'subject' => "{associationName} : Notification retard de paiement \"{subscriptionLabel}\"",
                'bodyHtml' => "<<p>Bonjour {lastName} {firstName},<br /> <br />Votre cotisation pour l'abonnement \"{subscriptionLabel}\" arrive &agrave; &eacute;ch&eacute;ance (le {endDate}).</p>
<p>Une nouvelle cotisation est pr&eacute;vu le {startDateNewFee}, vous pouvez r&eacute;gler votre nouvelle cotisation &agrave; tout moment avant son &eacute;ch&eacute;ance.</p>
<p>Si ce n'est pas le cas, votre compte sera d&eacute;sactiv&eacute; et vous ne pourez plus b&eacute;n&eacute;ficier de nos service.</p>
<p>Cordialement,<br /> <br /> L'&eacute;quipe de {associationName}</p>",
                'bodyText' => "Bonjour {lastName} {firstName},\r\n\r\nVotre cotisation pour l'abonnement \"{subscriptionLabel}\" arrive à échéance (le {endDate}).\r\n
\r\nUne nouvelle cotisation est prévu le {startDateNewFee}, vous pouvez régler votre nouvelle cotisation à tout moment avant son échéance.\r\n
\r\nSi ce n'est pas le cas, votre compte sera d&eacute;sactiv&eacute; et vous ne pourez plus b&eacute;n&eacute;ficier de nos service.\r\n
\r\nCordialement,\r\n\r\n L'équipe de {associationName}\r\n",
                'bundleLabel' => "Abonnement",
                'code' => "SUBSCRIPTION_LATE_FEE_PAYMENT"
            )
        ];
    }
}