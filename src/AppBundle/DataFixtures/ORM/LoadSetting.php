<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Setting;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadSettingData implements FixtureInterface, ContainerAwareInterface
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
        $settings = $this->getFakeSetting();

        foreach ($settings as $fakeSetting){
            $setting = new Setting();

            $setting->setType($fakeSetting->type)
                ->setCode($fakeSetting->code)
                ->setValue($fakeSetting->value)
            ;

            $manager->persist($setting);
        }

        $manager->flush();
    }

    public function getFakeSetting()
    {
        return [
            (object) [
                'code' => 'app.setting.logo',
                'type' => 'file',
                'value' => '',
            ],
            (object) [
                'code' => 'app.setting.name',
                'type' => 'string',
                'value' => 'Member relationship manager',
            ],
            (object) [
                'code' => 'app.setting.association_name',
                'type' => 'string',
                'value' => "Your association name",
            ],
            (object) [
                'code' => 'app.setting.description',
                'type' => 'text',
                'value' => 'Manage your association',
            ],
            (object) [
                'code' => 'app.setting.contact_email',
                'type' => 'string',
                'value' => '',
            ],
            (object) [
                'code' => 'app.setting.gsm',
                'type' => 'string',
                'value' => '',
            ],
            (object) [
                'code' => 'app.setting.phone',
                'type' => 'string',
                'value' => '',
            ],
            (object) [
                'code' => 'app.setting.country',
                'type' => 'string',
                'value' => '',
            ],
            (object) [
                'code' => 'app.setting.city',
                'type' => 'string',
                'value' => '',
            ],
            (object) [
                'code' => 'app.setting.zipcode',
                'type' => 'string',
                'value' => '',
            ],
            (object) [
                'code' => 'app.setting.address',
                'type' => 'string',
                'value' => '',
            ],
            (object) [
                'code' => 'app.setting.url_facebook',
                'type' => 'string',
                'value' => '',
            ],
            (object) [
                'code' => 'app.setting.url_twitter',
                'type' => 'string',
                'value' => '',
            ],
            (object) [
                'code' => 'app.user.default.avatar',
                'type' => 'string',
                'value' => '/images/avatars/default/userDefault.png',
            ],
            (object) [
                'code' => 'member.setting.expertise',
                'type' => 'array',
                'value' => '',
            ],
            (object) [
                'code' => 'member.setting.gender',
                'type' => 'array',
                'value' => '',
            ],
            (object) [
                'code' => 'member.setting.study',
                'type' => 'array',
                'value' => '',
            ],
            (object) [
                'code' => 'member.email.welcome_subject',
                'type' => 'string',
                'value' => "{associationName} : Bienvenue dans notre association",
            ],
            (object) [
                'code' => 'member.email.welcome_body',
                'type' => 'text',
                'value' => "<p>Bonjour {lastName} {firstName},<br /> <br /> Merci de rejoindre notre association.<br /> <br /> A bient&ocirc;t dans votre FabLab.<br /> <br /> Cordialement,<br /> <br /> L'&eacute;quipe de {associationName}</p>",
            ],
            (object) [
                'code' => 'member.email.new_subscription_subject',
                'type' => 'string',
                'value' => "{associationName} : Confirmation inscription \"{subscriptionLabel}\"",
            ],
            (object) [
                'code' => 'member.email.new_subscription_body',
                'type' => 'text',
                'value' => "<p>Bonjour {lastName} {firstName},<br /> <br /> Merci pour votre abonnement &agrave; \"{subscriptionLabel}\".<br /> <br /> Votre abonnement commence le {startDate} et fini le {endDate} pour un co&ucirc;t de {totalCost} &euro;.<br /> <br /> A bient&ocirc;t dans votre FabLab.<br /> <br /> Cordialement,<br /> <br /> L'&eacute;quipe de {associationName}</p>",
            ],
            (object) [
                'code' => 'member.email.new_fee_coming_soon_email_subject',
                'type' => 'string',
                'value' => "{associationName} : Une nouvelle échéance de cotisation pour l'abonnement \"{subscriptionLabel}\" arrive",
            ],
            (object) [
                'code' => 'member.email.new_fee_coming_soon_email_body',
                'type' => 'text',
                'value' => "<p>Bonjour {lastName} {firstName},<br /> <br />Votre cotisation pour l'abonnement \"{subscriptionLabel}\" arrive &agrave; &eacute;ch&eacute;ance (le {endDate}).</p>
↵<p>Une nouvelle cotisation est pr&eacute;vu le {startDateNewFee}, vous pouvez r&eacute;gler votre nouvelle cotisation &agrave; tout moment avant son &eacute;ch&eacute;ance.</p>
↵<p>Si ce n'est pas le cas, votre compte sera d&eacute;sactiv&eacute; et vous ne pourez plus b&eacute;n&eacute;ficier de nos service.</p>
↵<p>Cordialement,<br /> <br /> L'&eacute;quipe de {associationName}</p>",
            ],
            (object) [
                'code' => 'subscription.delay.new_fee_coming_soon_email_sending',
                'type' => 'integer',
                'value' => 15,
            ],
            (object) [
                'code' => 'member.email.late_member_notification_email_subject',
                'type' => 'string',
                'value' => "{associationName} : Notification retard de paiement \"{subscriptionLabel}\"",
            ],
            (object) [
                'code' => 'member.email.late_member_notification_email_body',
                'type' => 'text',
                'value' => "<p>Bonjour {lastName} {firstName},</p>
↵<p>Vous recevez cet e-mail car notre logiciel a d&eacute;tect&eacute; que vous &ecirc;tes en retard de paiement pour l'abonnement suivant :&nbsp; \"{subscriptionLabel}\".</p>
↵<p>Veuillez r&eacute;gulariser votre situation au plus vite, sinon nous serons dans l'obligation de vous d&eacute;sincrire de notre association et vous ne pourrez plus pr&eacute;tendre aux services que nous vous proposons.</p>
↵<p>&nbsp;Cordialement,</p>
↵<p>L'&eacute;quipe de {associationName}</p>",
            ],
            (object) [
                'code' => 'subscription.delay.late_payment_member_email_sending',
                'type' => 'integer',
                'value' => 15,
            ]
        ];
    }
}