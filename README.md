Lyon-0217-communit
==================

[![Visual Studio Team services](https://img.shields.io/badge/Do%20at-WildCodeSchool-orange.svg)]()

Do an intranet for clickDev !

## Important
Since MYSQL 5.7, GROUP BY management have changed please add : 
```
sql_mode = "STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"
```
in : 
```
/etc/mysql/mysql.conf.d
```

## Getting Started

```bash
# install dependencies
composer install

#load fixtures
bin/console doctrine:fixture:load

# run server
bin/console server:run 
```
## Dev Started

```bash
# if gulpLoader.sh can"t execute 
sudo chmod +x gulpLoader.sh

# install gulp dependencies
./gulpLoader.sh

# run browser-sync
gulp browser-sync
```

## Build with

- [Symfony](http://symfony.com) - The PHP framework
- [Bootstrap](http://getbootstrap.com) - The CSS framework
- [Jquery](http://jquery.com) - The Javascript library

We also use :

- [GulpJs](http://gulpjs.com) - A tool dev, for our work

## Authors

* **Irena Jakubec-Berenguel** _alias_ [@irjabe](https://github.com/irjabe)
* **Johan Gaub** _alias_ [@JohanGaub](https://github.com/JohanGaub)
* **François Letellier** _alias_ [@Topikana](https://github.com/Topikana)
* **Julien Mastromonaco** _alias_ [@JuMastro](https://github.com/JuMastro)

You can see all [contributors](https://github.com/WildCodeSchool/Lyon-0217-communit/contributors) here !


## More

A Symfony project created on April 13, 2017, 9:43 am.
