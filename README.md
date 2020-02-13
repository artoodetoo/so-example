# StackOverflow DB for Laravel

To be precise, it is the common structure of a several Q-n-A sites of StackExchange, not only stackeoverflow.com.  
Please start from the awesome [Brent Ozar post](https://www.brentozar.com/archive/2015/10/how-to-download-the-stack-overflow-database-via-bittorrent/) to get more information about the topic.
Brent do similar work for C# community.

## About this project

Origin al DB is in *MS SQL* and dump is available in *XML*. 
Database has obfuscated private information and it's publicly available under [cc-by-sa](https://creativecommons.org/licenses/by-sa/4.0/) license.

My humble goal is *MySQL* database suitable for Laravel-based projects. 
Names will be changed to snake_case and in certain cases replaced become more laravelish.  
It will be set of migrations and console command to import DB dump.

## License

This project is free and distributed under MIT license
