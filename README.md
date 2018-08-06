# RESTful PHP API

![](https://phptechsolutions.files.wordpress.com/2014/11/android_php_mysql.png)

<br/>

### Installation:
Install composer if you haven't: https://getcomposer.org/download
1. In main directory, run this php command to install all dependencies:
`php composer.phar install`
2. Rename **config.php.example** to **config.php** and set your own configurations.

<br/>

### Using API
This project is using single file API: **api.php**
Example:
`http://localhost/my_api/api.php/register` --> for registing new user.
<br/>
`http://localhost/my_api/api.php/get/customers` --> will retrieve all rows in customers table.
</br>
- Request is always using POST method. For CRUD, set **token** at Authorization on request header.
<br/>
`Authorization: Bearer MY_SECRET_TOKEN`
<br/>
- For registration set `Content-Type:application/x-www-form-urlencoded` on request header.

<br/>

### This repository is using:
- [Slim Framework](https://www.slimframework.com)
- [PHP JWT](https://github.com/lindelius/php-jwt)
- [PHP MySQLi Database Class](https://github.com/ThingEngineer/PHP-MySQLi-Database-Class)