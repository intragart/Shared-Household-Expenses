# ![Shared Household Expenses](https://github.com/intragart/Shared-Household-Expenses/raw/master/webapp/www/content/img/logo-light.png)

![GitHub release (latest by date including pre-releases)](https://img.shields.io/github/v/release/intragart/Shared-Household-Expenses?include_prereleases)
![GitHub last commit](https://img.shields.io/github/last-commit/intragart/Shared-Household-Expenses)
![GitHub license](https://img.shields.io/github/license/intragart/Shared-Household-Expenses)

With this project you can track the household expenses per person. The idea is to achive that each person contributing to the household spends the same amount of money without any need for spreadsheets or shared bank accounts.

## Table of contents

- [Installation](#installation)
  - [Database](#database)
  - [Webserver](#webserver)
  - [Configuration](#configuration)
- [License](#license)

## Installation

### Database

The data is stored in a relational database like MariaDB or MySQL. Create a new database called 'shared_household_expenses' by executing the sql scripts in the follwoing order.

1. database/create_database.sql
1. database/create_tables.sql
1. database/create_views.sql
1. database/create_functions.sql
1. database/create_users.sql

Please make sure use proper passwords when executing 'database/create_users.sql'. You also might want to enable ssl communication to your database server.

### Webserver

Install any webserver that's able to communicate with the database you've choosen and clone this repository. Document root for the webserver is 'webapp/www'. The project doesn't work with file extensions (e. g. .php). Therefore, please configure the webserver to search for .php files with the same name if file/folder couldn't be found.

Example configuration for Apache webserver:

```text
<Directory "/path/to/Shared-Household-Expenses/webapp/www">
    
  Options Indexes FollowSymLinks Includes ExecCGI

  AllowOverride All

  Require all granted
    
  <ifModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME}.php -f
    RewriteRule ^(.+)$ $1.php [L,QSA]
  </ifModule>
    
</Directory>
```

### Configuration

Please find 'db_settings.template.json' in the 'config' folder, copy the json file and rename it to 'db_settings.json'. Fill in the information to access the database within the newly generated file.

Any additional configurations that might be generated by the application during usage will be saved in folder 'config' as well.

## License

Shared Household Expenses\
Copyright (C) 2023  Marco Weingart

This program is free software: you can redistribute it and/or modify\
it under the terms of the GNU General Public License as published by\
the Free Software Foundation, either version 3 of the License, or\
(at your option) any later version.

This program is distributed in the hope that it will be useful,\
but WITHOUT ANY WARRANTY; without even the implied warranty of\
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\
GNU General Public License for more details.

You should have received a copy of the GNU General Public License\
along with this program.  If not, see <https://www.gnu.org/licenses/>.
