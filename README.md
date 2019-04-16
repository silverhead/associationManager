# associationManager
Help the associations to manage their member and communicate with them.

The application is running in [Symfony 3.4](https://symfony.com/) for the backend and use [AdminBSBMaterialDesign](https://github.com/gurayyarar/AdminBSBMaterialDesign) (use [bootstrap 3.7](https://getbootstrap.com/docs/3.3/)) for the frontend.

Be careful : The actual version is a beta version, use for test only.

The application is in french only for the moment.

## Required configuration (tested)

Server Apache 2.4.18, PHP 7.1.26 and MySQL 5.7.25 on Ubuntu 16.04.1 and activate rewrite, pdo_mysql php extensions.
You need  to install [composer](https://getcomposer.org/) for install and update symfony.
And for more easier installation and update application you need [git](https://git-scm.com/).

## Installation

1. In your root directory of your project, tape : `git clone https://github.com/silverhead/associationManager.git`
2. Use composer for install dependency, tape : `php composer install` or `php composer.phar install`
3. Set your parameters (in end of composer installation), if you need to set parameters edit parameters.yml file in `app/config/parameters.yml`
4. Create the database into your mysql server (use utf8mb4 as default CHARACTER SET)
5. Create schema of your database application, tape : `php bin/console doctrine:schema:create`
6. Add default setting of application and the admin user, tape : `php bin/console doctrine:fixtures:load`
7. Clear cache, tape : `php bin/console cache:clear --env=prod`

Congratulation, your application is ready to use!!! :-)

## First connexion

The only user create is the administrator :
username : admin
password : admin123

It's stronger recommended to change the password and the username of administrator

## Configuration

Once logged in, you must allow the user "admin" to see all modules, got to "menu" > "utilisateurs" > "groupes"

Click on the "Edit" button of the group "Administrateurs"
On the "Edit page" go to the "Autorisation" part and check all checkbox.
And click to the "Enregistrer et rester" for save the setting.

You can see that new entries in the menu appeared.

You can navigate in these new entries to set them.

For example, you can click on "Paramètre" menu for set the general application setting.

Be careful to add member setting : "Adhérents" > "Configuration"
and member status before add first member.
