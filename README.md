<p align="center"><a href="https://unitedcode.net/" target="_blank"><img src="https://api.unitedcode.net/uploads/logo_alt_2cf2d465f4.svg" width="400"></a></p>


# Test Job for Full-Stack PHP Web Developer

## Terms of assignment

Create a web parsing basis functionality with the next logic:

1. Load data about articles from https://laravel-news.com/blog with tag 'news' for the last 4 months
2. Show loaded data on the main page as a table with the next fields:
    * publication date in a format - 'd.m.Y'
    * title as a link to the article page
    * author name
    * all tags associated with article separated by a comma
3. Articles should be sorted alphabetically by author name
4. A possibility of manual sorting by title and date should be available also
5. Articles should be loaded once and saved in DB
6. Every page reload should show data from DB
7. Additionally add a command for updating the articles' data

#### Requirements

Functionality should be built by using Laravel

OOP should be used

Code should be well commented

#### You should use at least next:

* Laravel 6.x
* MySQL(MariaDB)
* composer
* git

#### User interface

There are no strict requirements for the interface. However, a pretty interface may be a plus

#### The result

The test task result should be a Laravel based project with at lead described functionality.

Any additional features or code structures demonstration may be a plus

All needed instructions for run the project should be described in 'readme.md'

The project should be stored in a git repository located on github.com or a similar service

## How to install

1. First, clone this repository.
2. Edit `.env` and set your database connection details.
3. If installed via git clone or download, run `php artisan key:generate`.
4. `php artisan migrate`
5. `composer install`
6. `npm install`
7. After all need to import articles `php artisan news:import`. If you want to import data 
for a different number of months, set the flag `--monthNumber`, for example, import data for 6 months:
`php artisan news:import --monthNumber=6`.

## Start to use

```bash
$ npm run build
$ php artisan serve
```

Then, there will be a notification that the site is waiting at some address, 
such as this `http://127.0.0.1:8000`.

## License

The project is built on the Laravel framework which is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
