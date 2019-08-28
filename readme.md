# Web portal to Media24's ElasticSearch archive.

A portal site, designed to give people access the Media24's ElasticSearch archive without needing the desktop software to be installed. It shows 4 different panes for 4 types of content: images, stories (XML files from EidosMedia Methodé), PDFs (printed pages) and HTML files - which are mostly maps of page layouts but also sometimes older archived web stories.

## Done:
* List of publications is now derived from an aggregation search.
* Added story viewer instead of just dumping raw stry XML


## To Do:

* Suggesters (needs reactive page)
* Get all filters working. Date range etc.
* Use the internal pagination function of ES instead of my own.
* Add fuzziness to searches.
* Add Adldap2 extension for auth.
* Give the app its own local DB (sqlite to allow portability?)
* Match exact words (but not in exact order)
* Search by category (news/comments)
