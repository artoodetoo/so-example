# StackOverflow DB for Laravel

To be precise, it is the common structure of a several Q-n-A sites of StackExchange, not only stackeoverflow.com.  
Please start from the awesome [Brent Ozar post](https://www.brentozar.com/archive/2015/10/how-to-download-the-stack-overflow-database-via-bittorrent/) to get more information about the topic.
Brent do similar work for C# community.

## About this project

Origin al DB is in *MS SQL* and dump is available in *XML*. 
Database has obfuscated private information and it's publicly available under [cc-by-sa](https://creativecommons.org/licenses/by-sa/4.0/) license.

My humble goal is *MySQL* database suitable for Laravel-based projects. 
It will be a set of migrations, classes and console command to import DB dump.

## Laravelization

Now all names are in snake_case.  
It is common to use `created_at` and `updated_at` names for timestamps, so I replaced original field names to these ones when applicable.  
As for `users` table, I make it compatible with standard Laravel auth, so I added some fields.  
I shorted a couple of super long names like `PostHistoryTypeId` => `history_type_id`.  
To use unsigned big integer for primary and foreign keys, I have replaced special value of "-1" to "1".       

## Missing data

For privacy purposes, some fields are not mentioned in the dump. But they obviously required, like email and password. See my notes about `users` above.   
`Votes` table miss the voting user reference. I created it and leave null, but it looks pretty unreal.  
`Posts` and `Tags` should be related as many-to-many. The pivot table is missing and the only source is denormalized field `posts`.`tags`.   

## License

This project is free and distributed under MIT license
