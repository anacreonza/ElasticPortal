# Deployment guide for ElasticPortal site:

0. Make a backup of the existing site by renaming the existing site's folder.
1. From the root directory where your websites are shared from clone the Git repo with `git clone https://github.com/anacreonza/ElasticPortal.git`
2. Make sure PHP composer is installed [Get Composer](https://getcomposer.org)
3. Run `composer install` in the directory that the repository was cloned into. This will install all the dependencies for the project.
4. Edit and update the .env file if necessary. The .env file is the settings file for the site. 
5. Run a database migration with `php artisan migrate`

