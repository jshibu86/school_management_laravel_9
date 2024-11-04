<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

# Installing Instructions School Management

Laravel --version 8

## System Requirements

```shell
php version 7.4 and above
```

To get started with development, you need to install few tools

1. git

    `git` version 2.13.1 or higher. Download [git](https://git-scm.com/downloads) if you don't have it already.

    To check your version of git, run:

    ```shell
     git --version
     git config --global user.name "John Doe"
     git config --global user.email johndoe@example.com
    ```

2. node

    `node` version 16.15.1 or higher. Download [node](https://nodejs.org/en/download/) if you don't have it already.

    To check your version of node, run:

    ```shell
     node --version
    ```

3. npm

    `npm` version 5.6.1 or higher. You will have it after you install node.

    To check your version of npm, run:

    ```shell
     npm --version
    ```

4. composer

    Open [https://getcomposer.org/download/](https://getcomposer.org/download/) to view it in your browser. You will have it after you install composer.

    To check your version of composert, run:

    ```shell
     composer --version
    ```

## Setup

To set up a development environment, please follow these steps:

1. Clone the repo

    ```shell
     git clone https://gitlab.com/schoolmanagement9690705/schoolmanagement-nigeria.git
    ```

2. Checkout to development Branch

    ```shell
     git checkout development
    ```

3. get pull all things in development Branch

    ```shell
     git pull origin development
    ```

4. Install the dependencies

    ```shell
    composer install &&  npm install --force && npm run dev
    ```

5. Create a New Branch and work on

    ```shell
     git chekcout -b <branchname>

    ```

6. Firstly run migrate commands for tenant and tenant setup to school_management_central DB

    ```shell
     php artisan migrate

    ```

7. Next run Core module migration to school_management_central DB

    ```shell
     php artisan cms:migrate-core

    ```

8. Next run Core module Menu to school_management_central DB (NOTE : This menu and Module we have some exclude teannt db and Central db that Exclude array in Configurations.php we add more array values these core module not come to tenant db)

    ```shell
     php artisan update:cms-menu-core

    ```

9. Next run Core module to school_management_central DB (Same Before Note)

    ```shell
     php artisan update:cms-module-core

    ```

10. after update all modules seeder run subscription module this will contains all core and local modules in modules table these module we set to every subscription plan

    ```shell
     php artisan db:cms-seed --class=ModuleSeeder

    ```

11. Next run Central db Default Users (it will create default roles and users)

    ```shell
     php artisan db:cms-seed --class=SuperAdminSeeder

     super admin centraldb

     username:admin
     password:admin123

    ```

12. Run Tenants Migration (On boarded school run migrations if added any new migration it will affect all dbs)

    ```shell
     php artisan tenants:migrate

    ```

13. Update Tenants means on board schools update modules

    ```shell
     php artisan tenants:update-module

    ```

14. if you are add new Menus to update tenants Databases

    ```shell
     php artisan tenants:update-menu

    ```

15. if you want to add core module migration

    ```shell
     php artisan make:cms-migration-core alter_user_table_and_school_approvel schoolmanagement

    ```

16. After Completed work just push the code

    ```shell
     git push origin <your created branch name>

    ```

    If you get an error, please check the console for more information.

    If you don't get an error, you are ready to start development.

17. Run the app

    ```shell
    php artisan serve

    Username : admin
    Password : admin123
    ```

    Project will be running in the browser.

    Open [http://localhost:8000/administrator](http://localhost:8000/administrator) to view it in your browser.

18. Some useful Commands

    ```shell
    # Create a new module
    php artisan make:cms-module <modulename>
    #create module with crud views
    php artisan make:cms-module <modulename> --crud
    #create controller
    php artisan make:cms-controller <controller-name> <module-name>

    ```
