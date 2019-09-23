<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use EmailBundle\Entity\Email;

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
        $encoder = $this->container->get('security.password_encoder');

        $emails = $this->getEmails();

        foreach ($emails as $fakeEmail){
            $email = new Email();
            $email->setLabel($fakeEmail->label);
            $email->setSubject($fakeEmail->subject);
            $email->setBody($fakeEmail->body);
            $email->setSystem($fakeEmail->automatic);
            $email->setStatus(Email::STATUS_AUTOMATIC);


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
                'body' => "<p>Bonjour {lastName} {firstName},<br /> <br /> Merci de rejoindre notre association.<br /> <br /> A bient&ocirc;t dans votre FabLab.<br /> <br /> Cordialement,<br /> <br /> L'&eacute;quipe de {associationName}</p>",
                'automatic' => true
            ),
            (object) array(
                'label' => 'E-mail Mot de passe perdu',
                'subject' => "{associationName} : Demande de changement de mot de passe",
                'body' => "Bonjour [name],<br/>
                        <br/>
                        Une demande de modification de mot de passe a été effectué pour cet e-mail.<br/>
                        Si vous n'êtes pas l'auteur de cette demande veuillez simplement l'ignorer, <br/>
                        sinon veuillez cliquer le lien suivant <a href=\"[link]\">changer de mot de passe</a><br/>
                        et suivre les instructions de la page qui va s'afficher.<br/>
                        <br/>
                        Cordialement,",
                'automatic' => true
            ),
            (object) array(
                'label' => 'E-mail de confirmation de souscription à un abonnement',
                'subject' => "{associationName} : Confirmation inscription \"{subscriptionLabel}\"",
                'body' => "<p>Bonjour {lastName} {firstName},<br /> <br /> Merci pour votre abonnement &agrave; \"{subscriptionLabel}\".<br /> <br /> Votre abonnement commence le {startDate} et fini le {endDate} pour un co&ucirc;t de {totalCost} &euro;.<br /> <br /> A bient&ocirc;t dans votre FabLab.<br /> <br /> Cordialement,<br /> <br /> L'&eacute;quipe de {associationName}</p>",
                'automatic' => true
            ),
            (object) array(
                'label' => 'E-mail de notification de nouvelle échéance de cotisation',
                'subject' => "{associationName} : Une nouvelle échéance de cotisation pour l'abonnement  \"{subscriptionLabel}\" arrive",
                'body' => "<p>Bonjour {lastName} {firstName},<br /> <br />Votre cotisation pour l'abonnement \"{subscriptionLabel}\" arrive &agrave; &eacute;ch&eacute;ance (le {endDate}).</p>
<p>Une nouvelle cotisation est pr&eacute;vu le {startDateNewFee}, vous pouvez r&eacute;gler votre nouvelle cotisation &agrave; tout moment avant son &eacute;ch&eacute;ance.</p>
<p>Si ce n'est pas le cas, votre compte sera d&eacute;sactiv&eacute; et vous ne pourez plus b&eacute;n&eacute;ficier de nos service.</p>
<p>Cordialement,<br /> <br /> L'&eacute;quipe de {associationName}</p>",
                'automatic' => true
            ),
            (object) array(
                'label' => 'E-mail de notification de nouvelle échéance de cotisation',
                'subject' => "{associationName} : Notification retard de paiement \"{subscriptionLabel}\"",
                'body' => "<<p>Bonjour {lastName} {firstName},<br /> <br />Votre cotisation pour l'abonnement \"{subscriptionLabel}\" arrive &agrave; &eacute;ch&eacute;ance (le {endDate}).</p>
<p>Une nouvelle cotisation est pr&eacute;vu le {startDateNewFee}, vous pouvez r&eacute;gler votre nouvelle cotisation &agrave; tout moment avant son &eacute;ch&eacute;ance.</p>
<p>Si ce n'est pas le cas, votre compte sera d&eacute;sactiv&eacute; et vous ne pourez plus b&eacute;n&eacute;ficier de nos service.</p>
<p>Cordialement,<br /> <br /> L'&eacute;quipe de {associationName}</p>",
                'automatic' => true
            ),
            (object) array(
                'label' => 'E-mail de notification de nouvelle échéance de cotisation',
                'subject' => "{associationName} : Notification retard de paiement \"{subscriptionLabel}\"",
                'body' => "<p>Bonjour {lastName} {firstName},</p>
<p>Vous recevez cet e-mail car notre logiciel a d&eacute;tect&eacute; que vous &ecirc;tes en retard de paiement pour l'abonnement suivant :&nbsp; \"{subscriptionLabel}\".</p>
<p>Veuillez r&eacute;gulariser votre situation au plus vite, sinon nous serons dans l'obligation de vous d&eacute;sincrire de notre association et vous ne pourrez plus pr&eacute;tendre aux services que nous vous proposons.</p>
<p>&nbsp;Cordialement,</p>
<p>L'&eacute;quipe de {associationName}</p>",
                'automatic' => true
            )
        ];
    }
}