# Todo Overview
User can come login and create a todo for him or can assign to someone

## Installation Guide

1. Use the package manager [composer](https://getcomposer.org/download/) to install Dependency of OurJobFlow.
2. PHP Version Needed and Apache Needed [xampp](https://www.apachefriends.org/download.html) to download and Install.
3. After Downloading need a repo [Github](https://github.com/saurabhnema27/todolist) for this you need a correct access rights.
4. After cloning it put it in your htdocs folder
5. After geeting this you need to do the composer install command.
6. migrate the database.
7. install the passport by using cmd.
8. Clean the cache if required.
9. copy the env files from server and put in local env.



```bash
composer install
```

```bash
php artisan migrate
```
```bash
php artisan passport:install
```

```bash
php artisan cache:clean
```


## Contributing
Contributed by Saurabh Nema.

## License
[MIT](https://choosealicense.com/licenses/mit/)
