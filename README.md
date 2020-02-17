[![Latest Stable Version](https://poser.pugx.org/artoodetoo/so-example/v/stable)](https://packagist.org/packages/artoodetoo/so-example)
[![License](https://poser.pugx.org/artoodetoo/so-example/license)](https://packagist.org/packages/artoodetoo/so-example)

# StackOverflow DB for Laravel

## Introduction and Disclaimer

It won't be a S.O. clone in any meaning. My interest is to get big enough and close-to-real-world DB sandbox. And get it ready to Laravel Eloquent.  

To be precise, it is the common structure of a several Q-n-A sites of StackExchange, not only stackeoverflow.com.  
Please start from the awesome [Brent Ozar post](https://www.brentozar.com/archive/2015/10/how-to-download-the-stack-overflow-database-via-bittorrent/) to get more information about the topic.

Original DB is in *MS SQL* and dump is available in *XML*. 
Database has obfuscated private information and it's publicly available under [cc-by-sa](https://creativecommons.org/licenses/by-sa/4.0/) license.

My humble goal is *MySQL* database in Laravel standards. 
It will be a set of migrations, classes and console command to import DB dump and fill the gaps when required.  
Since it is a one time action, *I DON'T try to do import as fast as possible.* I know, SQL can be much more effective than cycles on the PHP side. But that is.  

*NOTE:*  
> As of v0.1, the tables do not have indexes and foreign keys. It allows to import data fast but the queries are slow.    
> Starting from v0.2, I have been working on data consistency and queries optimization, so there will be indexes and FKs in migrations.  
>
> Feel free to run `git reset <tagname> --hard` or `git pull` to get this or that set of migrations _before_ the import.


## Laravelization

Now all names are in snake_case.  
It is common to use `created_at` and `updated_at` names for timestamps, so I replaced original field names to these ones when applicable.  
As for `users` table, I make it compatible with standard Laravel auth, so I added some fields.  
I shorted a couple of super long names like `PostHistoryTypeId` => `history_type_id`.  
To use unsigned big integer for primary and foreign keys, I have replaced special value of "-1" to "1".       

## Missing data

For privacy purposes, some fields are not mentioned in the dump. But they obviously required, like email and password. See my notes about `users` above.   
`Votes` table miss the voting user reference. It is null in most cases (exceptions are bounties and favs), it looks pretty unreal and have to be filled by fake data.  
`Posts` and `Tags` should be related as many-to-many. The pivot table is missing and the only source is denormalized field `posts`.`tags`.   
  
## Usage

### Installation 

```bash
composer create-project artoodetoo/so-structure
```  
Then go to new project directory, set database parameters in the .env file and run 

```bash
php artisan migrate
``` 
to create all the tables.  

### Data Import and post-processing

Download database dump from Web Archive site or via torrent. (you can find links in  Brent's article)  
Import tables content one by one:

```bash
php artisan stack:import path/to/Table.xml
```
Typically, for each site you have 8 files or tables to import:  
`Users`, `Badges`, `Tags`, `Posts`, `Comments`, `Votes`, `PostLinks`, `PostHistory`.    
The 9th table `post_tag` does not come out of the box. To recreate its data you have to run

```bash
php artisan stack:post-tag
```
Votes in the dump are anonymous. The only exception is favorite and bounty actions.  
To attach acceptance and up/down votes to random users, call

```bash
php artisan stack:vote-users --action=accept
php artisan stack:vote-users --action=up-down
```
(These are very slow) 

## License

StackOverflow DB for Laravel is open-sourced software licensed under the [MIT license](LICENSE.md).
