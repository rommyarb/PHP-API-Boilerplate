# PHP API Boilerplate

![](https://phptechsolutions.files.wordpress.com/2014/11/android_php_mysql.png)

<br/>

### Requirements
- PHP 7.1
- OpenSSL PHP extension (for certain algorithms)

<br/>

### Installation:
Install composer if you haven't: https://getcomposer.org/download
1. In main directory, run this php command to install all dependencies:
`php composer.phar install`
2. Rename **config.php.example** to **config.php** and set your own configurations.

<br/>

### Using API:
This project is using single file API: **api.php**
<br/>
Example:
<br/>
`http://localhost/my_api/api.php/register` --> for registing new user.
<br/>
`http://localhost/my_api/api.php/get/users` --> will retrieve all rows in users table.
</br>
- Request is always using POST method. For CRUD, set **token** at Authorization on request header.<br/>
`Authorization: Bearer MY_SECRET_TOKEN`
- For registration set `Content-Type:application/x-www-form-urlencoded` on request header.

### Sending email:
....

<br/>

### This repository is using:
- [Slim Framework](https://www.slimframework.com)
- [PHP JWT](https://github.com/lindelius/php-jwt)
- [SimpleCrud](https://github.com/oscarotero/simple-crud)
- [Swiftmailer](https://github.com/swiftmailer/swiftmailer)
